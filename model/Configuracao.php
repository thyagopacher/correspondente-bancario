<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Configuracao{
    
    public $codconfiguracao;
    public $loginSMS;
    public $senhaSMS;
    public $dtcadastro;
    public $codfuncionario;
    public $loginViper;
    public $usuarioMultiBR;
    public $senhaMultiBR;
    public $keyMultiBR;
    public $tempoocioso;
    private $conexao;    
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }
    
    public function inserir(){
        if(!isset($this->dtcadastro) || $this->dtcadastro == NULL || $this->dtcadastro == ""){
            $this->dtcadastro = date("Y-m-d H:i:s");
        }        
        if(!isset($this->codempresa) || $this->codempresa == NULL || $this->codempresa == ""){
            $this->codempresa = $_SESSION["codempresa"];
        }        
        if(!isset($this->codfuncionario) || $this->codfuncionario == NULL || $this->codfuncionario == ""){
            $this->codfuncionario = $_SESSION["codpessoa"];
        }        
        return $this->conexao->inserir("configuracao", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("configuracao", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("configuracao", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("configuracao", $this);
    }
    
}