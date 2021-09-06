<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Classificado{
    
    public $codclassificado;
    public $data;
    public $titulo;
    public $codpessoa;
    public $texto;
    public $arquivo;
    public $ehMorador;
    public $valor;
    public $codfornecedor;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        return $this->conexao->comando("insert into classificado(data, titulo, codpessoa, texto, arquivo, ehMorador, codempresa, valor, codfornecedor)
        values('{$this->data}', '{$this->titulo}', '{$this->codpessoa}',
        '".addslashes($this->texto)."', '{$this->arquivo}', '{$this->ehMorador}', '{$this->codempresa}', '{$this->valor}', '{$this->codfornecedor}');");
    }
    
    public function atualizar(){
        $setar = "";
        if(isset($this->arquivo) && $this->arquivo != NULL && $this->arquivo != ""){
            $setar .= ", arquivo = '{$this->arquivo}'";
        }
        if(isset($this->data) && $this->data != NULL && $this->data != ""){
            $setar .= ", data = '{$this->data}'";
        }
        if(isset($this->valor) && $this->valor != NULL && $this->valor != ""){
            $setar .= ", valor = '{$this->valor}'";
        }
        if(isset($this->codpessoa) && $this->codpessoa != NULL && $this->codpessoa != ""){
            $setar .= ", codpessoa = '{$this->codpessoa}'";
        }
        if(isset($this->codfornecedor) && $this->codfornecedor != NULL && $this->codfornecedor != ""){
            $setar .= ", codfornecedor = '{$this->codfornecedor}'";
        }
        $sql = "update classificado set titulo = '{$this->titulo}',
        texto = '".addslashes($this->texto)."', ehMorador = '{$this->ehMorador}' {$setar}
        where codclassificado = '{$this->codclassificado}' and codempresa = '{$this->codempresa}';";
        return $this->conexao->comando($sql);
    }  
    
    public function excluir($codclassificado){
        return $this->conexao->comando("delete from classificado where codclassificado = '{$codclassificado}'");
    }
    
    public function procuraCodigo($codclassificado){
        return $this->conexao->comandoArray(("select * from classificado where codclassificado = '{$codclassificado}'"));
    }
    
    public function procuraData($data1, $data2){
        return $this->conexao->comando("select classificado.*,  DATE_FORMAT(data, '%d/%m/%Y') as data2 from classificado where data >= '{$data1}' and data <= '{$data2}' order by data");
    } 
    
    public function procuraNome($titulo){
        return $this->conexao->comando("select * from classificado where titulo like '%{$titulo}%' order by data");
    } 
    
}