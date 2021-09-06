<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Servico{
    
    public $codservico;
    public $nome;
    public $valor;
    public $codfuncionario;
    public $codempresa;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        return $this->conexao->comando("insert into servico(nome, valor, codempresa, codfuncionario) values('{$this->nome}', '{$this->valor}', '{$this->codempresa}', '{$this->codfuncionario}');");
    }
    
    public function atualizar(){
        return $this->conexao->comando("update servico set nome = '{$this->nome}',
        valor = '{$this->valor}', codfuncionario = '{$this->codfuncionario}' where codservico = '{$this->codservico}' and codempresa = '{$this->codempresa}';");
    }  
    
    public function excluir($codservico){
        return $this->conexao->comando("delete from servico where codservico = '{$codservico}' and codempresa='{$this->codempresa}'");
    }
    
    public function procuraCodigo($codservico){
        return $this->conexao->comandoArray(("select * from servico where codservico = '{$codservico}' and codempresa='{$this->codempresa}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from servico where nome like '%{$nome}%' and codempresa='{$this->codempresa}' order by nome");
    } 
    
}