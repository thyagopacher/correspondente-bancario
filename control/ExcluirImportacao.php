<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include("../model/Conexao.php");
    include("../model/Importacao.php");
    $conexao    = new Conexao();
    $importacao = new Importacao($conexao);
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $importacao->$key = $value;
    } 
    if(!isset($importacao->codimportacao) || $importacao->codimportacao == NULL || $importacao->codimportacao == ""){
     
        $resExcluirImportacao = $importacao->excluirCarteira($importacao->codcarteira);
        $resImportacao = $conexao->comando("select codimportacao from importacao where codcarteira = '{$importacao->codcarteira}'");
        $qtdImportacao = $conexao->qtdResultado($resImportacao);
        if($qtdImportacao > 0){
            while($importacao2 = $conexao->resultadoArray($resultado)){
                //apagando telefones do cliente
                $resExcluirTelefone    = $conexao->comando("delete from telefone where codpessoa in(select codpessoa from pessoa where codimportacao = '{$importacao2["codimportacao"]}');");
                if($resExcluirTelefone == FALSE){
                    die(json_encode(array('mensagem' => "Erro ao excluir telefone do cliente causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
                    break;                    
                }                

                //apagando beneficios do clientes
                $resExcluirBeneficio   = $conexao->comando("delete from beneficiocliente where codpessoa in(select codpessoa from pessoa where codimportacao = '{$importacao2["codimportacao"]}');");
                if($resExcluirBeneficio == FALSE){
                    die(json_encode(array('mensagem' => "Erro ao excluir beneficio do cliente causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
                    break;                    
                }
                //apagando atendimento de pessoas da importação
                $resExcluirAtendimento = $conexao->comando("delete from atendimento where codpessoa in(select codpessoa from pessoa where codimportacao = '{$importacao2["codimportacao"]}');");
                if($resExcluirAtendimento == FALSE){
                    die(json_encode(array('mensagem' => "Erro ao excluir atendimento do cliente causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
                    break;                    
                }
                //apagando obs. de cliente pessoas da importação
                $resExcluirPessoa      = $conexao->comando("delete from observacaocliente where codpessoa in(select codpessoa from pessoa where codimportacao = '{$importacao2["codimportacao"]}');");
                if($resExcluirPessoa == FALSE){
                    die(json_encode(array('mensagem' => "Erro ao excluir cliente da carteira causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
                    break;
                }
                //apagando pessoas da importação
                $resExcluirPessoa      = $conexao->comando("delete from pessoa where codimportacao = '{$importacao2["codimportacao"]}'");
                if($resExcluirPessoa == FALSE){
                    die(json_encode(array('mensagem' => "Erro ao excluir cliente da carteira causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
                    break;
                }
            }
        }
        //apagando carteira dos clientes
        $sql = "delete from carteiracliente where codcliente in(select codpessoa from pessoa where codimportacao = '{$importacao->codcarteira}');";
        $resExcluirCarteiraCliente = $conexao->comando($sql)or die("<pre>$sql</pre>");
        if($resExcluirCarteiraCliente == FALSE){
            die(json_encode(array('mensagem' => "Erro ao excluir carteira do cliente causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
        }
        
        $sql = "delete from carteira where codcarteira = '{$importacao->codcarteira}'";
        $resExcluirCarteira   = $conexao->comando($sql) or die("<pre>$sql</pre>");
        if($resExcluirCarteira == FALSE){
            die(json_encode(array('mensagem' => "Erro ao excluir carteira causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
        }   
    }else{
        $resExcluirImportacao = $importacao->excluir();
    }
    if($resExcluirImportacao !== FALSE){
        $msg_retorno = "Excluido com sucesso a carteira!";
        $sit_retorno = true;
    }else{
        $msg_retorno = "Erro ao excluir carteira causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;        
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
    
    