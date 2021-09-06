<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WSViper
 *
 * @author ThyagoHenrique
 */
class WSViper {
    
    public $usuario = "LIBRAM@WS406485";
    
    /**
     * consulta por beneficio do cliente
     */
    public function consultaBeneficio($beneficio){
        $url       = 'http://app.viperconsig.com.br/WSInssben/wsdl';
        $function  = 'ConsultaBen';
        $arguments = array($function => array('nb' => $beneficio,'username' => $this->usuario));        
        $client    = new SoapClient($url);
        $result    = $client->__soapCall($function, $arguments);
    }
    
    /**
     * consulta por cpf do cliente
     */
    public function consultaCpf($cpf){
        $url       = 'http://app.viperconsig.com.br/WSInsscpf/wsdl';
        $function  = 'ConsultaCpf';
        $arguments = array($function => array('cpf' => $cpf,'username' => $this->usuario)); 
        $client    = new SoapClient($url);
        $result    = $client->__soapCall($function, $arguments);
    }
    
    /**
     * consulta por matricula + cpf siape
     */
    public function consultaSiape($matricula, $cpf){
        $url      = 'http://app.viperconsig.com.br/WSSiape/wsdl';
        $function = 'Consulta';
        $arguments = array($function => array('matricula' => $matricula, 'cpf' => $cpf,'username' => $this->usuario)); 
        $client   = new SoapClient($url);
        $result   = $client->__soapCall($function, $arguments);
    }
}


$viper = new WSViper();
$resultado = $viper->consultaCpf('39655539920');
echo $resultado;