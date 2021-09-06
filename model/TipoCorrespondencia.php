<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class TipoCorrespondencia{
    
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
    
    public function inserir($tipocorrespondencia){
        return $this->conexao->comando("insert into tipocorrespondencia(nome, codempresa) values('{$tipocorrespondencia->nome}', '{$tipocorrespondencia->codempresa}');");
    }
    
    public function atualizar($tipocorrespondencia){
        return $this->conexao->comando("update tipocorrespondencia set nome = '{$tipocorrespondencia->nome}' where codtipo = '{$tipocorrespondencia->codtipo}' and codempresa = '{$tipocorrespondencia->codempresa}';");
    }  
    
    public function excluir($codtipo){
        return $this->conexao->comando("delete from tipocorrespondencia where codtipo = '{$codtipo}' and codempresa='{$this->codempresa}'");
    }
    
    public function procuraCodigo($codtipo){
        return $this->conexao->comandoArray(("select * from tipocorrespondencia where codtipo = '{$codtipo}' and codempresa='{$this->codempresa}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from tipocorrespondencia where nome like '%{$nome}%' and codempresa='{$this->codempresa}' order by nome");
    } 
    
}