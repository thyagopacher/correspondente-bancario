<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Site{
    
    public $codsite;
    public $email;
    public $telefone;
    public $celular;
    public $skype;
    public $facebook;
    public $descricao;
    public $nome;
    public $palavrachave;
    public $logo;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        return $this->conexao->comando("insert into site(email, telefone, celular, skype, facebook, descricao, nome, logo, palavrachave)
        values('{$this->email}', '{$this->telefone}', '{$this->celular}', '{$this->skype}', 
        '{$this->facebook}', '".date('Y-m-d')."', '{$this->nome}', '{$this->logo}', '{$this->palavrachave}');");
    }
    
    public function atualizar(){      
        $setar = "";
        if(isset($this->logo) && $this->logo != NULL && $this->logo != ""){
            $setar .= ", logo = '{$this->logo}'";
        }
        if(isset($this->celular) && $this->celular != NULL && $this->celular != ""){
            $setar .= ", celular = '{$this->celular}'";
        }
        if(isset($this->skype) && $this->skype != NULL && $this->skype != ""){
            $setar .= ", skype = '{$this->skype}'";
        }
        if(isset($this->telefone) && $this->telefone != NULL && $this->telefone != ""){
            $setar .= ", telefone = '{$this->telefone}'";
        }
        if(isset($this->palavrachave) && $this->palavrachave != NULL && $this->palavrachave != ""){
            $setar .= ", palavrachave = '{$this->palavrachave}'";
        }
        if(isset($this->descricao) && $this->descricao != NULL && $this->descricao != ""){
            $setar .= ", descricao = '{$this->descricao}'";
        }
        $sql = "update site set email = '{$this->email}' {$setar} where codsite = '{$this->codsite}' and facebook = '{$this->facebook}'";
        return $this->conexao->comando($sql);
    }  
    
    public function excluir($codsite){
        $sql = "delete from site where codsite = '{$codsite}'";
        return $this->conexao->comando($sql);
    }
    
    public function procuraCodigo($codsite){
        return $this->conexao->comandoArray(("select * from site where codsite = '{$codsite}'"));
    }

}