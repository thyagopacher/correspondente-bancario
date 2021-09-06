<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Coeficiente{
    
    public $codcoeficiente;
    public $valor;
    public $dtcadastro;
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
        if(!isset($this->codempresa) || $this->codempresa == NULL || $this->codempresa == ""){
            $this->codempresa = $_SESSION["codempresa"];
        }              
        if(!isset($this->codfuncionario) || $this->codfuncionario == NULL || $this->codfuncionario == ""){
            $this->codfuncionario = $_SESSION["codpessoa"];
        } 
        return $this->conexao->inserir("coeficiente", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("coeficiente", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("coeficiente", $this);;
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("coeficiente", $this);
    }
    
    public function procuraCoeficienteHoje(){
        $sql = "select coeficiente.* 
        from coeficiente
        inner join empresa on empresa.codempresa = coeficiente.codempresa
        where (empresa.codempresa = {$_SESSION["codempresa"]} or empresa.codpessoa in(select codpessoa from pessoa where codempresa = {$_SESSION["codempresa"]}))
        and   coeficiente.dtcadastro >= '".date('Y-m-d')." 00:00:00' 
        and   coeficiente.dtcadastro <= '".date('Y-m-d')." 23:59:59'";
        return $this->conexao->comandoArray($sql);
    }
    
    public function procuraData($data1, $data2){
        return $this->conexao->comando("select * from coeficiente where dtcadastro >= '{$data1}' and dtcadastro <= '{$data2}' order by data");
    } 
    
}