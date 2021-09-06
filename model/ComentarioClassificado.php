<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class ComentarioClassificado{
    
    public $codcomentario;
    public $codmorador;
    public $texto;
    public $codfuncionario;
    public $porte;
    public $codempresa;
    public $dtcadastro;
    public $codclassificado;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        return $this->conexao->comando("insert into comentarioclassificado(codmorador, texto, codempresa, dtcadastro, codclassificado)
        values('{$this->codmorador}', '".addslashes($this->texto)."', '{$this->codempresa}', '".date('Y-m-d')."', {$this->codclassificado}');");
    }
    
    public function atualizar(){      
        $setar = "";
        if(isset($this->texto) && $this->texto != NULL && $this->texto != ""){
            $setar .= ", texto = '{$this->texto}'";
        }
        if(isset($this->codclassificado) && $this->codclassificado != NULL && $this->codclassificado != ""){
            $setar .= ", codclassificado = '{$this->codclassificado}'";
        }
        if(isset($this->dtcadastro) && $this->dtcadastro != NULL && $this->dtcadastro != ""){
            $setar .= ", dtcadastro = '{$this->dtcadastro}'";
        }
        $sql = "update comentarioclassificado set codmorador = '{$this->codmorador}' {$setar}
        where codcomentario = '{$this->codcomentario}' and codempresa = '{$this->codempresa}'";
        return $this->conexao->comando($sql);
    }  
    
    public function excluir($codcomentarioclassificado){
        return $this->conexao->comando("delete from comentarioclassificado where codcomentarioclassificado = '{$codcomentarioclassificado}' and codempresa='{$this->codempresa}'");
    }
    
    public function procuraCodigo($codcomentarioclassificado){
        return $this->conexao->comandoArray(("select * from comentarioclassificado where codcomentarioclassificado = '{$codcomentarioclassificado}' and codempresa='{$this->codempresa}'"));
    }
    
    public function procuraCodpessoa($codmorador){
        return $this->conexao->comando("select * from comentarioclassificado where codmorador = '{$codmorador}' and codempresa='{$this->codempresa}' order by texto");
    } 
        
    public function procuraData($dtcadastro1, $dtcadastro2){
        return $this->conexao->comando("select * from comentarioclassificado where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' and codempresa='{$this->codempresa}' order by texto");
    } 
  
}