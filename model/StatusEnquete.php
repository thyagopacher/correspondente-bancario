<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class StatusEnquete{
    
    public $codstatus;
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
        return $this->conexao->comando("insert into statusenquete(nome, codempresa) values('{$this->nome}');");
    }
    
    public function atualizar(){
        return $this->conexao->comando("update statusenquete set nome = '{$this->nome}' where codstatus = '{$this->codstatus}';");
    }  
    
    public function excluir($codstatus){
        return $this->conexao->comando("delete from statusenquete where codstatus = '{$codstatus}'");
    }
    
    public function procuraCodigo($codstatus){
        return $this->conexao->comandoArray(("select * from statusenquete where codstatus = '{$codstatus}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from statusenquete where nome like '%{$nome}%' order by nome");
    } 
    
}