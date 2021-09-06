<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/Agenda.php";
    
    $conexao = new Conexao();
    $agenda  = new Agenda($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $agenda->$key = $value;
    }  
    if(!isset($agenda->dtagenda) || $agenda->dtagenda == NULL || $agenda->dtagenda == ""){
        die(json_encode(array('mensagem' => "Não pode agendar sem data!!!", 'situacao' => false)));
    }
    
    $agenda->codpessoa      = $_POST["cliente"];
    $agenda->codfuncionario = $_POST["operador"];
    $agenda->dtcadastro     = date("Y-m-d H:i:s");
    $agenda->codempresa     = $_SESSION['codempresa'];    
    $agenda->dtagenda       = implode("-",array_reverse(explode("/",$agenda->dtagenda)));
    $sql = "select * 
    from agenda 
    where codempresa = '{$agenda->codempresa}' 
    and codfuncionario = '{$agenda->codfuncionario}' 
    and dtagenda = '{$agenda->dtagenda}' 
    and codpessoa = '{$agenda->codpessoa}'";
    $agendap = $conexao->comandoArray($sql);
    if(isset($agendap) && isset($agendap["codagenda"])){
        die(json_encode(array('mensagem' => "Não pode cadastrar agenda duas vezes para o mesmo dia e mesmo cliente!!!", 'situacao' => false)));
    }
    
    $res = $agenda->inserir();   
    if($res !== FALSE){
        $msg_retorno = "Agenda salva com sucesso!!!";
        $sit_retorno = true;
        include "../model/Log.php";
        $log = new Log($conexao);
        
        
        $log->acao       = "procurar";
        $log->observacao = "Agenda remanejada - em ". date('d/m/Y'). " - ". date('H:i');
        $log->codpagina  = "0";
        
        $log->hora = date('H:i:s');
        $log->inserir();            
    }else{
        $msg_retorno = "Erro ao agendar causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));