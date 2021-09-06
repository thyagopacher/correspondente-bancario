<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class StatusCorrespondencia{
    
    public $codstatus;
    public $nome;
    public $padrao;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir($statuscorrespondencia){
        return $this->conexao->comando("insert into statuscorrespondencia(nome, padrao)
        values('{$statuscorrespondencia->nome}', '{$statuscorrespondencia->padrao}');");
    }
    
    public function atualizar($statuscorrespondencia){
        $sql = "update statuscorrespondencia set nome = '{$statuscorrespondencia->nome}', padrao = '{$statuscorrespondencia->padrao}' where codstatus = '{$statuscorrespondencia->codstatus}'";
        return $this->conexao->comando($sql);
    }  
    
    public function excluir($codstatus){
        return $this->conexao->comando("delete from statuscorrespondencia where codstatus = '{$codstatus}'");
    }
    
    public function procuraCodigo($codstatus){
        return $this->conexao->comandoArray(("select * from statuscorrespondencia where codstatus = '{$codstatus}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from statuscorrespondencia where nome like '%{$nome}%' order by nome");
    } 
    
}