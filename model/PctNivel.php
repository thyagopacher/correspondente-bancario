<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class PctNivel{
    
    public $codpct;
    public $codnivel;
    public $codempresa;
    public $codfuncionario;
    public $dtcadastro;
    public $porcentagem;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        if(!isset($this->codempresa) || $this->codempresa == NULL || $this->codempresa == ""){
            $this->codempresa = $_SESSION["codempresa"];
        }
        if(!isset($this->codfuncionario) || $this->codfuncionario == NULL || $this->codfuncionario == ""){
            $this->codfuncionario = $_SESSION["codpessoa"];
        }
        if(!isset($this->dtcadastro) || $this->dtcadastro == NULL || $this->dtcadastro == ""){
            $this->dtcadastro = date("Y-m-d H:i:s");
        }
        return $this->conexao->inserir("pctnivel", $this);
    }
    
    public function atualizar(){      
        return $this->conexao->atualizar("pctnivel", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("pctnivel", $this);
    }
    
}