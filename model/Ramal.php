<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Ramal{
    
    public $codramal;
    public $ramal;
    public $nome;
    public $local;
    public $telefone;
    public $dtcadastro;
    public $codempresa;
    public $externo;
    public $ativo;
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
        return $this->conexao->comando("insert into ramal(ramal, nome, local, telefone, dtcadastro, codempresa, externo, ativo)
        values('{$this->ramal}', '{$this->nome}', '{$this->local}', '{$this->telefone}', '{$this->dtcadastro}', '{$this->codempresa}', 
        '{$this->externo}', '{$this->ativo}');");
    }
    
    public function atualizar(){
        $setar = "";
        if(isset($this->ativo) && $this->ativo != NULL && $this->ativo != ""){
            $setar .= ", ativo = '{$this->ativo}'";
        }
        if(isset($this->externo) && $this->externo != NULL && $this->externo != ""){
            $setar .= ", externo = '{$this->externo}'";
        }
        return $this->conexao->comando("update ramal set ramal = '{$this->ramal}', nome = '{$this->nome}', local = '{$this->local}', telefone = '{$this->telefone}'
        {$setar} where codramal = '{$this->codramal}' and codempresa = '{$this->codempresa}';");
    }  
    
    public function excluir($codramal){
        return $this->conexao->comando("delete from ramal where codramal = '{$codramal}' and codempresa='{$this->codempresa}'");
    }
    
    public function procuraCodigo($codramal){
        return $this->conexao->comandoArray(("select * from ramal where codramal = '{$codramal}' and codempresa='{$this->codempresa}'"));
    }
    
    public function procuraNome($local){
        return $this->conexao->comando("select * from ramal where local like '%{$local}%' and codempresa='{$this->codempresa}' order by ramal");
    } 

    public function soNumero($str) {
        return preg_replace("/[^0-9]/", "", $str);
    }    
    
    public function identificaCelular($numero) {
        $numero = $this->soNumero($numero);
        if(strlen($numero) >= 8 && strlen($numero) <= 9){
            if ($numero{0} == 7 || $numero{0} == 8 || $numero{0} == 9) {
                return true;
            } elseif ($numero{0} == 2 || $numero{0} == 3 || $numero{0} == 4) {
                return false;
            }
        }elseif(strlen($numero) > 9 && $numero{0} != 0){
            
            if ($numero{2} == 7 || $numero{2} == 8 || $numero{2} == 9) {
                return true;
            } elseif ($numero{2} == 2 || $numero{2} == 3 || $numero{2} == 4) {
                return false;
            }            
        }elseif($numero{0} == 0){
            if ($numero{3} == 7 || $numero{3} == 8 || $numero{3} == 9) {
                return true;
            } elseif ($numero{3} == 2 || $numero{3} == 3 || $numero{3} == 4) {
                return false;
            }            
        }
    }    
    
    public function mask($val, $mask = "(##)####-####") {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }
        return $maskared;
    }        
        
}