<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Modulo{
    
    public $codmodulo;
    public $nome;
    public $titulo;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir($modulo){
        return $this->conexao->comando("insert into modulo(nome, titulo) values('{$modulo->nome}', '{$modulo->titulo}');");
    }
    
    public function atualizar($modulo){
        return $this->conexao->comando("update modulo set nome = '{$modulo->nome}', titulo = '{$modulo->titulo}' where codmodulo = '{$modulo->codmodulo}';");
    }  
    
    public function excluir($codmodulo){
        return $this->conexao->comando("delete from modulo where codmodulo = '{$codmodulo}'");
    }
    
    public function procuraCodigo($codmodulo){
        return $this->conexao->comandoArray(("select * from modulo where codmodulo = '{$codmodulo}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from modulo where nome like '%{$nome}%' order by nome");
    } 
    
}