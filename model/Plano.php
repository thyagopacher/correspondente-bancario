<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Plano{
    
    public $codplano;
    public $nome;
    public $qtdfilial;
    public $qtdusuariomatriz;
    public $qtdusuariofilial;
    public $vlmensalidade;
    private $conexao;    
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }
    
    public function inserir(){
        return $this->conexao->inserir("plano", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("plano", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("plano", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("plano", $this);
    }

}