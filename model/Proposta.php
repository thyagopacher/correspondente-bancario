<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Proposta{
    
    public $codproposta;
    public $nome;
    public $dtcadastro;
    public $codfuncionario;
    public $codempresa;
    public $codstatus;
    public $codtabela;
    public $codbanco;
    public $codconvenio;
    public $prazo;
    public $codcliente;
    public $vlsolicitado;
    public $vlparcela;
    public $vlliberado;
    public $codbeneficio;
    public $codbanco2;
    public $agencia;
    public $conta;
    public $operacao;
    public $observacao;
    public $poupanca;
    public $dtvenda;
    public $pendente;
    public $corrente;
    public $beneficio;
    public $cdespecie;
    public $codtabelap;
    public $dtpago;
    public $valor_contrato_comissao_empresa;
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
        return $this->conexao->inserir("proposta", $this);
    }
    
    public function atualizar(){      
        return $this->conexao->atualizar("proposta", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("proposta", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("proposta", $this);
    }
    
    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from proposta where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' order by dtcadastro");
    } 
    
}