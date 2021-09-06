<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class TipoInformativo{
    
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
    
    public function inserir($tipoinformativo){
        return $this->conexao->comando("insert into tipoinformativo(nome, codempresa) values('{$tipoinformativo->nome}', '{$tipoinformativo->codempresa}');");
    }
    
    public function atualizar($tipoinformativo){
        return $this->conexao->comando("update tipoinformativo set nome = '{$tipoinformativo->nome}' where codtipo = '{$tipoinformativo->codtipo}' and codempresa = '{$tipoinformativo->codempresa}';");
    }  
    
    public function excluir($codtipo){
        return $this->conexao->comando("delete from tipoinformativo where codtipo = '{$codtipo}' and codempresa='{$this->codempresa}'");
    }
    
    public function procuraCodigo($codtipo){
        return $this->conexao->comandoArray(("select * from tipoinformativo where codtipo = '{$codtipo}' and codempresa='{$this->codempresa}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from tipoinformativo where nome like '%{$nome}%' and codempresa='{$this->codempresa}' order by nome");
    } 
    
}