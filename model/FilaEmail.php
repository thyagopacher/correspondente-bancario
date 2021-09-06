<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class FilaEmail{
    
    public $codfila;
    public $codpessoa;
    public $dtcadastro;
    public $codfuncionario;
    public $situacao;
    public $codempresa;
    public $assunto;
    public $texto;
    public $tipo;
    public $codpagina;
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
        if(!isset($this->situacao) || $this->situacao == NULL || $this->situacao == ""){
            $this->situacao = "n";//setando situação padrão para não enviado
        }
        if(isset($this->texto) && $this->texto != NULL && $this->texto != ""){
            $this->texto = addslashes($this->texto);
        }
        return $this->conexao->inserir("filaemail", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("filaemail", $this);
    }  
    
    public function excluir($codfila){
        return $this->conexao->comando("delete from filaemail where codfila = '{$codfila}' and codempresa = '{$this->codempresa}'");
    }
    
    public function procuraCodigo($codfila){
        return $this->conexao->comandoArray(("select * from filaemail where codfila = '{$codfila}' and codempresa = '{$this->codempresa}'"));
    }
    
    public function procuraCodpessoa($codpessoa){
        return $this->conexao->comando("select * from filaemail where codpessoa = '{$codpessoa}' and codempresa = '{$this->codempresa}' order by dtcadastro");
    } 
 
    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from filaemail where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' and codempresa = '{$this->codempresa}' order by dtcadastro");
    } 
 
    public function procuraNEnviado(){
        $sql = "select filaemail.*, 
        pessoa.email as para_email, 
        pessoa.nome as para
        from filaemail
        inner join pessoa  
            on pessoa.codpessoa = filaemail.codpessoa 
            and ((pessoa.codpessoa <> '1' 
            and pessoa.codempresa = filaemail.codempresa) 
            or (pessoa.codnivel = '1'))
        where (filaemail.situacao = 'n' or filaemail.situacao = '') 
        and pessoa.email <> '' 
        and pessoa.status = 'a' 
        and pessoa.recebemsg = 's' 
        and DATEDIFF(now(), filaemail.dtcadastro) <= 5
        order by filaemail.dtcadastro limit 5";
        return $this->conexao->comando($sql);
    } 
     
}