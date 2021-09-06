<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
date_default_timezone_set('America/Sao_Paulo');

class Conexao {

    private $host    = 'mysql.sitesesistemaspg.com';
    private $usuario = 'sitesesistemas01';
    private $senha   = 'd5s3t6';
    private $banco   = 'sitesesistemas01';
    private $porta = 3306;
    private $resultado;
    public $conexao;

    function __construct($usuario = null, $senha = null, $enderecoip = null, $banco = null) {
        if (isset($usuario) && $usuario != NULL && $usuario != "") {
            $this->banco = $banco;
            $this->host = $enderecoip;
            $this->usuario = $usuario;
            $this->senha = $senha;
        }
        $this->conectar();
    }

    function __destruct() {
        if(isset($this->resultado) && $this->resultado != NULL){
            unset($this->resultado);
        }
        if ($this->conexao != FALSE) {
            mysqli_close($this->conexao);
        }
        
    }

    public function conectar() {
        $this->conexao = mysqli_connect($this->host, $this->usuario, $this->senha, $this->banco, $this->porta);
        mysqli_set_charset($this->conexao, 'utf8');
    }

    /* retorna mysql_query */

    public function comando($query) {
        $this->resultado = mysqli_query($this->conexao, $query);
        return $this->resultado;
    }

    public function comandoArray($query) {
//        echo $query;
        return mysqli_fetch_array($this->comando($query));
    }

    /*     * retorna a quantidade de resultados da consulta */

    public function qtdResultado($resultado) {
        return mysqli_num_rows($resultado);
    }

    public function resultadoArray($resultado = null) {
        if ($resultado != NULL) {
            $this->resultado = $resultado;
        }
        return mysqli_fetch_array($this->resultado);
    }

    public function inserir($tabela, $objeto) {
        $valores = '';
        $campos = '';
        $res = $this->comando('DESC ' . $tabela);
        $useragent = $_SERVER['HTTP_USER_AGENT']; // CHECANDO O NAVEGADOR UTILIZADO

        if ($this->qtdResultado($res) > 0) {
            while ($campo = $this->resultadoArray($res)) {
                if ($campo['Key'] != 'PRI' && isset($objeto->$campo['Field']) && $objeto->$campo['Field'] != NULL && $objeto->$campo['Field'] != '') {
                    $objeto->$campo['Field'] = addslashes(trim($objeto->$campo['Field']));
                    $campos .= "{$campo['Field']},";
                    if ($campo['Type'] === 'text') {
                        $valores .= '"' . $objeto->$campo['Field'] . '",';
                    } elseif ($campo['Type'] === 'date' && preg_match('|Firefox/([0-9\.]+)|', $useragent, $matched)) {
                        $valores .= '"' . implode('-', array_reverse(explode('/', $objeto->$campo['Field']))) . '",';
                    } elseif ($campo['Type'] === 'double' && strpos($objeto->$campo['Field'], ',')) {
                        $valores .= '"' . str_replace(',', '.', $objeto->$campo['Field']) . '",';
                    } elseif ($campo['Type'] == "int(11)") {
                        $valores .= '"' . (int) $objeto->$campo['Field'] . '",';
                    } elseif ($campo['Field'] == "codempresa" && ($objeto->$campo['Field'] == NULL || $objeto->$campo['Field'] == "")) {
                        $valores .= '"' . (int) $_SESSION["codempresa"] . '",';
                    } elseif ($campo['Field'] == "codfuncionario" && ($objeto->$campo['Field'] == NULL || $objeto->$campo['Field'] == "")) {
                        $valores .= '"' . (int) $_SESSION["codpessoa"] . '",';
                    } else {
                        $valores .= '"' . $objeto->$campo['Field'] . '",';
                    }
                }
            }
        }
        if (isset($_SESSION['codpessoa']) && $_SESSION['codpessoa'] != NULL && $_SESSION['codpessoa'] != '') {//atualizando ultima ação da pessoa perante o sistema, ajuda a verificar se ela está online
            mysqli_query($this->conexao, 'update acesso set ultimaacao = "' . date('Y-m-d H:i:s') . '", dtsaida = "" where codpessoa = ' . $_SESSION['codpessoa'] . ' and codempresa = ' . $_SESSION['codempresa'] . ' and data = CURRENT_DATE()');
        }
        $sql = 'insert into ' . $tabela . '(' . substr($campos, 0, strlen(trim($campos)) - 1) . ') values(' . substr($valores, 0, strlen(trim($valores)) - 1) . ')';
        $resInserir = $this->comando($sql);
        return $resInserir;
    }

    public function atualizar($tabela, $objeto) {
        $setar = '';
        $where = '';
        $chavePrimaria = 0;
        $res = $this->comando('DESC ' . $tabela);
        if ($this->qtdResultado($res) > 0) {
            $useragent = $_SERVER['HTTP_USER_AGENT']; // CHECANDO O NAVEGADOR UTILIZADO
            while ($campo = $this->resultadoArray($res)) {
                $objeto->$campo['Field'] = addslashes(trim($objeto->$campo['Field']));
                if ($campo['Key'] != 'PRI' && isset($objeto->$campo['Field']) && $objeto->$campo['Field'] != NULL && trim($objeto->$campo['Field']) != '') {
                    if ($campo['Type'] === 'text') {
                        $setar .= $campo['Field'] . ' = "' . $objeto->$campo['Field'] . '", ';
                    } elseif ($campo['Type'] === 'date' && preg_match('|Firefox/([0-9\.]+)|', $useragent, $matched)) {
                        $setar .= $campo['Field'] . ' = "' . implode('-', array_reverse(explode('/', $objeto->$campo['Field']))) . '", ';
                    } elseif ($campo['Type'] === 'double' && strpos($objeto->$campo['Field'], ',')) {
                        $setar .= $campo['Field'] . ' = "' . (double) str_replace(',', '.', $objeto->$campo['Field']) . '", ';
                    } elseif ($campo['Type'] == "int(11)") {
                        $setar .= $campo['Field'] . ' = "' . (int) $objeto->$campo['Field'] . '", ';
                    } else {
                        $setar .= $campo['Field'] . ' = "' . $objeto->$campo['Field'] . '", ';
                    }
                } elseif ($campo['Key'] === 'PRI') {
                    $chavePrimaria = $objeto->$campo['Field'];
                    $where .= $campo['Field'] . ' = "' . $objeto->$campo['Field'] . '"';
                }
            }
        }
        //atualizando ultima ação da pessoa perante o sistema, ajuda a verificar se ela está online
        if (isset($_SESSION['codpessoa']) && $_SESSION['codpessoa'] != NULL && $_SESSION['codpessoa'] != '') {
            mysqli_query($this->conexao, 'update acesso set ultimaacao = "' . date('Y-m-d H:i:s') . '", dtsaida = "" where codpessoa = ' . $_SESSION['codpessoa'] . ' and codempresa = ' . $_SESSION['codempresa'] . ' and data = CURRENT_DATE()');
        }
        $sql = 'update ' . $tabela . ' set ' . substr($setar, 0, strlen(trim($setar)) - 1) . ' where ' . $where;
        return $this->comando($sql);
    }

    public function excluir($tabela, $objeto) {
        $where = '';
        $res = $this->comando('DESC ' . $tabela);
        $chavePrimaria = 0;
        if ($this->qtdResultado($res) > 0) {
            while ($campo = $this->resultadoArray($res)) {
                if ($campo['Key'] == 'PRI') {
                    $chavePrimaria = $objeto->$campo['Field'];
                    $where .= $campo['Field'] . '= "' . $objeto->$campo['Field'] . '"';
                    break;
                }
            }
        }
        //atualizando ultima ação da pessoa perante o sistema, ajuda a verificar se ela está online
        if (isset($_SESSION['codpessoa']) && $_SESSION['codpessoa'] != NULL && $_SESSION['codpessoa'] != '') {
            mysqli_query($this->conexao, 'update acesso set ultimaacao = "' . date('Y-m-d H:i:s') . '", dtsaida = "" where codpessoa = ' . $_SESSION['codpessoa'] . ' and codempresa = ' . $_SESSION['codempresa'] . ' and data = CURRENT_DATE()');
        }
        $sql = 'delete from ' . $tabela . ' where ' . $where;
        return $this->comando($sql);
    }

    public function procurarCodigo($tabela, $objeto) {
        $where = '';
        $res = $this->comando('DESC ' . $tabela);
        $qtdTabela = $this->qtdResultado($res);
        if ($qtdTabela > 0) {
            while ($campo = $this->resultadoArray($res)) {
                if ($campo['Key'] == 'PRI') {
                    $where .= $campo['Field'] . '= ' . (int) $objeto->$campo['Field'];
                    break;
                }
            }
        }
        //atualizando ultima ação da pessoa perante o sistema, ajuda a verificar se ela está online
        if (isset($_SESSION['codpessoa']) && $_SESSION['codpessoa'] != NULL && $_SESSION['codpessoa'] != '') {
            mysqli_query($this->conexao, 'update acesso set ultimaacao = "' . date('Y-m-d H:i:s') . '", dtsaida = "" where codpessoa = ' . $_SESSION['codpessoa'] . ' and codempresa = ' . $_SESSION['codempresa'] . ' and data = CURRENT_DATE()');
        }
        $sql = 'select * from ' . $tabela . ' where ' . $where;
        return $this->comandoArray($sql);
    }

    /**
     * método para montagem de pesquisa em geral
     * @param string $tabela passa o nome da tabela a ser pesquisada
     * @param array $colunas passa o array das colunas a serem usadas
     * @param array $post para ser usado no filtro
     * */
    public function procurarGeral($tabela, $colunas, $post) {
        $chave_primaria = "cod{$tabela}";
        $colunas_separadas = implode(",", $colunas);
        foreach ($post as $key => $valorPost) {
            if (!is_array($valorPost)) {
                $where .= " and {$tabela}.{$key} = '{$valorPost}'";
            } elseif (count($valorPost) == 2) {//para datas nos filtros
                $where .= " and {$tabela}.{$key} >= '{$valorPost[0]}'";
                $where .= " and {$tabela}.{$key} <= '{$valorPost[1]}'";
            } elseif (count($valorPost) > 2) {//para combos multiple
                $where .= " and {$tabela}.{$key} in(" . implode(",", $valorPost) . ")";
            }
        }
        foreach ($colunas as $key => $coluna) {
            if (strstr($coluna, 'cod') != FALSE) {
                $apelidoTabela2 = str_replace('cod', '', $coluna);
                if ($coluna == "codmorador" || $coluna == "codpessoa" || $coluna == "codfuncionario") {
                    $colunas_separadas .= ", {$apelidoTabela2}.apartamento, {$apelidoTabela2}.bloco, {$apelidoTabela2}.nome as {$apelidoTabela2}";
                } elseif ($coluna == "codcategoria" || $coluna == "codstatus" || $coluna == "codtipo" || $coluna == "codnivel") {
                    $colunas_separadas .= ", {$apelidoTabela2}.nome as {$apelidoTabela2}";
                } elseif ($coluna == "dtcadastro" || $coluna == "dtnascimento" || $coluna == "data") {
                    $colunas_separadas .= ", DATE_FORMAT({$apelidoTabela2}.{$coluna}, '%d/%m/%y %H:%i') as {$coluna}2";
                }
                $innerJoin .= " inner join {$apelidoTabela2} on {$apelidoTabela2}.{$coluna} = {$tabela}.{$coluna}";
            }
        }
        $sql = "select {$tabela}.{$chave_primaria} {$colunas_separadas} from {$tabela} {$innerJoin} where 1 = 1 {$where}";
        return $this->comando($sql);
    }

}
