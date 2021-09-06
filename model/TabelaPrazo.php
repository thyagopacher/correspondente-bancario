<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class TabelaPrazo{
    
    public $codtabelap;
    public $codtabela;
    public $prazode;
    public $prazoate;
    public $codfuncionario;
    public $dtcadastro;
    public $comissao;
    public $dtinicio;
    public $dtfim;
    public $bonus;
    public $rco;
    public $pgto_liquido;
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
        if(!isset($this->codfuncionario) || $this->codfuncionario == NULL || $this->codfuncionario == ""){
            $this->codfuncionario = $_SESSION["codpessoa"];
        }
        return $this->conexao->inserir("tabelaprazo", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("tabelaprazo", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("tabelaprazo", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("tabelaprazo", $this);
    }
    
   
    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from tabelaprazo where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' order by dtcadastro");
    } 
    
}