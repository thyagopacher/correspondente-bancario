<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class ConsultasSouth{
    
    public $codconsulta;
    public $codempresa;
    public $codfuncionario;
    public $dtcadastro;
    public $valor;
    public $campo;
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
        return $this->conexao->inserir("consultassouth", $this);
    }
    
    public function atualizar(){
        if(!isset($this->dtpagamento) || $this->dtpagamento == NULL || $this->dtpagamento == ""){
            $this->dtpagamento = " ";
        }
        return $this->conexao->atualizar("consultassouth", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("consultassouth", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("consultassouth", $this);
    }
  
}