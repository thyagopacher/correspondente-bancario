<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class ArquivoPessoa{
    
    public $codarquivo;
    public $nome;
    public $data;
    public $link;
    public $codempresa;
    public $codfuncionario;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }
    
    public function inserir(){
        if(!isset($this->data) || $this->data == NULL || $this->data == ""){
            $this->data = date("Ymd");
        }
        return $this->conexao->inserir("arquivopessoa", $this);
    }
    
    public function atualizar(){
        if(!isset($this->data) || $this->data == NULL || $this->data == ""){
            $this->data = date("Ymd");
        }  
        return $this->conexao->atualizar("arquivopessoa", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("arquivopessoa", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("arquivopessoa", $this);
    }

    public function procuraData($data1, $data2){
        return $this->conexao->comando("select * from arquivopessoa where data >= '{$data1}' and data <= '{$data2}' and codempresa='{$this->codempresa}' order by data");
    } 
    
}