<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Pagina{
    
    public $codpagina;
    public $nome;
    public $link;
    public $titulo;
    public $codmodulo;
    public $icone;
    public $abreaolado;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        return $this->conexao->inserir("pagina", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("pagina", $this);
    }  
    
    public function excluir($codpagina){
        return $this->conexao->comando("delete from pagina where codpagina = '{$codpagina}'");
    }
    
    public function procuraCodigo($codpagina){
        return $this->conexao->comandoArray(("select * from pagina where codpagina = '{$codpagina}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from pagina where nome like '%{$nome}%' order by nome");
    } 
    
}