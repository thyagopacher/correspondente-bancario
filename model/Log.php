<?php

Class Log {

    public $codlog;
    public $codpessoa;
    public $data;
    public $hora;
    public $codpagina;
    public $codempresa;
    public $acao; /*     * ('inserir', 'atualizar', 'excluir') */
    public $observacao;
    private $conexao;

    public function __construct($conn = NULL) {
        if (!isset($conn) || $conn == NULL) {
            $conn = new Conexao();
        }
        $this->conexao = $conn;
    }

    public function __destruct() {
        unset($this);
    }

    public function inserir() {
        if (!isset($this->data) || $this->data == NULL || $this->data == "") {
            $this->data = date('Y-m-d');
        }
        if (!isset($this->hora) || $this->hora == NULL || $this->hora == "") {
            $this->hora = date('H:i:s');
        }
        if (!isset($this->codempresa) || $this->codempresa == NULL || $this->codempresa == "") {
            $this->codempresa = $_SESSION["codempresa"];
        }
        if (!isset($this->codpessoa) || $this->codpessoa == NULL || $this->codpessoa == "") {
            $this->codpessoa = $_SESSION["codpessoa"];
        }
        return $this->conexao->inserir("log", $this);
    }

    public function atualizar() {
        if (!isset($this->data) || $this->data == NULL || $this->data == "") {
            $this->data = date("Ymd");
        }
        if (!isset($this->hora) || $this->hora == NULL || $this->hora == "") {
            $this->data = date('H:i:s');
        }
        return $this->conexao->inserir("log", $this);
    }

    public function excluir($codlog) {
        return $this->conexao->comando("delete from log where codlog = '{$codlog}' and codempresa = '{$this->codempresa}'");
    }

    public function procuraCodigo($codlog) {
        return $this->conexao->comandoArray("select * from log where codlog = '{$codlog}' and codempresa = '{$this->codempresa}'");
    }

    public function procuraCodpessoa($codpessoa) {
        return $this->conexao->comando("select * from log where codpessoa = '{$codpessoa}' and codempresa = '{$this->codempresa}' order by data");
    }

    public function procuraData($data1, $data2) {
        return $this->conexao->comando("select * from log where data >= '{$data1}' and data <= '{$data2}' and codempresa = '{$this->codempresa}' order by data");
    }

}
