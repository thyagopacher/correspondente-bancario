<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Banco{
    
    public $codbanco;
    public $nome;
    public $site;
    public $dtcadastro;
    public $numbanco;
    public $codfuncionario;
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
        return $this->conexao->inserir("banco", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("banco", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("banco", $this);
    }
    
    public function procuraCodigo($codbanco){
        return $this->conexao->comandoArray(("select * from banco where codbanco = '{$codbanco}'"));
    }
    
   
    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from banco where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' order by dtcadastro");
    } 
    
}