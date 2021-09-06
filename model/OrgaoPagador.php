<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class OrgaoPagador{
    
    public $codorgao;
    public $nome;
    public $dtcadastro;
    public $codfuncionario;
    private $conexao;    
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }
    
    public function inserir(){
        return $this->conexao->inserir("orgaopagador", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("orgaopagador", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("orgaopagador", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("orgaopagador", $this);
    }
    
}