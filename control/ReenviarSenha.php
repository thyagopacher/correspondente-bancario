<?php
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);
    include "../model/Conexao.php";
    include "../model/Pessoa.php";
    include "Email.php";
    include "../model/Log.php";
    $conexao = new Conexao(); 
    $pessoa  = new Pessoa($conexao);
    $pessoap = $conexao->comandoArray("select codpessoa, nome, codempresa, senha, email from pessoa where email = '{$_POST["email"]}' and senha <> ''");
    $empresa = $conexao->comandoArray("select razao, email from empresa where codempresa = '{$pessoap["codempresa"]}'");
    if(isset($pessoap) && isset($pessoap["codpessoa"]) && $pessoap["codpessoa"] != NULL && $pessoap["codpessoa"] != "" && $pessoap["codempresa"] && $pessoap["codempresa"] != ""){
        
        $pessoa = new Pessoa($conexao);
        $pessoa->codpessoa  = $pessoap["codpessoa"];
        $senha_gerada       = $pessoa->geraSenha();
        $pessoa->senha      = $senha_gerada;
        $resAtualizarPessoa = $pessoa->atualizar();
        if($resAtualizarPessoa == FALSE){
            die(json_encode(array('mensagem' => "Senha não pode ser atualizada erro causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
        }
        
        $email = new Email($conexao);
        $email->assunto        = "Reenvio de senha para acesso ao South Negócios";
        $email->mensagem       = "Olá caro usuário {$pessoap["nome"]} sua senha é ".($senha_gerada)."<br>";       
        $email->mensagem      .= "E-mail enviado pelo sistema. Data: ". date('d/m/Y');       
        $email->para           = $pessoap["nome"];
        $email->para_email     = $_POST["email"];
        $resEnviaEmail         = $email->envia();

        if($resEnviaEmail == FALSE && $email->erro != NULL && $email->erro != ""){
            die(json_encode(array('mensagem' => "Erro ao enviar e-mail de senha causado por 1:". $email->erro, 'situacao' => false)));
        }
        

        $log             = new Log($conexao);
        $log->acao       = "atualizar";
        $log->codempresa = $pessoap["codempresa"];
        $log->codpagina  = 4;
        $log->codpessoa  = $pessoap["codpessoa"];
        $log->data       = date('Y-m-d');
        $log->hora       = date('H:i:s');
        $log->observacao =  $email->assunto."<br>".$email->mensagem;
        $log->inserir();

        if($resEnviaEmail != FALSE){
            die(json_encode(array('mensagem' => "Senha enviada para o seu e-mail!!!", 'situacao' => true)));
        }else{
            die(json_encode(array('mensagem' => "Erro ao enviar senha para o e-mail causado por 2:". mysqli_error($conexao->conexao), 'situacao' => false)));
        }
    }else{
        die(json_encode(array('mensagem' => "Não achou ninguém cadastrado com esse e-mail no sistema!!!", 'situacao' => false)));
    }
?>