<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Cargo{
    
    public $codcargo;
    public $codpessoa;
    public $dtcadastro;
    public $codempresa;
    private $conexao;    
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }

    public function inserir(){
        return $this->conexao->inserir("cargo", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("cargo", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("cargo", $this);
    }
    
    public function procuraCodigo($codcargo){
        return $this->conexao->comandoArray(("select * from cargo where codcargo = '{$codcargo}'cargo"));
    }
    
    public function procuraCodpessoa($codpessoa){
        return $this->conexao->comando("select * from cargo where codpessoa = '{$codpessoa}'cargo order by dtcadastro");
    } 

    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from cargo where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}'cargo order by dtcadastro");
    } 
    
}