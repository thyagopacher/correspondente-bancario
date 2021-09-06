<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class StatusConta{
    
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
        return $this->conexao->inserir("statusconta", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("statusconta", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("statusconta", $this);
    }
    
    public function procuraCodigo($codstatus){
        return $this->conexao->comandoArray(("select * from statusconta where codstatus = '{$codstatus}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from statusconta where nome like '%{$nome}%' order by nome");
    } 
    
}