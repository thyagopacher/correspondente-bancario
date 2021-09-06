<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Agenda{
    
    public $codagenda;
    public $codpessoa;
    public $dtcadastro;
    public $codfuncionario;
    public $dtagenda;
    public $codempresa;
    public $codstatus;
    public $observacao;
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
        if(!isset($this->codempresa) || $this->codempresa == NULL || $this->codempresa == ""){
            $this->codempresa = $_SESSION["codempresa"];
        }              
        if(!isset($this->codpessoa) || $this->codpessoa == NULL || $this->codpessoa == ""){
            $this->codpessoa = $_SESSION["codpessoa"];
        }         
        return $this->conexao->inserir("agenda", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("agenda", $this);
    }  
    
    public function excluir($codagenda){
        $sql = "delete from agenda where codagenda = '{$codagenda}' and codempresa = '{$this->codempresa}'";
        return $this->conexao->comando($sql);
    }
    
    public function procuraCodigo($codagenda){
        return $this->conexao->comandoArray(("select * from agenda where codagenda = '{$codagenda}' and codempresa = '{$this->codempresa}'"));
    }
    
    public function procuraCodpessoa($codpessoa){
        return $this->conexao->comando("select * from agenda where codpessoa = '{$codpessoa}' and codempresa = '{$this->codempresa}' order by dtcadastro");
    } 

    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from agenda where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' and codempresa = '{$this->codempresa}' order by dtcadastro");
    } 
    
}