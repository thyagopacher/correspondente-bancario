<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Acesso {

    public $codacesso;
    public $codpessoa;
    public $data;
    public $quantidade;
    public $enderecoip;
    public $codempresa;
    public $hora;
    public $entrada;
    public $navegador;
    public $acessode;
    private $conexao;

    public function __construct($conn) {
        $this->conexao = $conn;
    }

    public function __destruct() {
        unset($this);
    }

    public function salvar() {
        $acessoHoje = $this->procuraAcessoPessoaHoje($this->codpessoa, $this->codempresa);
        $this->acessode = $this->acessoDe();
        if (isset($acessoHoje) && $acessoHoje != NULL && isset($acessoHoje["codpessoa"]) && $this->codcategoria != "6" && $this->codcategoria != "7") {
            $this->data = $acessoHoje["data"];
            $this->codacesso = $acessoHoje["codacesso"];
            $this->quantidade = $acessoHoje["quantidade"] + 1;
            $this->dtsaida = "0000-00-00 00:00:00";
            $this->enderecoip = $this->get_client_ip();
            $retorno = $this->atualizar();
        } else {
            $this->enderecoip = $this->get_client_ip();
            $this->hora       = date("H:i:s"); 
            $this->data       = date("Y-m-d");
            $retorno = $this->inserir();
        }
        return $retorno;
    }

    public function inserir() {
        if (!isset($this->entrada) || $this->entrada == NULL || $this->entrada == "") {
            $this->entrada = "online";
        } 
        return $this->conexao->inserir("acesso", $this);
    }

    public function atualizar() {
        return $this->conexao->atualizar("acesso", $this);
    }

    public function excluir($codacesso) {
        $sql = "delete from acesso where codacesso = '{$codacesso}' and codempresa = '{$this->codempresa}'";
        return $this->conexao->comando($sql);
    }

    public function procuraCodigo($codacesso) {
        return $this->conexao->comandoArray(("select * from acesso where codacesso = '{$codacesso}' and codempresa = '{$this->codempresa}'"));
    }

    public function procuraCodpessoa($codpessoa) {
        return $this->conexao->comando("select * from acesso where codpessoa = '{$codpessoa}' and codempresa = '{$this->codempresa}' order by data");
    }

    public function procuraAcessoPessoaHoje($codpessoa) {
        return $this->conexao->comandoArray('select * from acesso where codpessoa = '.$codpessoa.' and data = CURRENT_DATE() and codempresa = '.$this->codempresa.' order by data');
    }

    public function procuraData($data1, $data2) {
        return $this->conexao->comando("select * from acesso where data >= '{$data1}' and data <= '{$data2}' and codempresa = '{$this->codempresa}' order by data");
    }

    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if ($_SERVER['HTTP_X_FORWARDED']) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if ($_SERVER['HTTP_FORWARDED_FOR']) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if ($_SERVER['HTTP_FORWARDED']) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if ($_SERVER['REMOTE_ADDR']) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    public function acessoDe(){
        if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
            return "iPhone";
        }elseif(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],"iPad")){
            return "iPad";
        }elseif(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],"Android")){
            return "Android";
        }elseif(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],"webOS")){
            return "webOS";
        }elseif(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry")){
            return "BlackBerry";
        }elseif(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],"iPod")){
            return "iPod";
        }elseif(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],"Symbian")){
            return "Symbian";
        }else{
            return "computador";
        }
    }
    
    public function navegador() {
        $browser = new Browser;
        return $browser->getBrowser();
    }

}
