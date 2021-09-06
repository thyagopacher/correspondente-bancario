<?php

session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_erros', 1);
//error_reporting(E_ALL);
//validação caso a sessão caia
if (!isset($_SESSION["codempresa"])) {
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
}


include "../model/Conexao.php";
include "../model/Pessoa.php";
include "../model/Telefone.php";
include "../model/Trabalho.php";
include "../model/BeneficioCliente.php";
include "../model/ObservacaoLigacao.php";
include "Upload.php";

$conexao = new Conexao();
$pessoa = new Pessoa($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_REQUEST;
foreach ($variables as $key => $value) {
    $pessoa->$key = $value;
}



//if ((!isset($pessoa->codstatus) || $pessoa->codstatus == NULL || $pessoa->codstatus == "") && ($pessoa->codcategoria == 6)) {
//    die(json_encode(array('mensagem' => "Não pode atualizar sem selecionar situação ao lado do agendamento!!!", 'situacao' => false)));
//}
$pessoa->dtnascimento = implode("-", array_reverse(explode("/", $pessoa->dtnascimento)));
$pessoa->dtemissaorg = implode("-", array_reverse(explode("/", $pessoa->dtemissaorg)));
if (!isset($pessoa->nome) || $pessoa->nome == NULL || $pessoa->nome == "") {
    $msg_retorno = ("Não pode atualizar pessoa sem nome!");
    $sit_retorno = false;
} else {
    if ($sit_retorno) {
        
        $pessoa->porcentagem = str_replace(",", ".", $pessoa->porcentagem);
        $pessoa->codempresa = $_SESSION['codempresa'];
        $pessoa->codpessoa = $_POST["codpessoa"];
        if (isset($_FILES['imagem']) && $_FILES['imagem'] != NULL) {
            $pessoap = $conexao->comandoArray("select imagem from pessoa where codpessoa = '{$pessoa->codpessoa}' and pessoa.codempresa = '{$_SESSION['codempresa']}'");
            if (isset($pessoap["imagem"]) && $pessoap["imagem"] != NULL && $pessoap["imagem"] != "" && file_exists("../arquivos/{$pessoap["imagem"]}")) {
                $resApagarImagemAnterior = unlink("../arquivos/{$pessoap["imagem"]}");
            }
            $upload = new Upload($_FILES['imagem']);
            if ($upload->erro == "") {
                $pessoa->imagem = $upload->nome_final;
            }
        }
        /* * se foto for atualizada da pessoa isso renovara a foto da sessão */
        if (isset($pessoa->imagem) && $pessoa->imagem != NULL && trim($pessoa->imagem) != "") {
            $_SESSION["imagem"] = $pessoa->imagem;
        }    
        
        $res = $pessoa->atualizar();
        if ($res === FALSE) {
            $msg_retorno = "Erro ao atualizar pessoa! Causado por:" . mysqli_error($conexao->conexao);
            $sit_retorno = false;
        } else {

            if (isset($_POST["observacao"]) && $_POST["observacao"] != NULL && $_POST["observacao"] != "") {
                include("../model/ObservacaoCliente.php");
                $observacao = new ObservacaoCliente($conexao);
                $observacao->codempresa = $_SESSION['codempresa'];
                $observacao->codfuncionario = $_SESSION['codpessoa'];
                $observacao->codpessoa = $_POST["codpessoa"];
                $observacao->dtcadastro = date("Y-m-d H:i:s");
                $observacao->texto = $_POST["observacao"];
                $resInserirObservacao = $observacao->inserir();
            }
            if (isset($_POST["telefone"]) && $_POST["telefone"] != NULL && count($_POST["telefone"]) > 0) {
                $resExcluirTelefone = $conexao->comando("delete from telefone where codpessoa = '{$_POST["codpessoa"]}' and codempresa = '{$_SESSION['codempresa']}'");
                if ($resExcluirTelefone === FALSE) {
                    mail("thyago.pacher@gmail.com", "Erro ao atualizar telefones pessoas", "Erro causado por:" . mysqli_error($conexao->conexao));
                }
                $codempresa = $_SESSION['codempresa'];
                foreach ($_POST["telefone"] as $key => $telefone2) {
                    if (!isset($telefone2) || $telefone2 == NULL || $telefone2 == "") {
                        continue;
                    }
                    $telefone = new Telefone($conexao);
                    $telefone->codpessoa = $_POST["codpessoa"];
                    $telefone->codtipo = $_POST["tipotelefone"][$key];
                    $telefone->operadora = $_POST["operadora"][$key];
                    $telefone->dtcadastro = date("Y-m-d H:i:s");
                    $telefone->numero = $telefone2;
                    $resInserir = $telefone->inserir();
                    if ($resInserir == FALSE) {
                        $msg_retorno = "Erro ao inserir telefone do cliente causado por:" . mysqli_error($conexao->conexao);
                        $sit_retorno = false;
                        break;
                    }
                }
            }
            if (isset($_POST["orgaopagador"]) && $_POST["orgaopagador"] != NULL && $_POST["orgaopagador"] != "" && count($_POST["orgaopagador"]) > 0) {

                foreach ($_POST["orgaopagador"] as $key => $orgaopagador) {
                    if (!isset($orgaopagador) || $orgaopagador == NULL || $orgaopagador == "") {
                        continue;
                    }
                    $beneficiop = $conexao->comandoArray("select codbeneficio from beneficiocliente where codpessoa = '{$_POST["codpessoa"]}' and codempresa = '{$_SESSION['codempresa']}' and codbeneficio in(select codbeneficio from emprestimo where codpessoa = '{$_POST["codpessoa"]}')");
                    if (isset($beneficiop) && $beneficiop["codbeneficio"] != NULL && $beneficiop["codbeneficio"] != "") {
                        continue;
                    } else {
                        $resExcluir = $conexao->comando("delete from beneficiocliente where codpessoa = '{$_POST["codpessoa"]}' and codempresa = '{$_SESSION['codempresa']}' and numbeneficio = '{$_POST["numbeneficio"][$key]}'");
                        if ($resExcluir === FALSE) {
                            mail("thyago.pacher@gmail.com", "Erro ao atualizar beneficio cliente pessoas", "Erro causado por:" . mysqli_error($conexao->conexao));
                        }
                    }
                    $beneficio = new BeneficioCliente($conexao);
                    $beneficio->codorgao = $orgaopagador;
                    $beneficio->codempresa = $_SESSION['codempresa'];
                    $beneficio->codpessoa = $_POST["codpessoa"];
                    if (isset($_POST["especie"][$key]) && $_POST["especie"][$key] != NULL && $_POST["especie"][$key] != "") {
                        $beneficio->codespecie = $_POST["especie"][$key];
                    }
                    if (isset($_POST["matricula"][$key]) && $_POST["matricula"][$key] != NULL && $_POST["matricula"][$key] != "") {
                        $beneficio->matricula = $_POST["matricula"][$key];
                    }
                    if (isset($_POST["numbeneficio"][$key]) && $_POST["numbeneficio"][$key] != NULL && $_POST["numbeneficio"][$key] != "") {
                        $beneficio->numbeneficio = $_POST["numbeneficio"][$key];
                    }
                    if (isset($_POST["salariobase"][$key]) && $_POST["salariobase"][$key] != NULL && $_POST["salariobase"][$key] != "") {
                        $beneficio->salariobase = str_replace(",", ".", $_POST["salariobase"][$key]);
                    }
                    $beneficio->dtcadastro = date("Y-m-d H:i:s");
                    $beneficio->codfuncionario = $_SESSION['codpessoa'];
                    $resInserir = $beneficio->inserir();
                    if ($resInserir == FALSE) {
                        $msg_retorno = "Erro ao inserir beneficio do cliente causado por:" . mysqli_error($conexao->conexao);
                        $sit_retorno = false;
                        break;
                    }
                }
            }
            if (isset($_POST["localtrabalho"]) && $_POST["localtrabalho"] != NULL) {
                $resExcluir = $conexao->comando("delete from trabalho where codpessoa = '{$_POST["codpessoa"]}' and codempresa = '{$_SESSION['codempresa']}' and codtipo = 1");
                if ($resExcluir === FALSE) {
                    mail("thyago.pacher@gmail.com", "Erro ao atualizar trabalho(servidor) cliente pessoas", "Erro causado por:" . mysqli_error($conexao->conexao));
                }
                foreach ($_POST["localtrabalho"] as $key => $localtrabalho) {
                    if (!isset($localtrabalho) || $localtrabalho == NULL || $localtrabalho == "") {
                        continue;
                    }
                    $trabalho = new Trabalho($conexao);
                    $trabalho->codempresa = $_SESSION['codempresa'];
                    $trabalho->codfuncionario = $_SESSION['codpessoa'];
                    $trabalho->codpessoa = $_POST["codpessoa"];
                    $trabalho->dtcadastro = date("Y-m-d H:i:s");
                    $trabalho->local = $localtrabalho;
                    $trabalho->coddepartamento = $_POST["departamento"][$key];
                    $trabalho->codcargo = $_POST["codcargo"][$key];
                    $trabalho->matricula = $_POST["matriculaservidor"][$key];
                    $trabalho->salariobase = str_replace(",", ".", $_POST["salariobaseservidor"][$key]);
                    $trabalho->codtipo = 1;
                    $resInserirTrabalho = $trabalho->inserir();
                    if ($resInserirTrabalho == FALSE) {
                        $msg_retorno = "Erro ao inserir serviço do cliente causado por:" . mysqli_error($conexao->conexao);
                        $sit_retorno = false;
                        break;
                    }
                }
            }
            if (isset($_POST["empresa"]) && $_POST["empresa"] != NULL) {
                $resExcluir = $conexao->comando("delete from trabalho where codpessoa = '{$_POST["codpessoa"]}' and codempresa = '{$_SESSION['codempresa']}' and codtipo = 2");
                if ($resExcluir === FALSE) {
                    mail("thyago.pacher@gmail.com", "Erro ao atualizar trabalho(assalariado) cliente pessoas", "Erro causado por:" . mysqli_error($conexao->conexao));
                }
                foreach ($_POST["empresa"] as $key => $empresa) {
                    if (!isset($localtrabalho) || $localtrabalho == NULL || $localtrabalho == "") {
                        continue;
                    }
                    $trabalho = new Trabalho($conexao);
                    $trabalho->codempresa = $_SESSION['codempresa'];
                    $trabalho->codfuncionario = $_SESSION['codpessoa'];
                    $trabalho->codpessoa = $_POST["codpessoa"];
                    $trabalho->dtcadastro = date("Y-m-d H:i:s");
                    $trabalho->empresa = $empresa;
                    $trabalho->local = $_POST["endereco"][$key];
                    $trabalho->coddepartamento = 3;
                    $trabalho->codcargo = $_POST["codcargoAssalariado"][$key];
                    $trabalho->matricula = "";
                    $trabalho->salariobase = str_replace(",", ".", $_POST["salariobasetrabalho"][$key]);
                    $trabalho->codtipo = 2;
                    $resInserirTrabalho = $trabalho->inserir();
                    if ($resInserirTrabalho == FALSE) {
                        $msg_retorno = "Erro ao inserir serviço do cliente causado por:" . mysqli_error($conexao->conexao);
                        $sit_retorno = false;
                        break;
                    }
                }
            }
        }
    }
}
if ($sit_retorno) {
    $msg_retorno = "Sucesso em atualizar pessoa!";

    include "../model/Log.php";
    $log = new Log($conexao);
    $log->acao = "atualizar";
    
    $log->codpagina = 4;
    $log->codpessoa = $_SESSION['codpessoa'];
    
    $log->hora = date('H:i:s');
    $log->observacao = "Pessoa atualizada: {$pessoa->nome} - Email: {$pessoa->email} - Status: {$pessoa->status} - Sexo: {$pessoa->sexo}";
    $log->inserir();

    include("../model/Atendimento.php");
    $atendimento = new Atendimento($conexao);
    $atendimento->codempresa = $_SESSION['codempresa'];
    $atendimento->codfuncionario = $_SESSION['codpessoa'];
    $atendimento->dtcadastro = date("Y-m-d H:i:s");
    $atendimento->codpessoa = $_POST["codpessoa"];
    $resInserirAtendimento = $atendimento->inserir();

    if ($resInserirAtendimento == FALSE) {
        mail("thyago.pacher@gmail.com", "Erro ao inserir atendimento efetuado", "Erro causado por em AtualizarPessoa.php:" . mysqli_error($conexao->conexao));
    }
}
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno, $imagem => $pessoa->imagem));
