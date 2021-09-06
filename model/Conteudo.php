<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Conteudo{
    
    public $codconteudo;
    public $codpessoa;
    public $dtcadastro;
    public $nome;
    public $descricao;
    public $palavrachave;
    public $horasaida;
    public $dtcadastrosaida;
    public $codempresa;
    public $video;
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
        return $this->conexao->inserir("conteudo", $this);
    }
    
    public function atualizar(){       
        return $this->conexao->atualizar("conteudo", $this);
    }  
    
    public function excluir($codconteudo){
        return $this->conexao->comando("delete from conteudo where codconteudo = '{$codconteudo}'");
    }
    
    public function procuraCodigo($codconteudo){
        return $this->conexao->comandoArray(("select * from conteudo where codconteudo = '{$codconteudo}'"));
    }

    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from conteudo where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' order by dtcadastro");
    } 
    
}