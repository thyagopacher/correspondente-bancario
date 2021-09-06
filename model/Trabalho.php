<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Trabalho{
    
    public $codtrabalho;
    public $empresa;
    public $codpessoa;
    public $local;
    public $dtcadastro;
    public $codempresa;
    public $codfuncionario;
    public $coddepartamento;
    public $matricula;
    public $salariobase;        
    public $codcargo;
    public $codtipo;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        return $this->conexao->comando("insert into trabalho(codpessoa, local, codempresa, codfuncionario, dtcadastro, coddepartamento, matricula, salariobase, codcargo, codtipo, empresa)
        values('{$this->codpessoa}', '{$this->local}', '{$this->codempresa}', 
        '{$this->codfuncionario}', '{$this->dtcadastro}', '{$this->coddepartamento}', '{$this->matricula}', '{$this->salariobase}', '{$this->codcargo}', '{$this->codtipo}', '{$this->empresa}');");
    }
    
    public function atualizar(){
        $setar = "";
        if(isset($this->empresa) && $this->empresa != NULL && trim($this->empresa) != ""){
            $setar .= ", empresa = '{$this->empresa}'";
        }
        if(isset($this->codtipo) && $this->codtipo != NULL && trim($this->codtipo) != ""){
            $setar .= ", codtipo = '{$this->codtipo}'";
        }
        if(isset($this->matricula) && $this->matricula != NULL && trim($this->matricula) != ""){
            $setar .= ", matricula = '{$this->matricula}'";
        }
        if(isset($this->salariobase) && $this->salariobase != NULL && trim($this->salariobase) != ""){
            $setar .= ", salariobase = '{$this->salariobase}'";
        }
        if(isset($this->codfuncionario) && $this->codfuncionario != NULL && trim($this->codfuncionario) != ""){
            $setar .= ", codfuncionario = '{$this->codfuncionario}'";
        }
        if(isset($this->coddepartamento) && $this->coddepartamento != NULL && trim($this->coddepartamento) != ""){
            $setar .= ", coddepartamento = '{$this->coddepartamento}'";
        }
        if(isset($this->dtcadastro) && $this->dtcadastro != NULL && trim($this->dtcadastro) != ""){
            $setar .= ", dtcadastro = '{$this->dtcadastro}'";
        }
        if(isset($this->codcargo) && $this->codcargo != NULL && trim($this->codcargo) != ""){
            $setar .= ", codcargo = '{$this->codcargo}'";
        }
        return $this->conexao->comando("update trabalho set local = '{$this->local}' {$setar}
        where codtrabalho = '{$this->codtrabalho}' and codempresa = '{$this->codempresa}';");
    }  
    
    public function excluir($codtrabalho){
        return $this->conexao->comando("delete from trabalho where codtrabalho = '{$codtrabalho}' and codempresa='{$this->codempresa}'");
    }
    
    public function procuraCodigo($codtrabalho){
        return $this->conexao->comandoArray(("select * from trabalho where codtrabalho = '{$codtrabalho}' and codempresa='{$this->codempresa}'"));
    }
    
    public function procuraData($data1, $data2){
        return $this->conexao->comando("select trabalho.*,  DATE_FORMAT(dtcadastro, '%d/%m/%Y') as dtcadastro2 from trabalho where dtcadastro >= '{$data1}' and dtcadastro <= '{$data2}' and codempresa='{$this->codempresa}' order by dtcadastro");
    } 
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from trabalho where codpessoa like '%{$nome}%' and codempresa='{$this->codempresa}' order by codpessoa");
    } 
    
}