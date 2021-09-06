<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Atendimento{
    
    public $codatendimento;
    public $codfuncionario;
    public $dtcadastro;
    public $codpessoa;
    public $codempresa;
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
        if(!isset($this->codempresa) || $this->codempresa == NULL || trim($this->codempresa) == ""){
            $this->codempresa = $_SESSION["codempresa"];
        }        
        if(!isset($this->codfuncionario) || $this->codfuncionario == NULL || $this->codfuncionario == ""){
            $this->codfuncionario = $_SESSION["codpessoa"];
        }        
        return $this->conexao->inserir("atendimento", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("atendimento", $this);
    }  
    
    public function excluir($codacesso){
        $this->codacesso = $codacesso;
        return $this->conexao->excluir("atendimento", $this);
    }
    
    public function procuraCodigo($codacesso){
        $this->codacesso = $codacesso;
        return $this->conexao->procurarCodigo("atendimento", $this);
    }
   
}