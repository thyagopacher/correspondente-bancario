<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Emprestimo{
    
    public $codemprestimo;
    public $codpessoa;
    public $dtcadastro;
    public $codfuncionario;
    public $codempresa;
    public $codbanco;
    public $dtparcela;
    public $vlparcela;
    public $prazo;
    public $quitacao;
    public $contacorrente;
    public $agencia;
    public $codbeneficio;
    public $meio;
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
        return $this->conexao->inserir("emprestimo", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("emprestimo", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("emprestimo", $this);
    }
    
    public function procuraCodigo($codemprestimo){
        return $this->conexao->comandoArray(("select * from emprestimo where codemprestimo = '{$codemprestimo}' and codempresa = '{$this->codempresa}'"));
    }
    
    public function procuraCodpessoa($codpessoa){
        return $this->conexao->comando("select * from emprestimo where codpessoa = '{$codpessoa}' and codempresa = '{$this->codempresa}' order by dtcadastro");
    } 
    
    public function procuraEmprestimoPessoaHoje($codpessoa){
        return $this->conexao->comandoArray("select * from emprestimo where codpessoa = '{$codpessoa}' and dtcadastro = CURRENT_DATE() and codempresa = '{$this->codempresa}' order by dtcadastro");
    }
    
    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from emprestimo where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' and codempresa = '{$this->codempresa}' order by dtcadastro");
    } 
    
}