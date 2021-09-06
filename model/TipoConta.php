<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class TipoConta{
    
    public $codtipo;
    public $nome;
    public $codempresa;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        return $this->conexao->inserir("tipoconta", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("tipoconta", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("tipoconta", $this);
    }
    
    public function procuraCodigo($codtipo){
        return $this->conexao->comandoArray(("select * from tipoconta where codtipo = '{$codtipo}' and codempresa = '{$this->codempresa}'"));
    }
    
    public function procuraNome($nome){
        $sql = "select * from tipoconta where nome like '%{$nome}%' and codempresa = '{$this->codempresa}' order by nome";
        return $this->conexao->comando($sql);
    } 
    
}