<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class ObservacaoLigacao{
    
    public $codobservacao;
    public $codpessoa;
    public $dtcadastro;
    public $observacao;
    public $codempresa;
    public $codfuncionario;
    public $codstatus;
    private $conexao;   
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }
    
    public function inserir(){
        if(isset($this->observacao) && $this->observacao != NULL && $this->observacao != ""){
            $this->observacao = addslashes($this->observacao);
        }
        return $this->conexao->inserir("observacaoligacao", $this);
    }
    
    public function atualizar(){
        if(isset($this->observacao) && $this->observacao != NULL && $this->observacao != ""){
            $this->observacao = addslashes($this->observacao);
        }
        return $this->conexao->atualizar("observacaoligacao", $this);
    }  
    
    public function excluir($codobservacao){
        return $this->conexao->comando("delete from observacaocliente where codobservacao = '{$codobservacao}' and codempresa = '{$this->codempresa}'");
    }
    
    public function procuraCodigo($codobservacao){
        return $this->conexao->comandoArray("select * from observacaocliente where codobservacao = '{$codobservacao}' and codempresa = '{$this->codempresa}'");
    }
    
    public function procuraCodpessoa($codpessoa){
        return $this->conexao->comando("select * from observacaocliente where codpessoa = '{$codpessoa}' and codempresa = '{$this->codempresa}' order by dtcadastro");
    } 

    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from observacaocliente where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' and codempresa = '{$this->codempresa}' order by dtcadastro");
    } 
    
}