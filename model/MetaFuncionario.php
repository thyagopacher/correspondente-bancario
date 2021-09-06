<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class MetaFuncionario{
    
    public $codmeta;
    public $nome;
    public $site;
    public $dtcadastro;
    public $nummetafuncionario;
    public $codfuncionario;
    public $valor;
    public $dtinicio;
    public $dtfim;
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
        return $this->conexao->inserir("metafuncionario", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("metafuncionario", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("metafuncionario", $this);
    }
    
    public function procuraCodigo($codmeta){
        return $this->conexao->comandoArray(("select * from metafuncionario where codmeta = '{$codmeta}'"));
    }
    
   
    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from metafuncionario where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' order by dtcadastro");
    } 
    
}