<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class ObservacaoProposta{
    
    public $codobservacao;
    public $observacao;
    public $codstatus;
    public $codcliente;
    public $codfuncionario;
    public $dtcadastro;
    public $codempresa;
    public $codbanco;
    public $codconvenio;
    public $codtabela;
    public $prazo;
    public $valor;
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
        return $this->conexao->inserir("observacaoproposta", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("observacaoproposta", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("observacaoproposta", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("observacaoproposta", $this);
    }
    
}