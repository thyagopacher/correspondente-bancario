<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class StatusPessoa{
    
    public $codstatus;
    public $nome;
    public $codfuncionario;
    public $dtcadastro;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        return $this->conexao->inserir("statuspessoa", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("statuspessoa", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("statuspessoa", $this);
    }
    
    public function procuraCodigo($codstatus){
        return $this->conexao->comandoArray(("select * from statuspessoa where codstatus = '{$codstatus}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from statuspessoa where nome like '%{$nome}%' order by nome");
    } 
    
}