<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class StatusAchado{
    
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
    
    public function inserir($statusachado){
        return $this->conexao->comando("insert into statusachado(nome, padrao)
        values('{$statusachado->nome}', '{$statusachado->padrao}');");
    }
    
    public function atualizar($statusachado){
        $sql = "update statusachado set nome = '{$statusachado->nome}', padrao = '{$statusachado->padrao}' where codstatus = '{$statusachado->codstatus}';";
        return $this->conexao->comando($sql);
    }  
    
    public function excluir($codstatus){
        return $this->conexao->comando("delete from statusachado where codstatus = '{$codstatus}'");
    }
    
    public function procuraCodigo($codstatus){
        return $this->conexao->comandoArray(("select * from statusachado where codstatus = '{$codstatus}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from statusachado where nome like '%{$nome}%' order by nome");
    } 
    
}