<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Especie{
    
    public $codespecie;
    public $nome;
    public $numinss;
    public $dtcadastro;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        if(!isset($this->dtcadastro) || $this->dtcadastro == NULL || $this->dtcadastro == ""){
            $this->dtcadastro = date("Y-m-d H:i:s");
        }        
        return $this->conexao->inserir("especie", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("especie", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("especie", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("especie", $this);
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from especie where nome like '%{$nome}%' order by nome");
    } 
    
}