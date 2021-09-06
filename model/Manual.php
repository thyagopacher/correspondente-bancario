<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Manual{
    
    public $codmanual;
    public $nome;
    public $arquivo;
    public $codbanco;
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
        $this->codempresa = $_SESSION["codempresa"];
        return $this->conexao->inserir("manual", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("manual", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("manual", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("manual", $this);
    }
    
}