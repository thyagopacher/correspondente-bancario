<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Registro{
    
    public $codregistro;
    public $codigo;
    public $spp;
    public $procedimento;
    public $enviado;
    public $porte;
    public $tipo;
    public $paciente;
    public $dtcadastro;
    public $bilateral;
    public $incisao;
    public $valor;
    public $honorario;
    public $pessoa;
    public $data;
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
        $sql = "insert into registro(codigo, spp, procedimento, enviado, porte, tipo, paciente, 
        dtcadastro, bilateral, incisao, valor, honorario, codpessoa, data)
        values('{$this->codigo}', '{$this->spp}', '{$this->procedimento}', '{$this->enviado}', '{$this->porte}', '{$this->tipo}', 
        '{$this->paciente}', '{$this->dtcadastro}', '{$this->bilateral}', '{$this->incisao}', '{$this->valor}', '{$this->honorario}', '{$this->codpessoa}', '{$this->data}');";
        return $this->conexao->comando($sql);
    }
    
    public function atualizar(){
        $setar = "";
        if(isset($this->data) && $this->data != NULL && $this->data != ""){
            $setar .= ", data = '{$this->data}'";
        }
        if(isset($this->codpessoa) && $this->codpessoa != NULL && $this->codpessoa != ""){
            $setar .= ", codpessoa = '{$this->codpessoa}'";
        }
        if(isset($this->honorario) && $this->honorario != NULL && $this->honorario != ""){
            $setar .= ", honorario = '{$this->honorario}'";
        }
        if(isset($this->valor) && $this->valor != NULL && $this->valor != ""){
            $setar .= ", valor = '{$this->valor}'";
        }
        if(isset($this->incisao) && $this->incisao != NULL && $this->incisao != ""){
            $setar .= ", incisao = '{$this->incisao}'";
        }
        if(isset($this->bilateral) && $this->bilateral != NULL && $this->bilateral != ""){
            $setar .= ", bilateral = '{$this->bilateral}'";
        }
        if(isset($this->dtcadastro) && $this->dtcadastro != NULL && $this->dtcadastro != ""){
            $setar .= ", dtcadastro = '{$this->dtcadastro}'";
        }
        if(isset($this->codigo) && $this->codigo != NULL && $this->codigo != ""){
            $setar .= ", codigo = '{$this->codigo}'";
        }
        if(isset($this->spp) && $this->spp != NULL && $this->spp != ""){
            $setar .= ", spp = '{$this->spp}'";
        }
        if(isset($this->procedimento) && $this->procedimento != NULL && $this->procedimento != ""){
            $setar .= ", procedimento = '{$this->procedimento}'";
        }
        if(isset($this->enviado) && $this->enviado != NULL && $this->enviado != ""){
            $setar .= ", enviado = '{$this->enviado}'";
        }
        if(isset($this->porte) && $this->porte != NULL && $this->porte != ""){
            $setar .= ", porte = '{$this->porte}'";
        }
        if(isset($this->tipo) && $this->tipo != NULL && $this->tipo != ""){
            $setar .= ", tipo = '{$this->tipo}'";
        }
        $sql = "update registro set paciente = '{$this->paciente}' {$setar}
        where codregistro = '{$this->codregistro}'";
        return $this->conexao->comando($sql);
    }  
    
    public function excluir($codregistro){ 
        $sql = "delete from registro where codregistro = '{$codregistro}'";
        return $this->conexao->comando($sql);
    }
    
    public function procuraCodigo($codregistro){
        return $this->conexao->comandoArray(("select * from registro where codregistro = '{$codregistro}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from registro where paciente like '%{$nome}%' order by nome");
    } 
    
    public function procurarData($data1, $data2){
        return $this->conexao->comando("select * from registro where dtcadastro >= '{$data1}' and dtcadastro <= '{$data2}'");
    }
    
}