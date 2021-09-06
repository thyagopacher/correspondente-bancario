<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Convenio{
    
    public $codconvenio;
    public $nome;
    public $dtcadastro;
    public $codfuncionario;
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
        return $this->conexao->inserir("convenio", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("convenio", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("convenio", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("convenio", $this);
    }
    
}