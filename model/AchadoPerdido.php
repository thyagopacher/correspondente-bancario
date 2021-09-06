<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class AchadoPerdido{
    
    public $codachado;
    public $codpessoa;
    public $descricao;
    public $data;
    public $imagem;
    public $codempresa;
    public $entreguep;
    public $codpessoaentregue;
    public $codstatus;
    public $codtipo;
    public $local;
    private $conexao;
    
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }
    
    public function inserir(){
        return $this->conexao->comando("insert into achado(codpessoa, data, imagem, codempresa, descricao, codpessoaentregue, codstatus,  horacadastro, local, codtipo) 
        values('{$this->codpessoa}', '{$this->data}', '{$this->imagem}', '{$this->codempresa}', '{$this->descricao}', '{$this->codpessoaentregue}', '{$this->codstatus}', '".date('H:i:s')."', '{$this->local}', '{$this->codtipo}');");
    }
    
    public function atualizar(){
        $setar = "";
        if(isset($this->imagem) && $this->imagem != NULL && trim($this->imagem) != ""){
            $setar .= ", imagem = '{$this->imagem}'";
        }
        if(isset($this->codtipo) && $this->codtipo != NULL && trim($this->codtipo) != ""){
            $setar .= ", codtipo = '{$this->codtipo}'";
        }
        if(isset($this->codstatus) && $this->codstatus != NULL && trim($this->codstatus) != ""){
            $setar .= ", codstatus = '{$this->codstatus}'";
        }
        $sql = "update achado set codpessoa = '{$this->codpessoa}',
        data = '{$this->data}',  descricao = '{$this->descricao}', entreguep = '{$this->entreguep}',
        codpessoaentregue = '{$this->codpessoaentregue}', horacadastro = '{$this->horacadastro}',
        local = '{$this->local}' {$setar}
        where codachado = '{$this->codachado}' and codempresa = '{$this->codempresa}'";
        return $this->conexao->comando($sql);
    }  
    
    public function excluir($codachado){
        return $this->conexao->comando("delete from achado where codachado = '{$codachado}' and codempresa='{$this->codempresa}'");
    }
    
    public function procuraCodigo($codachado){
        return $this->conexao->comandoArray("select * from achado where codachado = '{$codachado}' and codempresa='{$this->codempresa}'");
    }
    
    public function procuraCodpessoa($codpessoa){
        return $this->conexao->comando("select * from achado where codpessoa = '{$codpessoa}' and codempresa='{$this->codempresa}' order by data");
    } 
    
    public function procuraData($data1, $data2){
        return $this->conexao->comando("select * from achado where data >= '{$data1}' and data <= '{$data2}' and codempresa='{$this->codempresa}' order by data");
    } 
    
}