<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Frase{
    
    public $codfrase;
    public $dtcadastro;
    public $texto;
    public $codfuncionario;
    public $codempresa;
    public $popup;
    public $periodo;
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
        return $this->conexao->inserir("frase", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("frase", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("frase", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("frase", $this);
    }
    
   
    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from frase where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' order by dtcadastro");
    } 
    
}