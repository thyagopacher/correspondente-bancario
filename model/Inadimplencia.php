<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Inadimplencia{
    
    public $codinadimplencia;
    public $codempresa;
    public $codfuncionario;    
    public $dtcadastro;
    public $dtpagamento;
    public $bloco;
    public $periodo;
    public $apartamento;
    public $cotacondominio;
    public $fundoreserva;
    public $rateioagua;
    public $txextra1;
    public $txextra2;
    public $dtvencimento;
    public $juro;
    public $multa;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        if(!isset($this->dtcadastro) || $this->dtcadastro == NULL || $this->dtcadastro ==  ""){
            $this->dtcadastro = date("Y-m-d H:i:s");
        }
        if(!isset($this->dtpagamento) || $this->dtpagamento == NULL || $this->dtpagamento ==  ""){
            $this->dtpagamento = date("Ymd");
        }
        if(!isset($this->fundoreserva) || $this->fundoreserva == NULL || $this->fundoreserva ==  ""){
            $this->fundoreserva = date('H:i:s');
        }
        $sql = "insert into inadimplencia(bloco, dtpagamento, codempresa, codfuncionario, dtcadastro, 
        apartamento, cotacondominio, fundoreserva, rateioagua, txextra1, txextra2, juro, multa, periodo, dtvencimento)
        values('{$this->bloco}', '{$this->dtpagamento}', '{$this->codempresa}', 
        '{$this->codfuncionario}', '{$this->dtcadastro}', '{$this->apartamento}', '{$this->cotacondominio}', '{$this->fundoreserva}',
        '{$this->rateioagua}', '{$this->txextra1}', '{$this->txextra2}', '{$this->juro}', '{$this->multa}', '{$this->periodo}',
        '{$this->dtvencimento}');";
        return $this->conexao->comando($sql);
    }
    
    public function atualizar(){
        $setar = ""; 
        if(isset($this->dtvencimento) && $this->dtvencimento != NULL && $this->dtvencimento != ""){
            $setar .= ", dtvencimento = '{$this->dtvencimento}'";
        }
        if(isset($this->dtcadastro) && $this->dtcadastro != NULL && $this->dtcadastro != ""){
            $setar .= ", dtcadastro = '{$this->dtcadastro}'";
        }
        if(isset($this->fundoreserva) && $this->fundoreserva != NULL && $this->fundoreserva != ""){
            $setar .= ", fundoreserva = '{$this->fundoreserva}'";
        }
        if(isset($this->rateioagua) && $this->rateioagua != NULL && $this->rateioagua != ""){
            $setar .= ", rateioagua = '{$this->rateioagua}'";
        }
        if(isset($this->txextra1) && $this->txextra1 != NULL && $this->txextra1 != ""){
            $setar .= ", txextra1 = '{$this->txextra1}'";
        }
        if(isset($this->txextra2) && $this->txextra2 != NULL && $this->txextra2 != ""){
            $setar .= ", txextra2 = '{$this->txextra2}'";
        }
        if(isset($this->juro) && $this->juro != NULL && $this->juro != ""){
            $setar .= ", juro = '{$this->juro}'";
        }
        if(isset($this->multa) && $this->multa != NULL && $this->multa != ""){
            $setar .= ", multa = '{$this->multa}'";
        }
        if(isset($this->apartamento) && $this->apartamento != NULL && $this->apartamento != ""){
            $setar .= ", apartamento = '{$this->apartamento}'";
        }
        if(isset($this->bloco) && $this->bloco != NULL && $this->bloco != ""){
            $setar .= ", bloco = '{$this->bloco}'";
        }
        if(isset($this->codfuncionario) && $this->codfuncionario != NULL && $this->codfuncionario != ""){
            $setar .= ", codfuncionario = '{$this->codfuncionario}'";
        }
        if(isset($this->periodo) && $this->periodo != NULL && $this->periodo != ""){
            $setar .= ", periodo = '{$this->periodo}'";
        }
        $sql = "update inadimplencia set dtpagamento = '{$this->dtpagamento}' {$setar}
        where codinadimplencia = '{$this->codinadimplencia}' and codempresa = '{$this->codempresa}';";
        return $this->conexao->comando($sql);
    }  
    
    public function excluir($codinadimplencia){
        $sql = "delete from inadimplencia where codinadimplencia = '{$codinadimplencia}' and codempresa='{$this->codempresa}'";
        return $this->conexao->comando($sql);
    }
    
    public function procuraCodigo($codinadimplencia){
        return $this->conexao->comandoArray("select * from inadimplencia where codinadimplencia = '{$codinadimplencia}' and codempresa='{$this->codempresa}'");
    }
    
    public function procuraApto(){
        return $this->conexao->comandoArray("select * from inadimplencia where bloco = '{$this->bloco}' and apartamento = '{$this->apartamento}'");
    } 
    
    public function procuraDtpagamento($dtpagamento1, $dtpagamento2){
        return $this->conexao->comando("select * from inadimplencia where dtpagamento >= '{$dtpagamento1}' and dtpagamento <= '{$dtpagamento2}' and codempresa='{$this->codempresa}' order by bloco, apartamento");
    } 
    
}