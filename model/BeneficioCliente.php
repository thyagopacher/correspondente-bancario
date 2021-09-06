<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class BeneficioCliente {

    public $codbeneficio;
    public $codpessoa;
    public $dtcadastro;
    public $codorgao;
    public $codespecie;
    public $codempresa;
    public $salariobase;
    public $margem;
    public $numbeneficio;
    public $matricula;
    public $codfuncionario;
    public $meio;
    public $codbanco;
    public $agencia;
    public $contacorrente;
    public $situacao;
    public $usuario = "LIBRAM@WS406485";
    public $usuarioMultiBR;
    public $senhaMultiBR;
    public $comeco = "http://85.25.237.95:8010/";
    private $conexao;

    public function __construct($conn) {
        $this->conexao = $conn;
        if (isset($_SESSION["codempresa"]) && $_SESSION["codempresa"] != NULL && $_SESSION["codempresa"] != "") {
            $configuracao = $this->conexao->comandoArray('select codconfiguracao, loginViper, usuarioMultiBR, senhaMultiBR, keyanaliseinfo 
            from configuracao where codempresa = ' . $_SESSION["codempresa"]);
            
            if (isset($configuracao["codconfiguracao"]) && $configuracao["codconfiguracao"] != NULL && $configuracao["codconfiguracao"] != "") {
                $this->usuario = $configuracao["loginViper"];
                $this->usuarioMultiBR = $configuracao["usuarioMultiBR"];
                $this->senhaMultiBR = $configuracao["senhaMultiBR"];
                $this->keyanaliseinfo = $configuracao["keyanaliseinfo"];
            }
        }
    }

    public function __destruct() {
        unset($this);
    }

    public function inserir() {
        if (!isset($this->dtcadastro) || $this->dtcadastro == NULL || $this->dtcadastro == "") {
            $this->dtcadastro = date("Y-m-d H:i:s");
        }
        if (!isset($this->codempresa) || $this->codempresa == NULL || $this->codempresa == "") {
            $this->codempresa = $_SESSION["codempresa"];
        }
        if (!isset($this->codfuncionario) || $this->codfuncionario == NULL || $this->codfuncionario == "") {
            $this->codfuncionario = $_SESSION["codpessoa"];
        }
        return $this->conexao->inserir("beneficiocliente", $this);
    }

    public function atualizar() {
        return $this->conexao->atualizar("beneficiocliente", $this);
    }

    public function excluir($codbeneficio) {
        return $this->conexao->comando("delete from beneficiocliente where codbeneficio = '{$codbeneficio}' and codempresa = '{$this->codempresa}'");
    }

    public function procuraCodigo($codbeneficio) {
        return $this->conexao->comandoArray("select * from beneficiocliente where codbeneficio = '{$codbeneficio}' and codempresa = '{$this->codempresa}'");
    }

    public function procuraCodpessoa($codpessoa) {
        return $this->conexao->comando("select * from beneficiocliente where codpessoa = '{$codpessoa}' and codempresa = '{$this->codempresa}' order by dtcadastro");
    }

    public function procuraData($dtcadastro1, $dtcadastro2) {
        return $this->conexao->comando("select * from beneficiocliente where dtcadastro >= '{$dtcadastro1}' and dtcadastro <= '{$dtcadastro2}' and codempresa = '{$this->codempresa}' order by dtcadastro");
    }

    /*     * viper */

    public function consultaCpfSiape($cpf) {
        $numero = str_pad($numero, 11, "0", STR_PAD_LEFT);
        $url = $this->comeco . "ws_siape.php?usuario={$this->usuario}&info={$cpf}&consulta=cpf";
        $conteudo = file_get_contents($url);
//        echo "Conteudo xml: <pre>$conteudo</pre>"; 
        return simplexml_load_string(strtolower($conteudo));
    }

    public function consultaMatriculaSiape($numero) {
        $url = $this->comeco . "ws_siape.php?usuario={$this->usuario}&info={$numero}&consulta=matricula";
        $conteudo = file_get_contents($url);
        return simplexml_load_string(strtolower($conteudo));
    }

    public function consultaCpfInss($numero) {
        $numero = str_pad($numero, 11, "0", STR_PAD_LEFT);
        $url = $this->comeco . "ws_inss.php?usuario={$this->usuario}&info={$numero}&consulta=cpf";
        $conteudo = file_get_contents($url);
        return simplexml_load_string(strtolower($conteudo));
    }

    public function consultaBenInss($numero) {
        $url = $this->comeco . "ws_inss.php?usuario={$this->usuario}&info={$numero}&consulta=ben";
        $conteudo = file_get_contents($url);
        return simplexml_load_string(strtolower($conteudo));
    }

    /*     * multibr */

    public function consultaKey() {
        $url = "http://{$this->usuarioMultiBR}:{$this->senhaMultiBR}@vpn.multibr.com/sys/api/login?username={$this->usuarioMultiBR}&password={$this->senhaMultiBR}&contentType=xml";
        $conteudo = file_get_contents($url);
        $retorno = simplexml_load_string($conteudo);
        $_SESSION["key"] = $retorno->key;
        return $retorno->key;
    }

    /**
     * retorna informações do beneficio em xml
     * $chave->dados_cadastrais->cep
     */
    public function consultaBenInss2($numero) {
        if (!isset($_SESSION["key"]) || $_SESSION["key"] == NULL || $_SESSION["key"] == "") {
            $_SESSION["key"] = $this->consultaKey();
        }
        $url = "http://{$this->usuarioMultiBR}:{$this->senhaMultiBR}@vpn.multibr.com/sys/api/b_pesq?nb={$numero}&contentType=xml&key={$_SESSION["key"]}";
        $conteudo = file_get_contents($url);
        $retorno = simplexml_load_string($conteudo);

        return $retorno;
    }

    /*     * retorna número de beneficios em array */

    public function consultaCpfInss2($cpf) {
        $cpf = str_pad($cpf, 11, "0", STR_PAD_LEFT);
        if (!isset($_SESSION["key"]) || $_SESSION["key"] == NULL || $_SESSION["key"] == "") {
            $_SESSION["key"] = $this->consultaKey();
        }
        $url = "http://{$this->usuarioMultiBR}:{$this->senhaMultiBR}@vpn.multibr.com/sys/api/b_cpf?cpf={$cpf}&key={$_SESSION["key"]}";
        $array = explode(";", strip_tags(file_get_contents($url)));
        return array_diff($array, array(""));
    }

    /**
     * retorna informações do beneficio em json - API Analise.Info
     */
    public function consultaBenInss3($numero) {
        $url = "http://api.analise.info/Cadastrofull?nb={$numero}&type=json&key={$this->keyanaliseinfo}";
        return json_decode(file_get_contents($url));
    }

    /* 
     * procura por cpf retorna número de beneficio e especie - API Analise.Info
     */
    public function consultaCpfInss3($cpf) {
        $cpf = str_pad($cpf, 11, "0", STR_PAD_LEFT);
        $cpf = str_replace('.', '', str_replace('-', '', $cpf));
        $url = "http://api.analise.info/Pesqcpf?cpf={$cpf}&type=json&key={$this->keyanaliseinfo}";
        return json_decode(file_get_contents($url));
    }

}
