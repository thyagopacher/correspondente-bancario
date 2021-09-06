<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Empresa{
    
    public $codempresa;
    public $razao;
    public $fantasia;
    public $cnpj;
    public $email;
    public $telefone;
    public $celular;
    public $cep;
    public $tipologradouro;
    public $logradouro;
    public $numero;
    public $bairro;
    public $cidade;
    public $uf;
    public $codramo;
    public $logo;
    public $dtcadastro;
    public $codstatus;
    public $codpessoa;
    public $nomecontato;
    public $observacao;
    public $porcentagem;
    public $pctsupervisao;
    
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
        if(isset($this->porcentagem) && $this->porcentagem != NULL && $this->porcentagem != ""){
            $this->porcentagem = str_replace(",", ".", $this->porcentagem);
        }else{
            $this->porcentagem = "0.0";
        } 
        return $this->conexao->inserir("empresa", $this);
    }
    
    public function atualizar(){
        if(isset($this->porcentagem) && $this->porcentagem != NULL && $this->porcentagem != ""){
            $this->porcentagem = str_replace(",", ".", $this->porcentagem);
        }else{
            $this->porcentagem = "0.0";
        }         
        return $this->conexao->atualizar("empresa", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("empresa", $this);
    }
    
    public function procuraCodigo($codempresa){
        return $this->conexao->comandoArray(("select * from empresa where codempresa = '{$codempresa}'"));
    }
    
    public function procuraNome($razao){
        return $this->conexao->comando("select * from empresa where razao like '%{$razao}%' order by razao");
    } 
    
}