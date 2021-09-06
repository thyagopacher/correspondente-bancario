<?php

session_start();
$cpf = $_POST["cpf"];
if (isset($_POST["cpf"]) && $_POST["cpf"] != NULL && $_POST["cpf"] != "") {

    function __autoload($class_name) {
        if (file_exists("../model/" . $class_name . '.php')) {
            include "../model/" . $class_name . '.php';
        } elseif (file_exists("../visao/" . $class_name . '.php')) {
            include "../visao/" . $class_name . '.php';
        } elseif (file_exists("./" . $class_name . '.php')) {
            include "./" . $class_name . '.php';
        }
    }

    $conexao = new Conexao();
    $utilitario = new Utilitario();
    $beneficio = new BeneficioCliente($conexao);
    $pessoa = new Pessoa($conexao);
    /*     * limpando cpf para pesquisar */
    $cpf = str_replace(".", "", $cpf);
    $cpf = str_replace("-", "", $cpf);
    $consulta_cpf = $beneficio->consultaCpfInss($cpf);
    $sql = "select codpessoa, nome, dtnascimento, mae, nit from pessoa where (cpf = '{$cpf}' or cpf = '{$_POST["cpf"]}') and codempresa = '{$_SESSION['codempresa']}'";
    $pessoap = $conexao->comandoArray($sql);

    if (isset($consulta_cpf->consulta->ok) && $consulta_cpf->consulta->ok != NULL && $consulta_cpf->consulta->ok != "") {
        $qtdbeneficio = count($consulta_cpf->consulta->consulta_cpf->resultado);
        $linhaConsulta = 0;
        
        foreach ($consulta_cpf->consulta->consulta_cpf->resultado as $key => $resultado2) {
            $beneficiop = $conexao->comandoArray("select numbeneficio, codbeneficio from beneficiocliente where codempresa = '{$_SESSION['codempresa']}' and numbeneficio = '{$resultado2->beneficio}'");
            $consulta_nb = $beneficio->consultaBenInss($resultado2->beneficio);
            if($consulta_nb->consulta->detalhamento->rubricas->msg == "DADOS DO EXTRATO OFFLINE"){
                continue;
            }
            //inserindo o telefone novo
            if (isset($consulta_nb->consulta->informacoes_beneficio->telefone) && $consulta_nb->consulta->informacoes_beneficio->telefone != NULL && $consulta_nb->consulta->informacoes_beneficio->telefone != "null" && $consulta_nb->consulta->informacoes_beneficio->telefone != "") {
                $telefone = new Telefone($conexao);
                $telefone->codpessoa = $pessoap["codpessoa"];
                if ($telefone->identificaCelular($consulta_nb->consulta->informacoes_beneficio->telefone)) {
                    $telefone->codtipo = "3";
                } else {
                    $telefone->codtipo = "1";
                }
                $telefone->numero = $consulta_nb->consulta->informacoes_beneficio->telefone;
                $resInserirTelefone = $telefone->inserir();
                if ($resInserirTelefone == FALSE) {
                    die(json_encode(array('mensagem' => "Erro ao inserir telefone!!! Causado por:" . mysqli_error($conexao->conexao), 'situacao' => false)));
                }
            }
            
            if (!isset($beneficiop["numbeneficio"]) || $beneficiop["numbeneficio"] == NULL || $beneficiop["numbeneficio"] == "") {
                $beneficio = new BeneficioCliente($conexao);
                $beneficio->codorgao = 3;
                if (isset($consulta_nb->consulta->informacoes_beneficio->codigo_banco) && $consulta_nb->consulta->informacoes_beneficio->codigo_banco != NULL && $consulta_nb->consulta->informacoes_beneficio->codigo_banco != "null" && $consulta_nb->consulta->informacoes_beneficio->codigo_banco != "") {
                    $sql = "select codbanco from banco where numbanco = '{$consulta_nb->consulta->informacoes_beneficio->codigo_banco}'";
                    $bancop = $conexao->comandoArray($sql);
                    if (isset($bancop["codbanco"]) && $bancop["codbanco"] != NULL && $bancop["codbanco"] != "" && $bancop["codbanco"] != "0") {
                        $beneficio->codbanco = $bancop["codbanco"];
                    } else {
                        $banco = new Banco($conexao);
                        $banco->nome = $consulta_nb->consulta->detalhadamento->banco;
                        $banco->numbanco = $consulta_nb->consulta->informacoes_beneficio->codigo_banco;
                        $banco->inserir();
                        $beneficio->codbanco = mysqli_insert_id($conexao->conexao);
                    }
                }
                $beneficio->banco = $consulta_nb->consulta->informacoes_beneficio->banco;
                $beneficio->numbeneficio = $resultado2->beneficio;
                $beneficio->codpessoa = $pessoap["codpessoa"];
                $beneficio->meio = $consulta_nb->consulta->informacoes_beneficio->meio_pagamento;
                $especiep = $conexao->comandoArray("select codespecie from especie where numinss = '{$resultado2->especie}'");
                if (isset($especiep["codespecie"]) && $especiep["codespecie"] != NULL && $especiep["codespecie"] != "" && $especiep["codespecie"] != "0") {
                    $beneficio->codespecie = $especiep["codespecie"];
                } else {
                    $especie = new Especie($conexao);
                    $especie->numinss = $especiep["codespecie"];
                    $especie->inserir();
                    $beneficio->codespecie = mysqli_insert_id($conexao->conexao);
                }
                if (isset($resultado2->data_inicio_beneficio) && $resultado2->data_inicio_beneficio != NULL && $resultado2->data_inicio_beneficio != "" && $resultado2->data_inicio_beneficio != "null") {
                    $beneficio->dtinicio = implode("-", array_reverse(explode("/", $resultado2->data_inicio_beneficio)));
                }
                if (isset($consulta_nb->consulta->informacoes_beneficio->agencia) && $consulta_nb->consulta->informacoes_beneficio->agencia != NULL && $consulta_nb->consulta->informacoes_beneficio->agencia != "null" && $consulta_nb->consulta->informacoes_beneficio->agencia != "") {
                    if (!isset($beneficiop["nome_agencia"]) || $beneficiop["nome_agencia"] == NULL || $beneficiop["nome_agencia"] == "") {
                        $beneficio->nome_agencia = $consulta_nb->consulta->informacoes_beneficio->agencia;
                    }
                }
                if (isset($consulta_nb->consulta->informacoes_beneficio->codigo_agencia) && $consulta_nb->consulta->informacoes_beneficio->codigo_agencia != NULL && $consulta_nb->consulta->informacoes_beneficio->codigo_agencia != "") {
                    if (!isset($beneficiop["agencia"]) || $beneficiop["agencia"] == NULL || $beneficiop["agencia"] == "") {
                        $beneficio->agencia = $consulta_nb->consulta->informacoes_beneficio->codigo_agencia;
                    }
                }
                if (isset($consulta_nb->consulta->informacoes_beneficio->endereco_agencia) && $consulta_nb->consulta->informacoes_beneficio->endereco_agencia != NULL && $consulta_nb->consulta->informacoes_beneficio->endereco_agencia != "") {
                    if (!isset($beneficiop["endereco_agencia"]) || $beneficiop["endereco_agencia"] == NULL || $beneficiop["endereco_agencia"] == "") {
                        $beneficio->endereco_agencia = $consulta_nb->consulta->informacoes_beneficio->endereco_agencia;
                    }
                }
                if (isset($consulta_nb->consulta->detalhamento->valor_margem_consignavel) && $consulta_nb->consulta->detalhamento->valor_margem_consignavel != NULL && $consulta_nb->consulta->detalhamento->valor_margem_consignavel != "") {
                    if (!isset($beneficiop["valor_margem_consignavel"]) || $beneficiop["valor_margem_consignavel"] == NULL || $beneficiop["valor_margem_consignavel"] == "" || $beneficiop["valor_margem_consignavel"] == "0") {
                        $beneficio->margem = $utilitario->converteRealAmericano($consulta_nb->consulta->detalhamento->valor_margem_consignavel);
                    }
                }
                if (isset($consulta_nb->consulta->informacoes_beneficio->conta_corrente) && $consulta_nb->consulta->informacoes_beneficio->conta_corrente != NULL && $consulta_nb->consulta->informacoes_beneficio->conta_corrente != "") {
                    if (!isset($beneficiop["contacorrente"]) || $beneficiop["contacorrente"] == NULL || $beneficiop["contacorrente"] == "") {
                        $beneficio->contacorrente = $consulta_nb->consulta->informacoes_beneficio->conta_corrente;
                    }
                }
                if (isset($consulta_nb->consulta->detalhamento->valor_beneficio) && $consulta_nb->consulta->detalhamento->valor_beneficio != NULL && $consulta_nb->consulta->detalhamento->valor_beneficio != "") {
                    if (!isset($beneficiop["salariobase"]) || $beneficiop["salariobase"] == NULL || $beneficiop["salariobase"] == "" || $beneficiop["salariobase"] == "0") {
                        $beneficio->salariobase = $utilitario->converteRealAmericano($consulta_nb->consulta->detalhamento->valor_beneficio);
                    }
                }
                if (isset($consulta_nb->consulta->informacoes_beneficio->valor_mr) && $consulta_nb->consulta->informacoes_beneficio->valor_mr != NULL && $consulta_nb->consulta->informacoes_beneficio->valor_mr != "") {
                    if (!isset($beneficiop["valor_mr"]) || $beneficiop["valor_mr"] == NULL || $beneficiop["valor_mr"] == "") {
                        $beneficio->valor_mr = $utilitario->converteRealAmericano($consulta_nb->consulta->informacoes_beneficio->valor_mr);
                    }
                }
                if (isset($consulta_nb->consulta->informacoes_beneficio->valor_debitos_fixos) && $consulta_nb->consulta->informacoes_beneficio->valor_debitos_fixos != NULL && $consulta_nb->consulta->informacoes_beneficio->valor_debitos_fixos != "") {
                    if (!isset($beneficiop["valor_debitos_fixos"]) || $beneficiop["valor_debitos_fixos"] == NULL || $beneficiop["valor_debitos_fixos"] == "") {
                        $beneficio->valor_debitos_fixos = $utilitario->converteRealAmericano($consulta_nb->consulta->informacoes_beneficio->valor_debitos_fixos);
                    }
                }
                if (isset($consulta_nb->consulta->informacoes_beneficio->valor_outros_creditos) && $consulta_nb->consulta->informacoes_beneficio->valor_outros_creditos != NULL && $consulta_nb->consulta->informacoes_beneficio->valor_outros_creditos != "") {
                    if (!isset($beneficiop["valor_outros_creditos"]) || $beneficiop["valor_outros_creditos"] == NULL || $beneficiop["valor_outros_creditos"] == "") {
                        $beneficio->valor_outros_creditos = $utilitario->converteRealAmericano($consulta_nb->consulta->informacoes_beneficio->valor_outros_creditos);
                    }
                }
                if (isset($consulta_nb->consulta->informacoes_beneficio->cartao_rmc) && $consulta_nb->consulta->informacoes_beneficio->cartao_rmc != NULL && $consulta_nb->consulta->informacoes_beneficio->cartao_rmc != "") {
                    if (!isset($beneficiop["cartao_rmc"]) || $beneficiop["cartao_rmc"] == NULL || $beneficiop["cartao_rmc"] == "") {
                        $beneficio->cartao_rmc = $consulta_nb->consulta->informacoes_beneficio->cartao_rmc;
                    }
                }
                if (isset($consulta_nb->consulta->informacoes_beneficio->valor_cartao_rmc) && $consulta_nb->consulta->informacoes_beneficio->valor_cartao_rmc != NULL && $consulta_nb->consulta->informacoes_beneficio->valor_cartao_rmc != "") {
                    if (!isset($beneficiop["valor_cartao_rmc"]) || $beneficiop["valor_cartao_rmc"] == NULL || $beneficiop["valor_cartao_rmc"] == "") {
                        $beneficio->valor_cartao_rmc = $utilitario->converteRealAmericano($consulta_nb->consulta->informacoes_beneficio->valor_cartao_rmc);
                    }
                }
                $resInserirBeneficio = $beneficio->inserir();
                if ($resInserirBeneficio == FALSE) {
                    die(json_encode(array('mensagem' => "Erro ao cadastrar beneficio causado por:" . mysqli_error($conexao->conexao), 'situacao' => false)));
                }
            }else{
                $beneficio = new BeneficioCliente($conexao);
                $beneficio->codbeneficio = $beneficiop["codbeneficio"];
                if($consulta_nb->consulta->informacoes_beneficio->meio_pagamento != NULL && $consulta_nb->consulta->informacoes_beneficio->meio_pagamento != "" && $consulta_nb->consulta->informacoes_beneficio->meio_pagamento != "NULL"){
                    $beneficio->meio = $consulta_nb->consulta->informacoes_beneficio->meio_pagamento;
                }
                if (isset($consulta_nb->consulta->informacoes_beneficio->codigo_banco) && $consulta_nb->consulta->informacoes_beneficio->codigo_banco != NULL && $consulta_nb->consulta->informacoes_beneficio->codigo_banco != "null" && $consulta_nb->consulta->informacoes_beneficio->codigo_banco != "") {
                    $sql = "select codbanco from banco where numbanco = '{$consulta_nb->consulta->informacoes_beneficio->codigo_banco}'";
                    
                    $bancop = $conexao->comandoArray($sql);
                    if (isset($bancop["codbanco"]) && $bancop["codbanco"] != NULL && $bancop["codbanco"] != "" && $bancop["codbanco"] != "0") {
                        $beneficio->codbanco = $bancop["codbanco"];
                    } else {
                        $banco = new Banco($conexao);
                        $banco->nome = $consulta_nb->consulta->detalhadamento->banco;
                        $banco->numbanco = $consulta_nb->consulta->informacoes_beneficio->codigo_banco;
                        $banco->inserir();
                        $beneficio->codbanco = mysqli_insert_id($conexao->conexao);
                    }
                }
                $beneficio->banco = $consulta_nb->consulta->informacoes_beneficio->banco;
                $beneficio->numbeneficio = $resultado2->beneficio;
                
                $resInserirBeneficio = $beneficio->atualizar();
                if ($resInserirBeneficio == FALSE) {
                    die(json_encode(array('mensagem' => "Erro ao cadastrar beneficio causado por:" . mysqli_error($conexao->conexao), 'situacao' => false)));
                }                
            }

            if ((!isset($pessoap["mae"]) || $pessoap["mae"] == NULL || $pessoap["mae"] == "") && (isset($resultado2->mae) && $resultado2->mae != NULL && $resultado2->mae != "" && $resultado2->mae != "null")) {
                $pessoa->mae = $resultado2->mae;
            }
            if ((isset($resultado2->data_nascimento) && $resultado2->data_nascimento != NULL && $resultado2->data_nascimento != "")) {
                $pessoa->dtnascimento = implode("/",array_reverse(explode("/",$resultado2->data_nascimento)));
            }
            if ((!isset($pessoap["nit"]) || $pessoap["nit"] == NULL || $pessoap["nit"] == "") && (isset($resultado2->nit) && $resultado2->nit != NULL && $resultado2->nit != "")) {
                $pessoa->nit = $resultado2->nit;
            }
            $pessoa->nome = $pessoap["nome"];
            $pessoa->codpessoa = $pessoap["codpessoa"];
            $resAtualizarPessoa = $pessoa->atualizar();
            if ($resAtualizarPessoa == FALSE) {
                die(json_encode(array('mensagem' => "Erro ao atualizar pessoa causado por:" . mysqli_error($conexao->conexao), 'situacao' => false)));
            }
            $linhaConsulta++;
        }
        die(json_encode(array('mensagem' => "Consulta realizada com sucesso!!!", 'situacao' => true)));
    } else {
        die(json_encode(array('mensagem' => "Problemas no viper: {$consulta_cpf->msg}!!!", 'situacao' => false)));
    }
} else {
    die(json_encode(array('mensagem' => "Sem CPF!!!", 'situacao' => false)));
}