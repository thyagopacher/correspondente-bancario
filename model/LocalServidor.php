<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class LocalServidor{
    
    public $codlocal;
    public $nome;
    public $dtcadastro;
    private $conexao;    
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }
    
    public function inserir(){
        return $this->conexao->comando("insert into localservidor(nome, dtcadastro) 
        values('{$this->nome}', '{$this->dtcadastro}');");
    }
    
    public function atualizar(){
        return $this->conexao->comando("update localservidor set nome = '{$this->nome}',
        dtcadastro = '{$this->dtcadastro}' where codlocal = '{$this->codlocal}'");
    }  
    
    public function excluir($codlocal){
        return $this->conexao->comando("delete from localservidor where codlocal = '{$codlocal}'");
    }
    
    public function procuraCodigo($codlocal){
        return $this->conexao->comandoArray(("select * from localservidor where codlocal = '{$codlocal}' order by nome"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from localservidor where nome like '%{$nome}%' order by nome");
    } 

    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from localservidor where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' order by dtcadastro");
    } 
    
}