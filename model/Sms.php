<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sms
 *
 * @author Thyago H Pacher
 */

class Sms {
    
    private $string_url = "http://sms.multibr.com/painel/ServiceSms.asmx?WSDL";
    private $cliente;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }    
    
    public function __destruct() {
        unset($this);
    }  
    
    /**
     * método para consultar o saldo do cliente
     * @param String $usuario - é o usuário que conecta ao webservice de SMS
     * @param String $senha - é o senha que conecta ao webservice de SMS
     * @return  double retorna saldo da campanha
     */
    public function saldoCliente($usuario, $senha){
        $this->cliente = SoapClient($this->string_url, array("User" => $usuario, "Pwd" => $senha));
        $retorno = $this->cliente->GetBalance();
        return $retorno;
    }
    
    /**
     * método para consultar o status da campanha
     * @param String $usuario - é o usuário que conecta ao webservice de SMS
     * @param String $senha - é o senha que conecta ao webservice de SMS
     * @param String $id_campanha - é o id da campanha
     * @return String DataSet contendo todos os dados da campanha
     */    
    public function statusCampanha($usuario, $senha, $id_campanha){
        $this->cliente = SoapClient($this->string_url, array("User" => $usuario, "Pwd" => $senha, "Id" => $id_campanha));
        $retorno = $this->cliente->GetStatus();
        return $retorno;
    }
    
    /**
     * Método para consultar um documento de determinada campanha
     * @param String $usuario - é o usuário que conecta ao webservice de SMS
     * @param String $senha - é o senha que conecta ao webservice de SMS
     * @param String $id_campanha - é o id da campanha
     * @param String $ref -  Código de referencia passado em cada destino dentro da campanha
     */        
    public function GetStatusByRef($usuario, $senha, $id_campanha, $ref){
        $this->cliente = SoapClient($this->string_url, array("User" => $usuario, "Pwd" => $senha, "Id" => $id_campanha, "Ref" => $ref));
        $retorno = $this->cliente->GetStatusByRef();
        return $retorno;        
    } 
    
    /**
     * Método para enviar SMS
     * @param String $usuario - é o usuário que conecta ao webservice de SMS
     * @param String $senha - é o senha que conecta ao webservice de SMS
     * @param String $smsMessage -  Objeto mensagem sms
     * @return type CODIGO DA CAMPANHA GERADO:SUCESSO ou 0:ERRO OCORRIDO DURANTE O PROCESSAMENTO
     */
    public function enviaSMS($usuario, $senha, $smsMessage){
        $this->cliente = SoapClient($this->string_url, array("User" => $usuario, "Pwd" => $senha, 'SmsMessage' => $smsMessage));
        $retorno = $this->cliente->SendSMS();
        return $retorno;        
    }
    
    /**
     * Método para prender a campanha
     * @param String $usuario - é o usuário que conecta ao webservice de SMS
     * @param String $senha - é o senha que conecta ao webservice de SMS
     * @param String $id_campanha - é o id da campanha
     * @return integer Retorno: 1:SUCESSO OU 0:ERRO OCORRIDO
     */
    public function SetHoldSMS($usuario, $senha, $id_campanha){
        $this->cliente = SoapClient($this->string_url, array("User" => $usuario, "Pwd" => $senha, "Id" => $id_campanha));
        $retorno = $this->cliente->SetHoldSMS();
        return $retorno;        
    } 
    
    /**
     * Método para liberar a campanha
     * @param String $usuario - é o usuário que conecta ao webservice de SMS
     * @param String $senha - é o senha que conecta ao webservice de SMS
     * @param String $id_campanha - é o id da campanha
     * @return integer Retorno: 1:SUCESSO OU 0:ERRO OCORRIDO
     */
    public function SetReleaseSMS($usuario, $senha, $id_campanha){
        $this->cliente = SoapClient($this->string_url, array("User" => $usuario, "Pwd" => $senha, "Id" => $id_campanha));
        $retorno = $this->cliente->SetReleaseSMS();
        return $retorno;        
    } 
}
