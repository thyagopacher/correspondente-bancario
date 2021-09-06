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
    $conta   = new Conta($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    // Tamanho máximo do arquivo (em Bytes)
    $_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
    $_UP['extensoes'] = array('jpg', 'png', 'gif', 'pdf');// Array com as extensões permitidas

    // Array com os tipos de erros de upload do PHP
    $_UP['erros'][0] = 'Não houve erro';
    $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
    $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['erros'][4] = 'Não foi feito o upload do arquivo';    
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $conta->$key = $value;
    }  
      
    if(isset($conta->codambiente) && $conta->codambiente != NULL && $conta->codambiente != ""){
        $sql = "select nome from ambienterateio where codambiente = '{$conta->codambiente}' and codempresa = '{$_SESSION['codempresa']}'";
        $ambienterateio = $conexao->comandoArray($sql);
        $conta->nome = "Rateio - {$ambienterateio["nome"]}";
    }
    if(!isset($conta->nome) || $conta->nome == NULL || $conta->nome == ""){
        $msg_retorno =  "Não pode inserir sem nome !";
    }else{
        if(isset($_POST["codfilial"]) && $_POST["codfilial"] != NULL && $_POST["codfilial"] != ""){
            $conta->codempresa = $_POST["codfilial"];
        }else{
            $conta->codempresa =  $_SESSION['codempresa'];
        }
        $conta->data = implode("-",array_reverse(explode("/",$conta->data)));
        $conta->dtpagamento = implode("-",array_reverse(explode("/",$conta->dtpagamento)));        
        $conta->codfuncionario  = $_SESSION['codpessoa'];
        $conta->valor = Utilitario::converteRealAmericano($conta->valor);
        if(!isset($conta->codtipo) || $conta->codtipo == NULL || $conta->codtipo == ""){
            $msg_retorno =  "Por favor defina um tipo para a conta!";
            $sit_retorno = false;   
            die(json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno)));
        }
        $res = $conta->inserir($conta);
        $codigo_conta = mysqli_insert_id($conexao->conexao);
        if($res === FALSE){
            $msg_retorno =  "Erro ao inserir conta! Causado por:". mysqli_error($conexao->conexao);
            $sit_retorno = false;
        }else{
            $msg_retorno =  "Conta inserida com sucesso!";
            if (isset($_FILES["arquivo"]) && count($_FILES["arquivo"]) > 1) {
                /*             * adicionando várias imagens a um mesmo produto* */
                $img = $_FILES['arquivo'];
                if ($img != NULL) {
                    $contImg = count($img['name']);
                    for ($i = 0; $i < $contImg; $i++) {
                        $tmp  = $img['tmp_name'][$i];
                        $name = $img['name'][$i];
                        $type = $img['type'][$i];
                        $extensao1 = explode('.', $name);
                        $extensao  = strtolower($extensao1[1]);
                        // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
                        if ($img['error'][$i] != 0) {
                            die(json_encode(array('mensagem' => "Não foi possível fazer o upload, erro:" . $_UP['erros'][$img['error'][$i]], 'situacao' => false)));
                        }    

                        if ($tmp != NULL && $name != NULL && $type != NULL) {
                            if (!empty($name)) {
                                $nomeArquivo = md5(uniqid(rand(), true)) .".".$extensao;
                                if (move_uploaded_file($tmp, '../arquivos/'.$nomeArquivo)) {
                                    $arquivo = new ArquivoConta($conexao);
                                    $arquivo->codconta       = $codigo_conta;
                                    $arquivo->codempresa     = $_SESSION['codempresa'];
                                    $arquivo->codfuncionario = $_SESSION['codpessoa'];
                                    $arquivo->dtcadastro     = date("Y-m-d H:i:s");
                                    $arquivo->link           = $nomeArquivo;
                                    $arquivo->nome           = $conta->nome ."-".date('d/m/Y');
                                    $resInserirArquivo       = $arquivo->inserir();
                                    if($resInserirArquivo == FALSE){
                                        $msg_retorno .= "\nFalha ao enviar arquivo para upload. Causado por:". mysqli_error($conexao->conexao);
                                        break;
                                    }
                                } else {
                                    break;
                                }                            
                            }
                        } else {
                            break;
                        }
                    }
                }
            }            
        }
    }    
    if(isset($upload->erro) && $upload->erro != NULL && $upload->erro != ""){
        $msg_retorno .= " Problemas com o envio do arquivo: ". $upload->erro;
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));