<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Baixa{
    
    public $codbaixa;
    public $codempresa;
    public $dtcadastro;
    public $codfuncionario;
    public $cpf;
    public $valor;
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
        return $this->conexao->inserir("baixa", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("baixa", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("baixa", $this);
    }
    
    public function procuraCodigo($codbaixa){
        return $this->conexao->comandoArray(("select * from baixa where codbaixa = '{$codbaixa}'"));
    }
    
   
    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from baixa where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' order by dtcadastro");
    } 
    
}