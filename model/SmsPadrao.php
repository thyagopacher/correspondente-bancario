<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class SmsPadrao{
    
    public $codsmspadrao;
    public $texto;
    public $codfuncionario;
    public $codempresa;
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
        return $this->conexao->inserir("smspadrao", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("smspadrao", $this);;
    }  
    
    public function excluir($codstatus){
        return $this->conexao->comando("delete from smspadrao where codsmspadrao = '{$codstatus}'");
    }
    
    public function procuraCodigo($codstatus){
        return $this->conexao->comandoArray(("select * from smspadrao where codsmspadrao = '{$codstatus}'"));
    }
    
    public function procuraNome($texto){
        return $this->conexao->comando("select * from smspadrao where texto like '%{$texto}%' order by texto");
    } 
    
}