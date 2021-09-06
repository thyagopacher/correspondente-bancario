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
    $tabela   = new Tabela($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $tabela->$key = $value;
    }    
    
    $res = $tabela->atualizar();
    if($res === FALSE){
        $msg_retorno = "Erro ao atualizar tabela! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $codigo_tabela = $tabela->codtabela;
        $msg_retorno = "Tabela atualizada com sucesso!";
        $sit_retorno = true;      
        $resTabelaPrazoDel = $conexao->comando("delete from tabelaprazo where codtabela = '{$tabela->codtabela}'");
        if($resTabelaPrazoDel == FALSE){
            die(json_encode(array('mensagem' => "Erro ao apagar tabela prazo:". mysqli_error($conexao->conexao), 'situacao' => false)));
        }
        foreach ($_POST["prazode"] as $key => $prazode) {
            $tabelap = new TabelaPrazo($conexao);
            $tabelap->dtinicio = $_POST["dtinicio"][$key];
            $tabelap->dtfim = $_POST["dtfim"][$key];   
            
            $tabelap->prazoate = $_POST["prazoate"][$key];
            $tabelap->prazode = $prazode;         
           
            $tabelap->comissao = $_POST["comissao"][$key];
            $tabelap->bonus = $_POST["bonus"][$key];   
            $tabelap->rco  = str_replace(',', '.', $_POST["rco"][$key]);            
            $tabelap->codtabela         = $codigo_tabela;
            $tabelap->pgto_liquido      = $_POST["pgto_liquido"][$key];           
            $resInserirPrazo = $tabelap->inserir();
            $codigo_tabelap = mysqli_insert_id($conexao->conexao);
            if($resInserirPrazo == FALSE){
                die(json_encode(array('mensagem' => "Erro ao inserir tabela X prazo", 'situacao' => false)));
            }else{
                
                $resnivel = $conexao->comando('select codnivel, nome from nivel where ((padrao = "s" and codnivel = 16) or (codempresa = ' . $_SESSION["codempresa"] . ' and padrao = "n")) and codnivel <> 1 and codnivel <> 19 order by nivel.codnivel');
                $qtdnivel = $conexao->qtdResultado($resnivel);          
                if($qtdnivel > 0){
                    
                    while($nivelp = $conexao->resultadoArray($resnivel)){
                        $pctNivel = new PctNivelTabelaPrazo($conexao);
                        $pctNivel->codnivel    = $nivelp["codnivel"];
                        $pctNivel->codtabelap  = $codigo_tabelap;
                        $pctNivel->porcentagem = $_POST["pctnivel"][$key];
                        $resPctNivelInserir = $pctNivel->inserir();
                        if($resPctNivelInserir == FALSE){
                            die(json_encode(array('mensagem' => "Erro ao inserir pct tabela prazo:". mysqli_error($conexao->conexao), 'situacao' => false)));
                        }
                    }
                }
            }
        }        
    }

    include "../model/Log.php";
    $log = new Log($conexao);
    $log->acao       = "procurar";
    $log->observacao = "Atualizado tabela: {$tabela->nome} - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    $log->inserir();   

    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));