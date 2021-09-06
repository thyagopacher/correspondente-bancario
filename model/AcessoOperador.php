<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class AcessoOperador{
    
    public $codacesso;
    public $codoperador;
    public $dtcadastro;
    public $codcarteira;
    public $codempresa;
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
        return $this->conexao->inserir("acessooperador", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("acessooperador", $this);
    }  
    
    public function excluir($codacesso){
        $this->codacesso = $codacesso;
        return $this->conexao->excluir("acessooperador", $this);
    }
    
    public function procuraCodigo($codacesso){
        $this->codacesso = $codacesso;
        return $this->conexao->procurarCodigo("acessooperador", $this);
    }
   
}