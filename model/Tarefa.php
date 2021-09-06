<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Tarefa{
    
    public $codtarefa;
    public $localizacao;
    public $descricao;
    public $prioridade;
    public $liberado;
    public $resolvido;
    public $dtcadastro;
    public $codempresa;
    public $codfuncionario;
    public $obsresolvido;
    public $imagem;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        if(!isset($tarefa->dtcadastro) || $tarefa->dtcadastro == NULL || $tarefa->dtcadastro == ""){
            $tarefa->dtcadastro = date("Ymd");
        }
        return $this->conexao->inserir("tarefa", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("tarefa", $this);
    }  
    
    public function excluir($codtarefa){
        return $this->conexao->comando("delete from tarefa where codtarefa = '{$codtarefa}' and codempresa='{$this->codempresa}'");
    }
    
    public function procuraCodigo($codtarefa){
        return $this->conexao->comandoArray(("select * from tarefa where codtarefa = '{$codtarefa}' and codempresa='{$this->codempresa}'"));
    }
    
    public function procuraData($liberado1, $liberado2){
        return $this->conexao->comando("select tarefa.*,  DATE_FORMAT(dtcadastro, '%d/%m/%Y') as dtcadastro2 from tarefa where dtcadastro >= '{$liberado1}' and dtcadastro <= '{$liberado2}' and codempresa='{$this->codempresa}' order by dtcadastro");
    } 
    
    public function procuraNome($localizacao){
        return $this->conexao->comando("select * from tarefa where localizacao like '%{$localizacao}%' and codempresa='{$this->codempresa}' order by localizacao");
    } 
    
}