<?php

session_start();

function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

function soNumero($str) {
    return preg_replace("/[^0-9]/", "", $str);
}

$conexao = new Conexao();

$conexao->comando('delete from beneficiocliente where codpessoa = ' . $_POST["codpessoa"] . ' and numbeneficio = ""');

$beneficio = new BeneficioCliente($conexao);
$pessoa = new Pessoa($conexao);
$sql = 'select cep, bairro, cidade, estado, codpessoa, logradouro, cpf, dtnascimento from pessoa where codempresa = ' . $_SESSION["codempresa"] . ' and codpessoa = "' . $_POST["codpessoa"] . '"';
$pessoap = $conexao->comandoArray($sql);

$num = $beneficio->consultaCpfInss3($pessoap["cpf"]);

foreach ($num->result as $key => $dadosBeneficio) {
    $dadosBeneficio = (array) $dadosBeneficio;

    $consulta_nb = (array) $beneficio->consultaBenInss3($dadosBeneficio['nb'])[0];

    /*     * atualizando informações pessoais */
    if (!isset($pessoap["cep"]) || $pessoap["cep"] == NULL || $pessoap["cep"] == "" || !isset($pessoap["bairro"]) || $pessoap["bairro"] == NULL || $pessoap["bairro"] == "" || !isset($pessoap["cidade"]) || $pessoap["cidade"] == NULL || $pessoap["cidade"] == "" || !isset($pessoap["uf"]) || $pessoap["uf"] == NULL || $pessoap["uf"] == "" || !isset($pessoap["cpf"]) || $pessoap["cpf"] == NULL || $pessoap["cpf"] == "" || !isset($pessoap["logradouro"]) || $pessoap["logradouro"] == NULL || $pessoap["logradouro"] == "") {
        $pessoa = new Pessoa($conexao);
        $pessoa->codempresa = $_SESSION["codempresa"];
        $pessoa->codpessoa = $pessoap["codpessoa"];
        $data_nascimento = $consulta_nb['NASCTO'];
        $pessoa->dtnascimento = date("Y-m-d", strtotime($data_nascimento));
        if (!isset($pessoap["cep"]) || $pessoap["cep"] == NULL || $pessoap["cep"] == "") {
            $pessoa->cep = $consulta_nb['CEP'];
        }
        if (!isset($pessoap["bairro"]) || $pessoap["bairro"] == NULL || $pessoap["bairro"] == "") {
            $pessoa->bairro = $consulta_nb['BAIRRO'];
        }
        if (!isset($pessoap["cidade"]) || $pessoap["cidade"] == NULL || $pessoap["cidade"] == "") {
            $pessoa->cidade = $consulta_nb['CIDADE'];
        }
        if (!isset($pessoap["uf"]) || $pessoap["uf"] == NULL || $pessoap["uf"] == "") {
            $pessoa->estado = $consulta_nb['UF'];
        }
        if (!isset($pessoap["logradouro"]) || $pessoap["logradouro"] == NULL || $pessoap["logradouro"] == "") {
            $pessoa->logradouro = $consulta_nb['ENDERECO'];
        }
        if (!isset($pessoap["cpf"]) || $pessoap["cpf"] == NULL || $pessoap["cpf"] == "") {
            $pessoa->cpf = $consulta_nb['CPF'];
        }
        $resAtualizarPessoa = $pessoa->atualizar();
        if ($resAtualizarPessoa == FALSE) {
            die(json_encode(array('mensagem' => 'Erro ao atualizar pessoa causado por:' . mysqli_error($conexao->conexao), 'situacao' => false)));
        }
    }

    $sql = 'select codbanco, codbeneficio, contacorrente from beneficiocliente as beneficio where codempresa = ' . $_SESSION["codempresa"] . ' and codpessoa = ' . $pessoap["codpessoa"] . ' and numbeneficio = "' . $dadosBeneficio['nb'] . '"';
    $beneficiop = $conexao->comandoArray($sql);
    if ((!isset($beneficiop["codbanco"]) || $beneficiop["codbanco"] == NULL || $beneficiop["codbanco"] == "0" || !isset($beneficiop["salariobase"]) || $beneficiop["salariobase"] == NULL || $beneficiop["salariobase"] == "" || !isset($beneficiop["margem"]) || $beneficiop["margem"] == NULL || $beneficiop["margem"] == "" || !isset($beneficiop["codespecie"]) || $beneficiop["codespecie"] == NULL || $beneficiop["codespecie"] == "" || !isset($beneficiop["contacorrente"]) || $beneficiop["contacorrente"] == NULL || $beneficiop["contacorrente"] == "") && isset($beneficiop["codbeneficio"])) {
        $beneficio = new BeneficioCliente($conexao);
        $beneficio->codbeneficio = $beneficiop["codbeneficio"];
        $beneficio->meio = '';
        $especiep = $conexao->comandoArray('select codespecie from especie where numinss = "' . soNumero($consulta_nb['ESP']) . '"');
        if (isset($especiep["codespecie"]) && $especiep["codespecie"] != NULL && $especiep["codespecie"] != "") {
            $beneficio->codespecie = $especiep["codespecie"];
        } else {
            $especie = new Especie($conexao);
            $especie->dtcadastro = date("Y-m-d H:i:s");
            $especie->numinss = soNumero($consulta_nb['ESP']);
            $especie->nome = str_replace($especie->numinss, '', $consulta_nb['ESP']);
            $resInserirEspecie = $especie->inserir();
            if ($resInserirEspecie == FALSE) {
                die(json_encode(array('mensagem' => 'Erro ao inserir especie novo causado por:' . mysqli_error($conexao->conexao), 'situacao' => false)));
            } else {
                $codigo_especie = mysqli_insert_id($conexao->conexao);
            }
            $beneficio->codespecie = $codigo_especie;
        }
        if (!isset($beneficiop["codbanco"]) || $beneficiop["codbanco"] == NULL || $beneficiop["codbanco"] == "0") {
            $bancop = $conexao->comandoArray('select codbanco from banco where numbanco = "' . soNumero($consulta_nb['BANCO_PAGTO']) . '"');
            if (isset($bancop["codbanco"]) && $bancop["codbanco"] != NULL && $bancop["codbanco"] != "") {
                $beneficio->codbanco = $bancop["codbanco"];
            } else {
                $banco = new Banco($conexao);
                $banco->codfuncionario = 6;
                $banco->dtcadastro = date("Y-m-d H:i:s");
                $banco->numbanco = soNumero($consulta_nb['BANCO_PAGTO']);
                $banco->nome = str_replace($banco->numbanco, '', $consulta_nb['BANCO_PAGTO']);
                $resInserirBanco = $banco->inserir();
                if ($resInserirBanco == FALSE) {
                    die(json_encode(array('mensagem' => 'Erro ao inserir banco novo causado por:' . mysqli_error($conexao->conexao), 'situacao' => false)));
                } else {
                    $codigo_banco = mysqli_insert_id($conexao->conexao);
                }
                $beneficio->codbanco = $codigo_banco;
            }
        }
        if (!isset($beneficiop["salariobase"]) || $beneficiop["salariobase"] == NULL || $beneficiop["salariobase"] == "") {
            $beneficio->salariobase = $consulta_nb['MR'];
        }
        if (!isset($beneficiop["margem"]) || $beneficiop["margem"] == NULL || $beneficiop["margem"] == "") {
            $beneficio->margem = $consulta_nb['MARGEM'];
        }
        $beneficio->agencia = $consulta_nb['AGENCIA_PAGTO'];
        $beneficio->contacorrente = $consulta_nb['CONTA_PAGTO'];
        $resInserirBeneficio = $beneficio->atualizar();
        if ($resInserirBeneficio == FALSE) {
            die(json_encode(array('mensagem' => 'Erro ao atualizar beneficio causado por:' . mysqli_error($conexao->conexao), 'situacao' => false)));
        }
    } elseif (!isset($beneficiop["codbeneficio"]) || $beneficiop["codbeneficio"] == NULL || $beneficiop["codbeneficio"] == "") {/*     * cadastrando o beneficio pois não encontrou o mesmo na consulta */
        $beneficio = new BeneficioCliente($conexao);

        $beneficio->agencia = $consulta_nb['AGENCIA_PAGTO'];
        $bancop = $conexao->comandoArray('select codbanco from banco where numbanco = "' . soNumero($consulta_nb['BANCO_PAGTO']) . '"');
        if (isset($bancop["codbanco"]) && $bancop["codbanco"] != NULL && $bancop["codbanco"] != "") {
            $beneficio->codbanco = $bancop["codbanco"];
        } else {
            $banco = new Banco($conexao);
            $banco->codfuncionario = 6;
            $banco->dtcadastro = date("Y-m-d H:i:s");
            $banco->numbanco = soNumero($consulta_nb['BANCO_PAGTO']);
            $banco->nome = str_replace($banco->numbanco, '', $consulta_nb['BANCO_PAGTO']);
            $resInserirBanco = $banco->inserir();
            if ($resInserirBanco == FALSE) {
                die(json_encode(array('mensagem' => 'Erro ao inserir banco novo causado por:' . mysqli_error($conexao->conexao), 'situacao' => false)));
            } else {
                $beneficio->codbanco = mysqli_insert_id($conexao->conexao);
            }
        }
        $beneficio->codempresa = $_SESSION["codempresa"];
        $especiep = $conexao->comandoArray('select codespecie from especie where numinss = "' . soNumero($consulta_nb['ESP']) . '"');
        if (isset($especiep["codespecie"]) && $especiep["codespecie"] != NULL && $especiep["codespecie"] != "") {
            $beneficio->codespecie = $especiep["codespecie"];
        } else {
            $especie = new Especie($conexao);
            $especie->dtcadastro = date("Y-m-d H:i:s");
            $especie->numinss = soNumero($consulta_nb['ESP']);
            $especie->nome = str_replace($especie->numinss, '', $consulta_nb['ESP']);
            $resInserirEspecie = $especie->inserir();
            if ($resInserirEspecie == FALSE) {
                die(json_encode(array('mensagem' => 'Erro ao inserir especie novo causado por:' . mysqli_error($conexao->conexao), 'situacao' => false)));
            } else {
                $codigo_especie = mysqli_insert_id($conexao->conexao);
            }
            $beneficio->codespecie = $codigo_especie;
        }
        $beneficio->codfuncionario = $_SESSION["codpessoa"];
        $beneficio->codorgao = 5;
        $beneficio->codpessoa = $pessoap["codpessoa"];
        $beneficio->contacorrente = $consulta_nb['CONTA_PAGTO'];
        $beneficio->dtcadastro = date("Y-m-d H:i:s");
        $beneficio->margem = $consulta_nb['MARGEM'];
        $beneficio->matricula = $dadosBeneficio['nb'];
        $beneficio->meio = '';
        $beneficio->numbeneficio = $dadosBeneficio['nb'];
        $beneficio->salariobase = $consulta_nb['MR'];
        $beneficio->situacao = 'ativo';
        $resInserirBeneficio = $beneficio->inserir();
        if ($resInserirBeneficio == FALSE) {
            die(json_encode(array('mensagem' => 'Erro ao inserir beneficio causado por:' . mysqli_error($conexao->conexao), 'situacao' => false)));
        }
    }
}
die(json_encode(array('mensagem' => 'Importação realizada com sucesso!!!', 'situacao' => true, 'codpessoa' => $pessoap["codpessoa"])));

