<?php
    
    session_start();
    if(!isset($_SESSION['codempresa'])){
        die(json_encode(array('mensagem' => "Sua sessão caiu por favor sai e entre novamente no sistema!!!", 'situacao' => false)));
    }
    if(!isset($_POST["codpessoa"]) || $_POST["codpessoa"] == NULL || $_POST["codpessoa"] == ""){
        die(json_encode(array('mensagem' => "Erro ao cadastrar agenda não pode inserir sem passar o código da pessoa, por favor contate o desenvolvedor!!!", 'situacao' => false)));
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
    $pessoap = $conexao->comandoArray("select codcategoria from pessoa where codpessoa = '{$_POST["codpessoa"]}' and codempresa = '{$_SESSION['codempresa']}'");
    if((!isset($agenda->observacao) || $agenda->observacao == NULL || $agenda->observacao == "") && ($pessoap["codcategoria"] == 6)){
        die(json_encode(array('mensagem' => "Não pode agendar sem observação da ligação!!!", 'situacao' => false)));
    }
    if(!isset($agenda->dtagenda) || $agenda->dtagenda == NULL || $agenda->dtagenda == ""){
        die(json_encode(array('mensagem' => "Não pode agendar sem data!!!", 'situacao' => false)));
    }
    if($agenda->codstatus == "3" || $agenda->codstatus == "5" || $agenda->codstatus == "7" || $agenda->codstatus == "9"
            || $agenda->codstatus == "10" || $agenda->codstatus == "12" || $agenda->codstatus == "14"){
        if(!isset($_POST["dtagenda"]) || $_POST["dtagenda"] == NULL || $_POST["dtagenda"] == ""){
            die(json_encode(array('mensagem' => "Não pode inserir sem data de agenda!!!", 'situacao' => false)));
        }
    }  
    
    $agenda->codfuncionario = $_SESSION['codpessoa'];
    $agenda->dtcadastro     = date("Y-m-d H:i:s");
    $agenda->codempresa     = $_SESSION['codempresa'];  
    $agenda->codcarteira    = $_POST["codcarteira"];
    $agenda->dtagenda       = implode("-",array_reverse(explode("/",$agenda->dtagenda))). ' '.$_POST["horaagenda"];
    $sql = "select codagenda from agenda where codempresa = '{$agenda->codempresa}' and atendido = 'n'
    and dtagenda >= '".$agenda->dtagenda."' 
    and dtagenda <= '".$agenda->dtagenda."' 
    and codpessoa = '{$agenda->codpessoa}'"; 
    $agendap = $conexao->comandoArray($sql);
    if(isset($agendap) && isset($agendap["codagenda"])){
        die(json_encode(array('mensagem' => "Já tem agendamento para a data, por favor escolha outra!!!", 'situacao' => false)));
    }
    
    $sql = "select codagenda from agenda where codempresa = '{$agenda->codempresa}' and atendido = 'n' and codfuncionario = '{$agenda->codfuncionario}' and codpessoa = '{$agenda->codpessoa}'";    
    $agendap = $conexao->comandoArray($sql);
    if(isset($agendap) && isset($agendap["codagenda"])){
        $sql = "update agenda set atendido = 's' where codfuncionario = '{$agendap["codfuncionario"]}' and atendido = 'n' and codpessoa = '{$agenda->codpessoa}'";
        $conexao->comando($sql);
    }
    if(isset($agenda->codstatus) && $agenda->codstatus != NULL && $agenda->codstatus != ""){
        $agenda->observacao = $_POST["observacao"]; 
    }  
    if(strtotime(date($agenda->dtagenda)) < strtotime(date('Y-m-d'))){
        die(json_encode(array('mensagem' => "Por favor verifique a data de agendamento, não pode ter data menor que hoje!!!", 'situacao' => false)));
    }
    $res = $agenda->inserir();   
    if($res !== FALSE){
        $msg_retorno = "Agenda salva com sucesso!!!";
        include("../model/Atendimento.php");
        $atendimento = new Atendimento($conexao);
        $atendimento->codpessoa      = $_POST["codpessoa"];
        $resInserirAtendimento       = $atendimento->inserir();
        if($resInserirAtendimento == FALSE){
            mail("thyago.pacher@gmail.com", "Erro ao inserir atendimento efetuado", "Erro causado por:". mysqli_error($conexao->conexao));
        }
    }else{
        $msg_retorno = "Erro ao agendar causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));