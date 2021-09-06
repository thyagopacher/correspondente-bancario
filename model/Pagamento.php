<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Pagamento{
    
    public $codpagamento;
    public $codbanco;
    public $agencia;
    public $conta;
    public $operacao;
    public $codcliente;
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
        return $this->conexao->inserir("pagamento", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("pagamento", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("pagamento", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("pagamento", $this);
    }

}