<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Documento{
    
    public $coddocumento;
    public $codbanco;
    public $codempresa;
    public $nome;
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
        if(!isset($this->dtcadastro) || $this->dtcadastro == NULL || $this->dtcadastro == ""){
            $this->dtcadastro = date("Y-m-d H:i:s");
        }
        return $this->conexao->inserir("documento", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("documento", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("documento", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("documento", $this);
    }
    
    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from documento where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' order by dtcadastro");
    } 
    
}