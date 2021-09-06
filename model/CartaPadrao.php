<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class CartaPadrao{
    
    public $codcartapadrao;
    public $assunto;
    public $texto;
    public $codfuncionario;
    public $codempresa;
    public $dtcadastro;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        return $this->conexao->comando("insert into cartapadrao(assunto, texto, codfuncionario, codempresa, dtcadastro)
        values('{$this->assunto}', '{$this->texto}', '{$this->codfuncionario}', '{$this->codempresa}', '{$this->dtcadastro}');");
    }
    
    public function atualizar(){
        $setar = "";
        if(isset($this->assunto) && $this->assunto != NULL && $this->assunto != ""){
            $setar .= ", assunto = '{$this->assunto}'";
        }
        if(isset($this->dtcadastro) && $this->dtcadastro != NULL && $this->dtcadastro != ""){
            $setar .= ", dtcadastro = '{$this->dtcadastro}'";
        }
        if(isset($this->codfuncionario) && $this->codfuncionario != NULL && $this->codfuncionario != ""){
            $setar .= ", codfuncionario = '{$this->codfuncionario}'";
        }
        $sql = "update cartapadrao set texto = '{$this->texto}' {$setar} where codcartapadrao = '{$this->codcartapadrao}';";
        return $this->conexao->comando($sql);
    }  
    
    public function excluir($codstatus){
        return $this->conexao->comando("delete from cartapadrao where codcartapadrao = '{$codstatus}'");
    }
    
    public function procuraCodigo($codstatus){
        return $this->conexao->comandoArray(("select * from cartapadrao where codcartapadrao = '{$codstatus}'"));
    }
    
    public function procuraNome($texto){
        return $this->conexao->comando("select * from cartapadrao where texto like '%{$texto}%' order by texto");
    } 
    
}