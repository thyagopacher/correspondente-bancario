<?php

session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   

function __autoload($class_name) {
    if(file_exists("../model/".$class_name . '.php')){
        include "../model/".$class_name . '.php';
    }elseif(file_exists("../visao/".$class_name . '.php')){
        include "../visao/".$class_name . '.php';
    }elseif(file_exists("./".$class_name . '.php')){
        include "./".$class_name . '.php';
    }
}
    
$conexao = new Conexao();
$pessoa = new Pessoa($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_REQUEST;
foreach ($variables as $key => $value) {
    $pessoa->$key = $value;
}

if($pessoa->codcategoria == 1){
    $pessoa->codstatus = 14;
}
if(!isset($pessoa->codstatus) || $pessoa->codstatus == NULL || $pessoa->codstatus == ""){
    die(json_encode(array('mensagem' => "Não pode inserir sem selecionar situação!!!", 'situacao' => false)));
}

if (!isset($pessoa->nome) || $pessoa->nome == NULL || $pessoa->nome == "") {
    $msg_retorno = ("Não pode cadastrar pessoa sem nome!");
    $sit_retorno = false;
} else {
    if ($sit_retorno) {
        $pessoa->codempresa = $_SESSION['codempresa'];
        $pessoa->codpessoa = $_POST["codpessoa"];
        $pessoa->dtemissaorg = implode("-",array_reverse(explode("/",$pessoa->dtemissaorg)));
        $pessoa->dtnascimento = implode("-",array_reverse(explode("/",$pessoa->dtnascimento)));
        if(!isset($pessoa->codcategoria) || $pessoa->codcategoria == NULL || $pessoa->codcategoria == ""){
            $pessoa->codcategoria = 1;
        }
          
        $pessoap = $conexao->comandoArray("select codpessoa from pessoa where cpf = '{$pessoa->cpf}' and codempresa = '{$_SESSION['codempresa']}'");
        if(isset($pessoap["codpessoa"]) && $pessoap["codpessoa"] != NULL && $pessoap["codpessoa"] != ""){
            $codigo_pessoa = $pessoap["codpessoa"];            
            $pessoa->codpessoa = $codigo_pessoa;
            $res = $pessoa->atualizar();
        }else{
            
            $res = $pessoa->inserir();
            $codigo_pessoa = mysqli_insert_id($conexao->conexao);
        }
        if ($res === FALSE) {
            $msg_retorno = "Erro ao cadastrar pessoa! Causado por:" . mysqli_error($conexao->conexao);
            $sit_retorno = false;
        }else{            
            if(isset($_POST["dtagenda"]) && $_POST["dtagenda"] != NULL && $_POST["dtagenda"] != ""){
                include "../model/Agenda.php";
                $agenda = new Agenda($conexao);
                $agenda->codfuncionario = $_SESSION['codpessoa'];
                $agenda->dtcadastro     = date("Y-m-d H:i:s");
                $agenda->codempresa     = $_SESSION['codempresa'];    
                $agenda->codpessoa      = $codigo_pessoa;
                $agenda->dtagenda       = implode("-",array_reverse(explode("/",$agenda->dtagenda)));
                $sql = "select codagenda,codfuncionario  from agenda where codempresa = '{$agenda->codempresa}' and atendido = 'n' and codfuncionario = '{$agenda->codfuncionario}' and codpessoa = '{$agenda->codpessoa}'";    
                $agendap = $conexao->comandoArray($sql);
                if(isset($agendap) && isset($agendap["codagenda"])){
                    $sql = "update agenda set atendido = 's' where codfuncionario = '{$agendap["codfuncionario"]}' and atendido = 'n' and codpessoa = '{$agenda->codpessoa}'";
                    $conexao->comando($sql);
                }
                $agenda->observacao = $_POST["observacao"];    
                $resInserirAgenda = $agenda->inserir();  
                if($resInserirAgenda !== FALSE){
                    $msg_retorno .= "\nAgenda salva com sucesso!!!";
                    include("../model/Atendimento.php");
                    $atendimento = new Atendimento($conexao);
                    $atendimento->codempresa     = $_SESSION['codempresa'];
                    $atendimento->codfuncionario = $_SESSION['codpessoa'];
                    $atendimento->dtcadastro     = date("Y-m-d H:i:s");
                    $atendimento->codpessoa      = $codigo_pessoa;
                    $resInserirAtendimento       = $atendimento->inserir();
                    if($resInserirAtendimento == FALSE){
                        mail("thyago.pacher@gmail.com", "Erro ao inserir atendimento efetuado", "Erro causado por:". mysqli_error($conexao->conexao));
                    }                    
                }
            }
            if(isset($_POST["observacao"]) && $_POST["observacao"] != NULL && $_POST["observacao"] != ""){
                include("../model/ObservacaoCliente.php");
                $observacao = new ObservacaoCliente($conexao);
                $observacao->codempresa = $_SESSION['codempresa'];
                $observacao->codfuncionario = $_SESSION['codpessoa'];
                $observacao->codpessoa = $codigo_pessoa;
                $observacao->dtcadastro = date("Y-m-d H:i:s");
                $observacao->texto = $_POST["observacao"];
                $resInserirObservacao = $observacao->inserir();
            }
            if(isset($_POST["telefone"]) && $_POST["telefone"] != NULL && count($_POST["telefone"]) > 1){
                foreach ($_POST["telefone"] as $key => $telefone2) {
                    if(!isset($telefone2) || $telefone2 == NULL || $telefone2 == ""){
                        continue;
                    }
                    if(!isset($_POST["tipotelefone"][$key]) || $_POST["tipotelefone"][$key] == NULL || $_POST["tipotelefone"][$key] == ""){
                        die(json_encode(array('mensagem' => "Escolha um tipo para o telefone!!!", 'situacao' => false)));
                    }
                    $telefone = new Telefone($conexao);
                    $telefone->codpessoa = $codigo_pessoa;
                    $telefone->operadora = $_POST["operadora"][$key];
                    $telefone->codtipo = $_POST["tipotelefone"][$key];
                    $telefone->numero = $telefone2;
                    $resInserir = $telefone->inserir();
                    if($resInserir == FALSE){
                        $msg_retorno = "Erro ao inserir telefone do cliente causado por:". mysqli_error($conexao->conexao);
                        $sit_retorno = false;
                        break;
                    }                
                }  
            }
            if(isset($_POST["orgaopagador"]) && $_POST["orgaopagador"] != NULL && $_POST["orgaopagador"] != "" && count($_POST["orgaopagador"]) > 0){
                foreach ($_POST["orgaopagador"] as $key => $orgaopagador) {
                    if(!isset($orgaopagador) || $orgaopagador == NULL || $orgaopagador == ""){
                        continue;
                    }
                    $beneficio               = new BeneficioCliente($conexao);
                    $beneficio->codorgao     = $orgaopagador;
                    $beneficio->codempresa   = $_SESSION['codempresa'];
                    $beneficio->codpessoa    = $codigo_pessoa;
                    if(isset($_POST["especie"][$key]) && $_POST["especie"][$key] != NULL && $_POST["especie"][$key] != ""){
                        $beneficio->codespecie   = $_POST["especie"][$key];
                    }
                    if(isset($_POST["matricula"][$key]) && $_POST["matricula"][$key] != NULL && $_POST["matricula"][$key] != ""){
                        $beneficio->matricula    = $_POST["matricula"][$key];
                    }
                    if(isset($_POST["numbeneficio"][$key]) && $_POST["numbeneficio"][$key] != NULL && $_POST["numbeneficio"][$key] != ""){
                        $beneficio->numbeneficio = $_POST["numbeneficio"][$key];
                    }
                    $beneficio->salariobase  = str_replace(",", ".", $_POST["salariobase"][$key]);
                    $beneficio->dtcadastro   = date("Y-m-d H:i:s");
                    $beneficio->codfuncionario = $_SESSION['codpessoa'];
                    $resInserir = $beneficio->inserir();
                    if($resInserir == FALSE){
                        $msg_retorno = "Erro ao inserir beneficio do cliente causado por:". mysqli_error($conexao->conexao);
                        $sit_retorno = false;
                        break;
                    }
                }
            }
            if(isset($_POST["localtrabalho"]) && $_POST["localtrabalho"] != NULL){
                foreach ($_POST["localtrabalho"] as $key => $localtrabalho) {
                    if(!isset($localtrabalho) || $localtrabalho == NULL || $localtrabalho == ""){
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
                    $resInserirTrabalho = $trabalho->inserir();
                    if($resInserirTrabalho == FALSE){
                        $msg_retorno = "Erro ao inserir serviço do cliente causado por:". mysqli_error($conexao->conexao);
                        $sit_retorno = false;
                        break;                        
                    }
                }
            }
        } 
    }
}
if($sit_retorno){
    $msg_retorno = "Cliente inserido com sucesso";
}
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
