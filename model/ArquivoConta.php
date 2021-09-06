<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class ArquivoConta{
    
    public $codarquivo;
    public $nome;
    public $dtcadastro;
    public $link;
    public $codempresa;
    public $codfuncionario;
    public $codconta;
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
        return $this->conexao->inserir("arquivoconta",$this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("arquivoconta",$this);
    }  
    
    public function excluir($codarquivo){
        $sql = "delete from arquivoconta where codarquivo = '{$codarquivo}' and codempresa='{$this->codempresa}'";
        return $this->conexao->comando($sql);
    }
    
    public function procuraCodigo($codarquivo){
        return $this->conexao->comandoArray("select * from arquivoconta where codarquivo = '{$codarquivo}' and codempresa='{$this->codempresa}'");
    }
    
    public function procuraCodconta($nome){
        return $this->conexao->comando("select * from arquivoconta where nome = '{$nome}' and codempresa='{$this->codempresa}' order by dtcadastro");
    } 

    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from arquivoconta where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' and codempresa='{$this->codempresa}' order by dtcadastro");
    } 
    
}