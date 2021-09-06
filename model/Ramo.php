<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Ramo{
    
    public $codramo;
    public $nome;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir($ramo){
        return $this->conexao->comando("insert into ramo(nome) values('{$ramo->nome}');");
    }
    
    public function atualizar($ramo){
        return $this->conexao->comando("update ramo set nome = '{$ramo->nome}' where codramo = '{$ramo->codramo}';");
    }  
    
    public function excluir($codramo){
        return $this->conexao->comando("delete from ramo where codramo = '{$codramo}'");
    }
    
    public function procuraCodigo($codramo){
        return $this->conexao->comandoArray(("select * from ramo where codramo = '{$codramo}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from ramo where nome like '%{$nome}%' order by nome");
    } 
    
}