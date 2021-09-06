<?php

session_start();
$numero = $_POST["numero"];

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
if (isset($_POST["codpessoa"])) {
    $numero = "";
    $resbeneficio = $conexao->comando("select numbeneficio from beneficiocliente where codpessoa = '{$_POST["codpessoa"]}' and codempresa = '{$_SESSION['codempresa']}'");
    $qtdbeneficio = $conexao->qtdResultado($resbeneficio);
    if ($qtdbeneficio > 0) {
        while ($beneficio = $conexao->resultadoArray($resbeneficio)) {
            $numero .= "{$beneficio["numbeneficio"]};";
        }
    }
}
if (isset($numero) && $numero != NULL && $numero != "") {
    $utilitario = new Utilitario();

    $beneficio = new BeneficioCliente($conexao);
    $pessoa = new Pessoa($conexao);

    if (strpos($numero, ";")) {
        $numeros = explode(";", $numero);
    } else {
        $numeros[0] = $numero;
    }
    foreach ($numeros as $key => $numero) {
        /*         * limpando NB para pesquisar */
        $numero = str_replace(".", "", $numero);
        $numero = str_replace("-", "", $numero);
        $sql = "select * from beneficiocliente where codempresa = '{$_SESSION['codempresa']}' and numbeneficio = '{$numero}'";
        $beneficiop = $conexao->comandoArray($sql);
        if(strlen($numero) == 9){
            $numero = "0". $numero;
        }
        $consulta_nb = $beneficio->consultaBenInss($numero);

        if (isset($beneficiop["codbeneficio"]) && $beneficiop["codbeneficio"] != NULL && $beneficiop["codbeneficio"] != "") {
            $conexao->comando("delete from beneficiocliente where codbeneficio <> '{$beneficiop["codbeneficio"]}' and codempresa = '{$_SESSION['codempresa']}' and numbeneficio = '' and codorgao = '3'");//tira cadastros com nb em branco
            $conexao->comando("delete from beneficiocliente where codbeneficio <> '{$beneficiop["codbeneficio"]}' and codempresa = '{$_SESSION['codempresa']}' and numbeneficio = '{$numero}'");//tira beneficios duplicados
        }
        
        if (isset($consulta_nb->consulta->beneficio) && $consulta_nb->consulta->beneficio != NULL && $consulta_nb->consulta->beneficio != "") {
            $cpf = trim($consulta_nb->consulta->informacoes_beneficio->cpf);
            $pessoap = $conexao->comandoArray("select * from pessoa where codempresa = '{$_SESSION['codempresa']}' and codpessoa = '{$beneficiop["codpessoa"]}'");


            //atualizando situação do beneficio
            if (isset($consulta_nb->consulta->informacoes_beneficio->situacao_beneficio) && $consulta_nb->consulta->informacoes_beneficio->situacao_beneficio != NULL && $consulta_nb->consulta->informacoes_beneficio->situacao_beneficio != "null" && $consulta_nb->consulta->informacoes_beneficio->situacao_beneficio != "") {
                $beneficio->situacao = $consulta_nb->consulta->informacoes_beneficio->situacao_beneficio;
            }

            //atualizando data de nascimento caso vazia
            if (isset($consulta_nb->consulta->informacoes_beneficio->data_nascimento) && $consulta_nb->consulta->informacoes_beneficio->data_nascimento != NULL && $consulta_nb->consulta->informacoes_beneficio->data_nascimento != "null" && $consulta_nb->consulta->informacoes_beneficio->data_nascimento != "") {
                if (!isset($pessoap["dtnascimento"]) || $pessoap["dtnascimento"] == NULL || $pessoap["dtnascimento"] == "" || $pessoap["dtnascimento"] == "0000-00-00") {
                    $pessoa->dtnascimento = implode("-", array_reverse(explode("/", $consulta_nb->consulta->informacoes_beneficio->data_nascimento)));
                }
            }
            //inserindo o telefone novo
            if (isset($consulta_nb->consulta->informacoes_beneficio->telefone) && $consulta_nb->consulta->informacoes_beneficio->telefone != NULL && $consulta_nb->consulta->informacoes_beneficio->telefone != "null" && $consulta_nb->consulta->informacoes_beneficio->telefone != "") {
                $conexao->comando("delete from telefone where (LENGTH(numero) < 4 or numero = '' or numero = 'null') and codpessoa = '{$pessoap["codpessoa"]}'");
                $telefone = new Telefone($conexao);
                $telefone->codempresa = $_SESSION['codempresa'];
                $telefone->codfuncionario = $_SESSION['codpessoa'];
                $telefone->codpessoa = $pessoap["codpessoa"];
                if ($telefone->identificaCelular($consulta_nb->consulta->informacoes_beneficio->telefone)) {
                    $telefone->codtipo = "3";
                } else {
                    $telefone->codtipo = "1";
                }
                $telefone->dtcadastro = date("Y-m-d H:i:s");
                $telefone->numero = $consulta_nb->consulta->informacoes_beneficio->telefone;
                $resInserirTelefone = $telefone->inserir();
                if ($resInserirTelefone == FALSE) {
                    die(json_encode(array('mensagem' => "Erro ao inserir telefone!!! Causado por:" . mysqli_error($conexao->conexao), 'situacao' => false)));
                }
            }
            //atualizando logradouro caso não tenha cadastrado um
            if (isset($consulta_nb->consulta->informacoes_beneficio->endereco) && $consulta_nb->consulta->informacoes_beneficio->endereco != NULL && $consulta_nb->consulta->informacoes_beneficio->endereco != "null" && $consulta_nb->consulta->informacoes_beneficio->endereco != "") {
                if (!isset($pessoap["logradouro"]) || $pessoap["logradouro"] == NULL || $pessoap["logradouro"] == "") {
                    $pessoa->logradouro = $consulta_nb->consulta->informacoes_beneficio->endereco;
                }
            }
            if (isset($consulta_nb->consulta->informacoes_beneficio->bairro) && $consulta_nb->consulta->informacoes_beneficio->bairro != NULL && $consulta_nb->consulta->informacoes_beneficio->bairro != "null" && $consulta_nb->consulta->informacoes_beneficio->bairro != "") {
                if (!isset($pessoap["bairro"]) || $pessoap["bairro"] == NULL || $pessoap["bairro"] == "") {
                    $pessoa->bairro = $consulta_nb->consulta->informacoes_beneficio->bairro;
                }
            }
            if (isset($consulta_nb->cidade->informacoes_beneficio->cidade) && $consulta_nb->consulta->informacoes_beneficio->cidade != NULL && $consulta_nb->consulta->informacoes_beneficio->cidade != "null" && $consulta_nb->consulta->informacoes_beneficio->cidade != "") {
                if (!isset($pessoap["cidade"]) || $pessoap["cidade"] == NULL || $pessoap["cidade"] == "") {
                    $pessoa->cidade = $consulta_nb->consulta->informacoes_beneficio->cidade;
                }
            }
            if (isset($consulta_nb->consulta->informacoes_beneficio->estado) && $consulta_nb->consulta->informacoes_beneficio->estado != NULL && $consulta_nb->consulta->informacoes_beneficio->estado != "null" && $consulta_nb->consulta->informacoes_beneficio->estado != "") {
                if (!isset($pessoap["estado"]) || $pessoap["estado"] == NULL || $pessoap["estado"] == "") {
                    $pessoa->estado = $consulta_nb->consulta->informacoes_beneficio->estado;
                }
            }
            if (isset($consulta_nb->consulta->informacoes_beneficio->cep) && $consulta_nb->consulta->informacoes_beneficio->cep != NULL && $consulta_nb->consulta->informacoes_beneficio->cep != "null" && $consulta_nb->consulta->informacoes_beneficio->cep != "") {
                if (!isset($pessoap["cep"]) || $pessoap["cep"] == NULL || $pessoap["cep"] == "") {
                    $pessoa->cep = $consulta_nb->consulta->informacoes_beneficio->cep;
                }
            }

            //verificando e atualizando meio de pagamento do beneficio
            if (isset($consulta_nb->consulta->informacoes_beneficio->meio_pagamento) && $consulta_nb->consulta->informacoes_beneficio->meio_pagamento != NULL && $consulta_nb->consulta->informacoes_beneficio->meio_pagamento != "null" && $consulta_nb->consulta->informacoes_beneficio->meio_pagamento != "") {
                if (!isset($beneficiop["meio"]) || $beneficiop["meio"] == NULL || $beneficiop["meio"] == "") {
                    $beneficio->meio = $consulta_nb->consulta->informacoes_beneficio->meio_pagamento;
                }
            }
            if (isset($consulta_nb->consulta->informacoes_beneficio->banco) && $consulta_nb->consulta->informacoes_beneficio->banco != NULL && $consulta_nb->consulta->informacoes_beneficio->banco != "null" && $consulta_nb->consulta->informacoes_beneficio->banco != "") {
                if (!isset($beneficiop["banco"]) || $beneficiop["banco"] == NULL || $beneficiop["banco"] == "") {
                    $beneficio->banco = $consulta_nb->consulta->informacoes_beneficio->banco;
                }
            }
            if (isset($consulta_nb->consulta->detalhadamento->codigo_banco) && $consulta_nb->consulta->detalhadamento->codigo_banco != NULL && $consulta_nb->consulta->detalhadamento->codigo_banco != "null" && $consulta_nb->consulta->detalhadamento->codigo_banco != "") {
                $sql = "select * from banco where numbanco = '{$consulta_nb->consulta->detalhadamento->codigo_banco}'";
                $bancop = $conexao->comandoArray($sql);
                if (isset($bancop["codbanco"]) && $bancop["codbanco"] != NULL && $bancop["codbanco"] != "" && $bancop["codbanco"] != "0") {
                    $beneficio->codbanco = $bancop["codbanco"];
                } else {
                    $banco = new Banco($conexao);
                    $banco->nome = $consulta_nb->consulta->detalhadamento->banco;
                    $banco->numbanco = $consulta_nb->consulta->detalhadamento->codigo_banco;
                    $banco->dtcadastro = date("Y-m-d H:i:s");
                    $banco->inserir();
                    $beneficio->codbanco = mysqli_insert_id($conexao->conexao);
                }
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
            if (isset($consulta_nb->consulta->informacoes_beneficio->especie) && $consulta_nb->consulta->informacoes_beneficio->especie != NULL && $consulta_nb->consulta->informacoes_beneficio->especie != "") {
                $sql = "select * from especie where numinss = '{$consulta_nb->consulta->informacoes_beneficio->especie}'";
                $especiep = $conexao->comandoArray($sql);
                if (isset($especiep["codespecie"]) && $especiep["codespecie"] != NULL && $especiep["codespecie"] != "" && $especiep["codespecie"] != "0") {
                    $beneficio->codespecie = $especiep["codespecie"];
                } else {
                    $especie = new Especie($conexao);
                    $especie->nome = $consulta_nb->consulta->informacoes_beneficio->especie_descricao;
                    $especie->numinss = $consulta_nb->consulta->informacoes_beneficio->especie;
                    $especie->dtcadastro = date("Y-m-d H:i:s");
                    $especie->inserir();
                    $beneficio->codespecie = mysqli_insert_id($conexao->conexao);
                }
                if (!isset($beneficiop["codespecie"]) || $beneficiop["codespecie"] == NULL || $beneficiop["codespecie"] == "") {
                    $beneficio->codespecie = $consulta_nb->consulta->informacoes_beneficio->codespecie;
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
            $beneficio->dtconsulta = date("Y-m-d H:i:s");
            $beneficio->codorgao = 3;
            $beneficio->codbeneficio = $beneficiop["codbeneficio"];
            $resAtualizarBeneficio = $beneficio->atualizar();
            if ($resAtualizarBeneficio == FALSE) {
                die(json_encode(array('mensagem' => "Erro ao atualizar beneficio causado por:" . mysqli_error($conexao->conexao), 'situacao' => false)));
            }
            $pessoa->nome = trim($pessoap["nome"]);
            $pessoa->codpessoa = $pessoap["codpessoa"];
            $resAtualizarPessoa = $pessoa->atualizar();
            if ($resAtualizarPessoa == FALSE) {
                die(json_encode(array('mensagem' => "Erro ao atualizar pessoa causado por:" . mysqli_error($conexao->conexao), 'situacao' => false)));
            }
            $sql = "delete from emprestimo where codpessoa = '{$pessoap["codpessoa"]}' and codempresa = '{$_SESSION['codempresa']}' and codbeneficio = '{$beneficiop["codbeneficio"]}'";

            $resExcluirEmprestimo = $conexao->comando($sql) or die("<pre>$sql</pre>");
            if ($resExcluirEmprestimo == FALSE) {
                die(json_encode(array('mensagem' => "Erro ao excluir empréstimos da pessoa causado por:" . mysqli_error($conexao->conexao), 'situacao' => false)));
            }
            foreach ($consulta_nb->consulta->consignacoes->consignacao as $key => $resultado2) {
                if ($resultado2->situacao == "ativa") {
                    $emprestimo = new Emprestimo($conexao);
                    $emprestimo->codempresa = $_SESSION['codempresa'];
                    $sql = "select * from banco where numbanco = '{$resultado2->codigo_banco}'";
                    $bancop = $conexao->comandoArray($sql);
                    if (isset($bancop["codbanco"]) && $bancop["codbanco"] != NULL && $bancop["codbanco"] != "" && $bancop["codbanco"] != 0) {
                        $emprestimo->codbanco = $bancop["codbanco"];
                    } else {
                        $banco = new Banco($conexao);
                        $banco->nome = $resultado2->banco;
                        $banco->numbanco = $resultado2->codigo_banco;
                        $banco->dtcadastro = date("Y-m-d H:i:s");
                        $banco->inserir();
                        $emprestimo->codbanco = mysqli_insert_id($conexao->conexao);
                    }
                    $emprestimo->codbeneficio = $beneficiop["codbeneficio"];
                    $emprestimo->codfuncionario = $_SESSION['codpessoa'];
                    $emprestimo->codpessoa = $pessoap["codpessoa"];
                    $emprestimo->dtcadastro = date("Y-m-d H:i:s");
                    $emprestimo->situacao = $resultado2->situacao;
                    $emprestimo->banco = $resultado2->banco;
                    $emprestimo->dtinicio = implode("-", array_reverse(explode("/", $resultado2->data_inicio)));
                    $emprestimo->dttermino = implode("-", array_reverse(explode("/", $resultado2->data_termino)));
                    $emprestimo->parcelas = $resultado2->parcelas;
                    $emprestimo->parcelas_aberto = $utilitario->converteRealAmericano($resultado2->parcelas_aberto);
                    $emprestimo->vlparcela = $utilitario->converteRealAmericano($resultado2->valor_parcela);
                    $emprestimo->saldo_aproximado = $utilitario->converteRealAmericano($resultado2->saldo_aproximado);
                    $emprestimo->quitacao = $utilitario->converteRealAmericano($resultado2->saldo_aproximado);
                    $emprestimo->prazo = $emprestimo->parcelas;
                    $resEmprestimo = $emprestimo->inserir();
                    if ($resEmprestimo == FALSE) {
                        die(json_encode(array('mensagem' => "Não conseguiu atualizar o empréstimo, erro causado por:" . mysqli_error($conexao->conexao), 'situacao' => false)));
                    }
                }
            }
            $conexao->comando("update beneficiocliente set dtconsulta = '".date('Y-m-d')."' where codbeneficio = '{$beneficiop["codbeneficio"]}'");
            die(json_encode(array('mensagem' => "Consulta realizada com sucesso!!!", 'situacao' => true)));
        } else {
           die(json_encode(array('mensagem' => "Problemas no viper - sem créditos!!!", 'situacao' => false)));
        }
    }
    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "procurar";
    $log->observacao = "Consultado beneficio INSS {$numero} - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();       
} else {
    die(json_encode(array('mensagem' => "Não pode consultar sem número de beneficio INSS...!!!", 'situacao' => false)));
}
    