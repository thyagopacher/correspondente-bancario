<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class CarteiraCliente{
    
    public $codcarteira;
    public $codcliente;
    public $codempresa;
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
        if(!isset($this->codempresa) || $this->codempresa == NULL || $this->codempresa == ""){
            $this->codempresa = $_SESSION["codempresa"];
        }
        if(!isset($this->codfuncionario) || $this->codfuncionario == NULL || $this->codfuncionario == ""){
            $this->codfuncionario = $_SESSION["codpessoa"];
        }        
        if(!isset($this->dtcadastro) || $this->dtcadastro == NULL || $this->dtcadastro == ""){
            $this->dtcadastro = date("Y-m-d H:i:s");
        }        
        return $this->conexao->inserir("carteiracliente", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("carteiracliente", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("carteiracliente", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("cargo", $this);
    }
    
    public function procuraCodpessoa($codpessoa){
        return $this->conexao->comando("select * from cargo where codpessoa = '{$codpessoa}'cargo order by dtcadastro");
    } 

    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from cargo where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}'cargo order by dtcadastro");
    } 
    
}