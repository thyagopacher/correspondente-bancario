<?php
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   

include "../model/Conexao.php";
include "../model/Registro.php";

$conexao = new Conexao();
$registro = new Registro($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_REQUEST;
foreach ($variables as $key => $value) {
    $registro->$key = $value;
}
if(!isset($_POST["procedimento"]) || $_POST["procedimento"] == NULL){
    die(json_encode(array('mensagem' => "Não pode inserir registro sem selecionar pelo menos um procedimento", 'situacao' => false)));
}

if (!isset($registro->paciente) || $registro->paciente == NULL || $registro->paciente == "") {
    $msg_retorno = ("Não pode inserir registro sem paciente!");
    $sit_retorno = false;
} else {
    if ($sit_retorno) {
        if(strpos($registro->valor, ",")){
            $registro->valor = str_replace(",", ".", $registro->valor);
        }
        $registro->data = implode("-",array_reverse(explode("/",$registro->data)));
        $registro->codregistro = $_POST["codregistro"];
        $res = $registro->inserir($registro);
        $codigo_registro = mysqli_insert_id($conexao->conexao);
        if ($res === FALSE) {
            $msg_retorno = "Erro ao inserir registro! Causado por:" . mysqli_error($conexao->conexao);
            $sit_retorno = false;
        }else{
            $linha = 0;
            if(isset($_POST["codigo_procedimento"]) && $_POST["codigo_procedimento"] != NULL){
                foreach ($_POST["codigo_procedimento"] as $key => $value) {
                    if(strpos($_POST["valorHonorario"][$key], ",")){
                        $_POST["valorHonorario"][$key] = str_replace(",", ".", $_POST["valorHonorario"][$key]);
                    }
                    $sql = "INSERT INTO `procedimentoregistro`(`codregistro`, `codigo`, `dtcadastro`, valor, incisao, bilateral, porte, tipo)
                    VALUES ('{$codigo_registro}', '{$value}', '".date("Y-m-d H:i:s")."', '{$_POST["valorHonorario"][$key]}', 
                    '{$_POST["incisao"][$key]}', '{$_POST["bilateral"][$key]}', '{$_POST["porte"][$key]}', '{$_POST["tipo"][$key]}')";
                    $res = $conexao->comando($sql);
                    if($res == FALSE){
                        $msg_retorno = "Erro ao inserir demais procedimentos para o registro de cirurgia!";
                        $sit_retorno = false;
                        break;
                    }
                }
            }
        } 
    }
}

echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
