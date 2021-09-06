<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Nivel{
    
    public $codnivel;
    public $nome;
    public $codempresa;
    public $codfuncionario;
    public $porcentagem;
    public $padrao;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        if(isset($this->porcentagem) && $this->porcentagem != NULL && $this->porcentagem > 0){
            $this->porcentagem = str_replace(",", ".", $this->porcentagem);
        }else{
            $this->porcentagem = "0.0";
        }  

        return $this->conexao->inserir("nivel", $this);
    }
    
    public function atualizar(){
        if(isset($this->porcentagem) && $this->porcentagem != NULL && $this->porcentagem > 0){
            $this->porcentagem = str_replace(",", ".", $this->porcentagem);
        }else{
            $this->porcentagem = "0.0";
        }        
    
        return $this->conexao->atualizar("nivel", $this);
    }  
    
    public function excluir($codnivel){
        $sql = "delete from nivel where codnivel = '{$codnivel}' and codempresa = '{$this->codempresa}'";
        return $this->conexao->comando($sql);
    }
    
    public function procuraCodigo($codnivel){
        return $this->conexao->comandoArray(("select * from nivel where codnivel = '{$codnivel}' and codempresa = '{$this->codempresa}'"));
    }
    
    public function procuraNome($nome){
        $sql = "select * from nivel where nome like '%{$nome}%' and codempresa = '{$this->codempresa}' order by nome";
        return $this->conexao->comando($sql);
    } 
    
    public function procuraNome2($nome){
        $sql = "select * from nivel where nome like '%{$nome}%' and codempresa = '{$this->codempresa}' and codnivel <> '1' order by nome";
        return $this->conexao->comando($sql);
    } 
    
}