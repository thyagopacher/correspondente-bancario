<?php

    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    if (isset($_FILES["arquivo"])) {
        
        function __autoload($class_name) {
            if (file_exists("../model/" . $class_name . '.php')) {
                include "../model/" . $class_name . '.php';
            } elseif (file_exists("../visao/" . $class_name . '.php')) {
                include "../visao/" . $class_name . '.php';
            } elseif (file_exists("./" . $class_name . '.php')) {
                include "./" . $class_name . '.php';
            }
        }
        $delimitador = ';';
        $cerca       = '"';        
        $upload      = new Upload($_FILES["arquivo"]);
        if ($upload->erro == "") {
            $conexao   = new Conexao();
            $f         = fopen("../arquivos/".$upload->nome_final, 'r');
            if ($f) {
                $cabecalho = fgetcsv($f, 0, $delimitador, $cerca);
                
                while (!feof($f)) {
                    
                    $linha = fgetcsv($f, 0, $delimitador, $cerca);
                    if (!isset($linha[0]) || $linha[0] == NULL || $linha[0] == ""){
                        continue;
                    }
                    $prazos                      = explode(" ", str_replace("a", " ", $linha[3]));
                    $convenio1                    = explode(" ", $linha[2]);
                    
                    //inserindo novo banco
                    if($convenio1[0] == "BV" || strtoupper($convenio1[0]) == "BV"){
                        $bancop = $conexao->comandoArray("select * from banco where numbanco = '655'");
                    }elseif($convenio1[0] == "BGN" || strtoupper($convenio1[0]) == "BGN"){
                        $bancop = $conexao->comandoArray("select * from banco where numbanco = '739'");
                    }elseif($convenio1[0] == "Banrisul" || strtoupper($convenio1[0]) == "BANRISUL"){
                        $bancop = $conexao->comandoArray("select * from banco where numbanco = '041'");
                    }elseif($convenio1[0] == "BIC" || strtoupper($convenio1[0]) == "BIC" || $convenio1[0] == "BIC SUL FINANCEIRA"){
                        $bancop = $conexao->comandoArray("select * from banco where numbanco = '901'");
                    }elseif($convenio1[0] == "JV" || strtoupper($convenio1[0]) == "JV" || $convenio1[0] == "JV ITAU-BMG" || $convenio1[0] == "ITAU-BMG"){
                        $bancop = $conexao->comandoArray("select * from banco where numbanco = '029'");
                    }elseif($convenio1[0] == "Caixa Econômica Federal" || strtoupper($convenio1[0]) == "Caixa Econômica Federal"){
                        $bancop = $conexao->comandoArray("select * from banco where numbanco = '104'");
                    }else{
                        $bancop = $conexao->comandoArray("select * from banco where (nome like '%{$convenio1[0]}%' or LOWER(nome) like '".strtolower($convenio1[0])."')");
                    }
                    if(isset($bancop["codbanco"]) && $bancop["codbanco"] != NULL && $bancop["codbanco"] != ""){
                        $codigo_banco = $bancop["codbanco"];
                    }else{
                        $banco                 = new Banco($conexao);
                        $banco->codfuncionario = $_SESSION['codpessoa'];
                        $banco->nome           = $convenio1[0];
                        $banco->numbanco       = "";
                        $resInserirBanco       = $banco->inserir();
                        if($resInserirBanco == FALSE){
                            die(json_encode(array('mensagem' => "Erro ao inserir novo banco causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
                        }else{
                            $codigo_banco = mysqli_insert_id($conexao->conexao);
                        }
                    }
                    
                    //inserindo nova tabela
                    $tabela                 = new Tabela($conexao);
                    if($convenio1[1] == "INSS" || $convenio1[1] == "inss" || $convenio1[1] == "Inss"){
                        $tabela->codconvenio    = 1;
                    }elseif($convenio1[1] != ""){
                        $conveniop = $conexao->comandoArray("select * from convenio where (nome like '%{$convenio1[1]}%' or LOWER(nome) like '".strtolower($convenio1[1])."')");
                        if(isset($conveniop["codconvenio"]) && $conveniop["codconvenio"] != NULL && $conveniop["codconvenio"] != "0"){
                            $tabela->codconvenio = $conveniop["codconvenio"];
                        }else{
                            $convenio = new Convenio($conexao);
                            $convenio->codfuncionario = $_SESSION['codpessoa'];
                            $convenio->nome = $conveniop["nome"];
                            $resInserirConvenio = $convenio->inserir();
                            if($resInserirConvenio == FALSE){
                                die(json_encode(array('mensagem' => "Erro ao inserir novo convenio causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
                            }else{
                                $codigo_convenio = mysqli_insert_id($conexao->conexao);
                            }                            
                        }
                        $tabela->codconvenio = $codigo_convenio;
                    }
                    $tabela->codfuncionario = $_SESSION['codpessoa'];
                    $tabela->codbanco       = $codigo_banco;
                    $tabela->dtcadastro     = date("Y-m-d H:i:s");
                    $tabela->nome           = str_replace($convenio1[0], "", str_replace($convenio1[1], "", $linha[2]));
                    
                    $tabelap = $conexao->comandoArray("select codtabela from tabela 
                    where nome = '{$tabela->nome}' 
                    and codempresa = {$_SESSION["codempresa"]}    
                    and codbanco = '{$tabela->codbanco}'");
                    if(isset($tabelap["codtabela"]) && $tabelap["codtabela"] != NULL && $tabelap["codtabela"] != ""){
                        $resTabelaInserir       = $tabela->atualizar();
                    }else{
                        $resTabelaInserir       = $tabela->inserir();
                    }
                    if($resTabelaInserir == FALSE){
                        die(json_encode(array('mensagem' => "Erro ao inserir nova tabela causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
                    }else{
                        $codigo_tabela = mysqli_insert_id($conexao->conexao);
                    }                    
                    
                    //inserindo nova tabela prazo
                    $tabelaprazo                 = new TabelaPrazo($conexao);
                    $tabelaprazo->codfuncionario = $_SESSION['codpessoa'];
                    $tabelaprazo->codtabela      = $codigo_tabela; 
                    $tabelaprazo->comissao       = str_replace(",", ".", $linha[4]);
                    $tabelaprazo->dtcadastro     = date("Y-m-d H:i:s");
                    $tabelaprazo->prazode        = $prazos[0];
                    $tabelaprazo->prazoate       = $prazos[3];
                    if(strpos($linha[1], "/")){
                        $tabelaprazo->dtinicio = implode("-",array_reverse(explode("/",$linha[1])));
                    }
                    $resTabelaPrazoInserir       = $tabelaprazo->inserir();
                    if($resTabelaPrazoInserir == FALSE){
                        die(json_encode(array('mensagem' => "Erro ao inserir nova tabela causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
                    }                    
                }
                fclose($f);
            }            
        }
    }else{
        die(json_encode(array('mensagem' => "Arquivo não encontrado, por favor selecione algum!!!", 'situacao' => false)));
    }

    die(json_encode(array('mensagem' => "Importação efetuada com sucesso!", 'situacao' => true)));