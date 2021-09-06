<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Conexao;

Class Importacao{
    
    public $codimportacao;
    public $codcarteira;
    public $data;
    public $codfuncionario;
    public $codempresa;
    public $qtdimportado;
    public $qtdnimportado;
    public $terminado;
    public $arquivo;
    public $qtdlinha;
    public $adicionar_carteira;
    public $atualizar_cliente;
    public $categoriacliente;
    private $conexao;
    
    public function __construct($conn = NULL) {
        if($conn == NULL){
            $conn = new Conexao();
        }
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }
    
    public function inserir(){
        if(!isset($this->codempresa) || $this->codempresa == NULL || $this->codempresa == ""){
            $this->codempresa = $_SESSION["codempresa"];
        }
        if(!isset($this->codfuncionario) || $this->codfuncionario == NULL || $this->codfuncionario == ""){
            $this->codfuncionario = $_SESSION["codpessoa"];
        }
        if(!isset($this->data) || $this->data == NULL || $this->data == ""){
            $this->data = date("Y-m-d H:i:s");
        }
        return $this->conexao->inserir("importacao", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("importacao", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("importacao", $this);
    }
    
    public function excluirCarteira($codcarteira){
        return $this->conexao->comando("delete from importacao where codcarteira = '{$codcarteira}'");
    }
    
    public function procuraCodigo($codimportacao){
        return $this->conexao->comandoArray(("select * from importacao where codimportacao = '{$codimportacao}' and codempresa = '{$this->codempresa}'"));
    }

    public function procuraData($data1, $data2){
        return $this->conexao->comando("select * from importacao where data >= '{$data1}' and data <= '{$data2}' and codempresa = '{$this->codempresa}' order by data");
    } 
    
}