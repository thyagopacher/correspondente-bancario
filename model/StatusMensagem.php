<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class StatusMensagem{
    
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
    
    public function inserir($statusmensagem){
        return $this->conexao->comando("insert into statusmensagem(nome) values('{$statusmensagem->nome}');");
    }
    
    public function atualizar($statusmensagem){
        return $this->conexao->comando("update statusmensagem set nome = '{$statusmensagem->nome}' where codstatus = '{$statusmensagem->codstatus}';");
    }  
    
    public function excluir($codstatus){
        return $this->conexao->comando("delete from statusmensagem where codstatus = '{$codstatus}'");
    }
    
    public function procuraCodigo($codstatus){
        return $this->conexao->comandoArray(("select * from statusmensagem where codstatus = '{$codstatus}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from statusmensagem where nome like '%{$nome}%' order by nome");
    } 
    
}