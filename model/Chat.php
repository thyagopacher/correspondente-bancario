<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Chat{
    
    public $codchat;
    public $codpessoa1;
    public $codpessoa2;
    public $codempresa;
    public $dtcadastro;
    public $texto;    
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
        return $this->conexao->inserir("chat", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("chat", $this);
    }  
    
    public function excluir($codchat){
        return $this->conexao->comando("delete from chat where codchat = '{$codchat}' and codempresa = '{$this->codempresa}'");
    }
    
    public function procuraCodigo($codchat){
        return $this->conexao->comandoArray(("select * from chat where codchat = '{$codchat}' and codempresa = '{$this->codempresa}'"));
    }

    public function procuraDtcadastro($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from chat where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' and codempresa = '{$this->codempresa}' order by dtcadastro");
    } 
    
}