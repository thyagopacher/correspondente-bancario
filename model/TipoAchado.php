<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class TipoAchado{
    
    public $codtipo;
    public $nome;
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
        return $this->conexao->comando("insert into tipoachado(nome, codempresa, codfuncionario)
        values('{$this->nome}', '{$this->codempresa}', '{$this->codfuncionario}');");
    }
    
    public function atualizar(){
        $setar = "";
        if(isset($this->codfuncionario) && $this->codfuncionario != NULL && $this->codfuncionario != ""){
            $setar .= ", codfuncionario = '{$this->codfuncionario}'";
        }
        $sql = "update tipoachado set nome = '{$this->nome}' {$setar} where codtipo = '{$this->codtipo}' and codempresa = '{$this->codempresa}';";
        return $this->conexao->comando($sql);
    }  
    
    public function excluir($codtipo){
        return $this->conexao->comando("delete from tipoachado where codtipo = '{$codtipo}' and codempresa = '{$this->codempresa}'");
    }
    
    public function procuraCodigo($codtipo){
        return $this->conexao->comandoArray(("select * from tipoachado where codtipo = '{$codtipo}' and codempresa = '{$this->codempresa}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from tipoachado where nome like '%{$nome}%' and codempresa = '{$this->codempresa}' order by nome");
    } 
    
}