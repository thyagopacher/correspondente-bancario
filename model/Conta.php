<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Conta{
    
    public $codconta;
    public $nome;
    public $valor;
    public $codtipo;
    public $data;
    public $movimentacao;
    public $dtcadastro;
    public $codempresa;
    public $codfuncionario;
    public $arquivo;
    public $codambiente;
    public $codstatus;
    public $dtpagamento;
    public $observacao;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        if(!isset($this->dtcadastro) || $this->dtcadastro == NULL || $this->dtcadastro == ""){
            $this->dtcadastro = date("Ymd");
        }
        return $this->conexao->inserir("conta", $this);
    }
    
    public function atualizar(){
        if(!isset($this->dtpagamento) || $this->dtpagamento == NULL || $this->dtpagamento == ""){
            $this->dtpagamento = " ";
        }
        return $this->conexao->atualizar("conta", $this);
    }  
    
    public function excluir($codconta){
        $this->codconta = $codconta;
        return $this->conexao->excluir("conta", $this);
    }
    
    public function procuraCodigo($codconta){
        return $this->conexao->comandoArray(("select * from conta where codconta = '{$codconta}' and codempresa='{$this->codempresa}'"));
    }
    
    public function procuraData($data1, $data2){
        return $this->conexao->comando("select conta.*,  DATE_FORMAT(data, '%d/%m/%Y') as data2 from conta where data >= '{$data1}' and data <= '{$data2}' and codempresa='{$this->codempresa}' order by data");
    } 
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from conta where nome like '%{$nome}%' and codempresa='{$this->codempresa}' order by nome");
    } 
    
}