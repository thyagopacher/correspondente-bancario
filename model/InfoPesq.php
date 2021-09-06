<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InfoPesq
 *
 * @author ThyagoHenrique
 */
use Conexao;

class InfoPesq {
    
    public $token;
    public $link = 'http://infopesq.com/app_web/';
    public $tipoConsulta = 'XML';
    public $conexao;
    
    function __construct() {
        $this->conexao = new Conexao();
        if(isset($_SESSION["codempresa"]) && $_SESSION["codempresa"] != NULL && $_SESSION["codempresa"] != ""){
            $sql = 'select tokenInfoPesq from configuracao where codempresa = '. $_SESSION["codempresa"];
            $configuracaop = $this->conexao->comandoArray($sql);
            if(isset($configuracaop["tokenInfoPesq"]) && $configuracaop["tokenInfoPesq"] != NULL && $configuracaop["tokenInfoPesq"] != ""){
                $this->token = $configuracaop["tokenInfoPesq"];
            }else{
                die(json_encode(array('mensagem' => "Sem token definido para a empresa!!!", 'situacao' => false)));
            }
        }
    }

    function __destruct() {
        unset($this->token);
    }    
    
    public function procuraNome($nome){
        $this->link .= '?CONSULTA=NOME&NOME='.$nome.'&tk='.$this->token.'&TYPE=ARRAY';
        return file_get_contents($this->link);        
    }
    
    public function procuraCPF($cpf){
        $cpf = str_pad($cpf, 11, "0", STR_PAD_LEFT);
        $this->link .= '?CONSULTA=CPF&CPF='.$cpf.'&tk='.$this->token.'&TYPE='. $this->tipoConsulta;
        return simplexml_load_string(file_get_contents($this->link));        
    }
    
    public function procuraBeneficio($beneficio){
        $this->link .= '?CONSULTA=INSS&NB='.$beneficio.'&tk='.$this->token.'&TYPE='. $this->tipoConsulta;
        return simplexml_load_string(file_get_contents($this->link));        
    }
    
    public function procuraCNPJ($cnpj){
        $this->link .= '?CONSULTA=CNPJ&CNPJ='.$cnpj.'&tk='.$this->token.'&TYPE='. $this->tipoConsulta;
        return simplexml_load_string(file_get_contents($this->link));        
    }
    
}
