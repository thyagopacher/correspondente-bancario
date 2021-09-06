<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class SouthConsulta{
    
    public $codconsulta;
    public $codempresa;
    public $qtdconsulta;
    public $dtcadastro;
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
        return $this->conexao->inserir("southconsulta", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("southconsulta", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("southconsulta", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("southconsulta", $this);
    }
  
}