<?php
    set_time_limit(0);
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
    include("./excel/reader.php");
    $data           = new Spreadsheet_Excel_Reader();
    $sit_retorno    = true;
    $msg_retorno    = "";
    $first_row      = true;    
    $conexao        = new Conexao();
    $pessoa         = new Pessoa($conexao);
    $telefone       = new Telefone($conexao);
    
    $totPessoasNEncontradas = 0;
    
    if (!empty($_FILES['arquivo']) && $_FILES['arquivo']['type'] != "application/vnd.ms-excel" && $_FILES["arquivo"]["type"] != "application/octet-stream") {
        die(json_encode(array('mensagem' => "Só pode arquivo em formado XLS!", 'situacao' => false)));
    }    
    
    /**lendo o arquivo xls*/
    $data->setOutputEncoding('UTF-8');
    $data->read($_FILES['arquivo']['tmp_name']);    
    
    
    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
//        
//        var_dump($data->sheets[0]['cells'][$i]);
        if (!isset($data->sheets[0]['cells'][$i])) {//continue para linha em branco
            continue;
        }  
        $cpf     = $pessoa->soNumero(trim($data->sheets[0]['cells'][$i][8]));//retira pontos do cpf
        $sql = "select codpessoa, codempresa 
        from pessoa 
        where codempresa = {$_SESSION["codempresa"]} 
        and (replace(replace(cpf, '.', ''), '-', '') = '{$cpf}' or replace(replace(cpf, '.', ''), '-', '') = '0{$cpf}')
        and codcategoria in(1,6)";
        $pessoap = $conexao->comandoArray($sql);
        
        if(!isset($pessoap["codpessoa"]) || $pessoap["codpessoa"] == NULL || $pessoap["codpessoa"] == ""){
            $totPessoasNEncontradas++;
            continue;
        }
        
        //inserindo telefone coluna 1
        $telefone = new Telefone($conexao);
        $telefone->codempresa     = $pessoap["codempresa"];
        $telefone->codfuncionario = 6;
        $telefone->codpessoa      = $pessoap["codpessoa"];
        $telefone->numero         = $telefone->soNumero(trim($data->sheets[0]['cells'][$i][11]));
        $sql = "select codtelefone from telefone where codempresa = '{$_SESSION['codempresa']}' 
        and numero = '{$telefone->numero}' and codpessoa = '{$pessoap["codpessoa"]}' and codpessoa in(select codpessoa from pessoa where codcategoria in(1,6))";    
        die($sql);
        $telefonep = $conexao->comandoArray($sql);
        if(isset($telefonep["codtelefone"]) && $telefonep["codtelefone"] != NULL && $telefonep["codtelefone"] != ""){
            continue;
        }else{
            if($telefone->identificaCelular($telefone->numero)){
                $telefone->codtipo = "3";
            }else{
                $telefone->codtipo = "1";
            } 
            $telefone->dtcadastro = date("Y-m-d H:i:s");
            $resInserirTelefone   = $telefone->inserir();
            if($resInserirTelefone == FALSE){
                die(json_encode(array('mensagem' => "Erro ao inserir novo telefone causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
            }
        }
        
        //inserindo telefone coluna 2
        $telefone = new Telefone($conexao);
        $telefone->codempresa     = $pessoap["codempresa"];
        $telefone->codfuncionario = 6;
        $telefone->codpessoa      = $pessoap["codpessoa"];
        $telefone->numero         = $telefone->soNumero(trim($data->sheets[0]['cells'][$i][12]));
        $telefonep                = $conexao->comandoArray("select codtelefone from telefone where codempresa = '{$_SESSION['codempresa']}' and numero = '{$telefone->numero}' and codpessoa = '{$pessoap["codpessoa"]}' and codpessoa in(select codpessoa from pessoa where codcategoria in(1,6))");
        if(isset($telefonep["codtelefone"]) && $telefonep["codtelefone"] != NULL && $telefonep["codtelefone"] != ""){
            continue;
        }else{
            if($telefone->identificaCelular($telefone->numero)){
                $telefone->codtipo = "3";
            }else{
                $telefone->codtipo = "1";
            } 
            $telefone->dtcadastro = date("Y-m-d H:i:s");
            $resInserirTelefone   = $telefone->inserir();
            if($resInserirTelefone == FALSE){
                die(json_encode(array('mensagem' => "Erro ao inserir novo telefone causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
            }
        }
        
        //inserindo telefone coluna 3
        $telefone = new Telefone($conexao);
        $telefone->codempresa     = $pessoap["codempresa"];
        $telefone->codfuncionario = 6;
        $telefone->codpessoa      = $pessoap["codpessoa"];
        $telefone->numero         = $telefone->soNumero(trim($data->sheets[0]['cells'][$i][13]));
        $telefonep                = $conexao->comandoArray("select codtelefone from telefone where codempresa = '{$_SESSION['codempresa']}' and numero = '{$telefone->numero}' and codpessoa = '{$pessoap["codpessoa"]}' and codpessoa in(select codpessoa from pessoa where codcategoria in(1,6))");
        if(isset($telefonep["codtelefone"]) && $telefonep["codtelefone"] != NULL && $telefonep["codtelefone"] != ""){
            continue;
        }else{
            if($telefone->identificaCelular($telefone->numero)){
                $telefone->codtipo = "3";
            }else{
                $telefone->codtipo = "1";
            } 
            $telefone->dtcadastro = date("Y-m-d H:i:s");
            $resInserirTelefone   = $telefone->inserir();
            if($resInserirTelefone == FALSE){
                die(json_encode(array('mensagem' => "Erro ao inserir novo telefone causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
            }
        }
        
        //inserindo telefone coluna 4
        $telefone = new Telefone($conexao);
        $telefone->codempresa     = $pessoap["codempresa"];
        $telefone->codfuncionario = 6;
        $telefone->codpessoa      = $pessoap["codpessoa"];
        $telefone->numero         = $telefone->soNumero(trim($data->sheets[0]['cells'][$i][14]));
        $telefonep                = $conexao->comandoArray("select codtelefone from telefone where codempresa = '{$_SESSION['codempresa']}' and numero = '{$telefone->numero}' and codpessoa = '{$pessoap["codpessoa"]}' and codpessoa in(select codpessoa from pessoa where codcategoria in(1,6))");
        if(isset($telefonep["codtelefone"]) && $telefonep["codtelefone"] != NULL && $telefonep["codtelefone"] != ""){
            continue;
        }else{
            if($telefone->identificaCelular($telefone->numero)){
                $telefone->codtipo = "3";
            }else{
                $telefone->codtipo = "1";
            } 
            $telefone->dtcadastro = date("Y-m-d H:i:s");
            $resInserirTelefone   = $telefone->inserir();
            if($resInserirTelefone == FALSE){
                die(json_encode(array('mensagem' => "Erro ao inserir novo telefone causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
            }
        }
        
        //inserindo telefone coluna 5
        $telefone = new Telefone($conexao);
        $telefone->codempresa     = $pessoap["codempresa"];
        $telefone->codfuncionario = 6;
        $telefone->codpessoa      = $pessoap["codpessoa"];
        $telefone->numero         = $telefone->soNumero(trim($data->sheets[0]['cells'][$i][15]));
        $telefonep                = $conexao->comandoArray("select codtelefone from telefone where codempresa = '{$_SESSION['codempresa']}' and numero = '{$telefone->numero}' and codpessoa = '{$pessoap["codpessoa"]}' and codpessoa in(select codpessoa from pessoa where codcategoria in(1,6))");
        if(isset($telefonep["codtelefone"]) && $telefonep["codtelefone"] != NULL && $telefonep["codtelefone"] != ""){
            continue;
        }else{
            if($telefone->identificaCelular($telefone->numero)){
                $telefone->codtipo = "3";
            }else{
                $telefone->codtipo = "1";
            } 
            $telefone->dtcadastro = date("Y-m-d H:i:s");
            $resInserirTelefone   = $telefone->inserir();
            if($resInserirTelefone == FALSE){
                die(json_encode(array('mensagem' => "Erro ao inserir novo telefone causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
            }
        }
        
        //inserindo telefone coluna 6
        $telefone = new Telefone($conexao);
        $telefone->codempresa     = $pessoap["codempresa"];
        $telefone->codfuncionario = 6; 
        $telefone->codpessoa      = $pessoap["codpessoa"];
        $telefone->numero         = $telefone->soNumero(trim($data->sheets[0]['cells'][$i][16]));
        $telefonep                = $conexao->comandoArray("select codtelefone from telefone where codempresa = '{$_SESSION['codempresa']}' and numero = '{$telefone->numero}' and codpessoa = '{$pessoap["codpessoa"]}' and codpessoa in(select codpessoa from pessoa where codcategoria in(1,6))");
        if(isset($telefonep["codtelefone"]) && $telefonep["codtelefone"] != NULL && $telefonep["codtelefone"] != ""){
            continue;
        }else{
            if($telefone->identificaCelular($telefone->numero)){
                $telefone->codtipo = "3";
            }else{
                $telefone->codtipo = "1";
            } 
            $telefone->dtcadastro = date("Y-m-d H:i:s");
            $resInserirTelefone   = $telefone->inserir();
            if($resInserirTelefone == FALSE){
                die(json_encode(array('mensagem' => "Erro ao inserir novo telefone causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
            }
        }
    }

    if($totPessoasNEncontradas > 0){
        $msg_final = "\nPessoas não encontradas: $totPessoasNEncontradas";    
    }
    
     die(json_encode(array('mensagem' => "Telefones importados com sucesso".$msg_final, 'situacao' => true)));   