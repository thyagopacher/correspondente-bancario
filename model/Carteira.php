<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Conexao;

Class Carteira{
    
    public $codcarteira;
    public $codfuncionario;
    public $dtcadastro;
    public $codempresa;
    public $nome;
    private $conexao;    
    
    public function __construct($conn = NULL) {
        if($conn == NULL){
            $conn = new Conexao();
        }
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
        return $this->conexao->inserir("carteira", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("carteira", $this);
    }  
    
    public function excluir($codcarteira){
        $this->codcarteira = $codcarteira;
        return $this->conexao->excluir("carteira", $this);
    }
    
    public function procuraCodigo($codcarteira){
        $this->codcarteira = $codcarteira;
        return $this->conexao->procurarCodigo("carteira", $this);
    }
   
}