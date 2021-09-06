<?php
    
    function __autoload($class_name) {
        if(file_exists("../model/".$class_name . '.php')){
            include "../model/".$class_name . '.php';
        }elseif(file_exists("../visao/".$class_name . '.php')){
            include "../visao/".$class_name . '.php';
        }elseif(file_exists("./".$class_name . '.php')){
            include "./".$class_name . '.php';
        }
    }
    
    
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    
    $conexao = new Conexao();
    $pessoa  = new Pessoa($conexao);
    $pessoa->codempresa = $_SESSION['codempresa'];
    $pessoa->codpessoa  = $_POST["codpessoa"];
    $inputValue = $_POST["imagem"];
    $nome_arquivo = "image_webcam_pessoa_emp{$_SESSION['codpessoa']}_{$_POST["codpessoa"]}".date("Ymd").".png";
    if (isset($inputValue)) {
        if (strpos($inputValue, "data:image/png;base64,") === 0) {
            $fd = fopen("../arquivos/{$nome_arquivo}", "wb");
            $data = base64_decode(substr($inputValue, strlen("data:image/png;base64,")));
        } else if (strpos($inputValue, "data:image/jpg;base64,") === 0) {
            $fd = fopen("../arquivos/{$nome_arquivo}", "wb");
            $data = base64_decode(substr($inputValue, strlen("data:image/jpg;base64,")));
        }

        if ($fd) {
            fwrite($fd, $data);
            fclose($fd);
        } else {
            die(json_encode(array('mensagem' => "Erro ao transferir arquivo para servidor!!!", 'situacao' => false)));
        }
    }
    
    $pessoa->imagem = $nome_arquivo;
    $resAtualizarPessoa = $pessoa->atualizar();
    if($resAtualizarPessoa !== FALSE){
        include "../model/Log.php";
        $log = new Log($conexao);
        
        
        $log->acao       = "procurar";
        $log->observacao = "Salvo foto da img: {$_POST["imagem"]} - em ". date('d/m/Y'). " - ". date('H:i');
        $log->codpagina  = "0";
        
        $log->hora = date('H:i:s');
        $log->inserir();         
        die(json_encode(array('mensagem' => "Sucesso ao salvar imagem", 'situacao' => true)));
    }else{
        die(json_encode(array('mensagem' => "Erro ao salvar imagem causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
    }