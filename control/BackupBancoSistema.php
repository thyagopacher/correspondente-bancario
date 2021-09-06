<?php
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
if(isset($_SESSION["codnivel"])){
    if($_SESSION["codnivel"] == 1){
        if(isset($_GET["codempresa"])){
            $andTabela = " and codempresa = '{$_GET["codempresa"]}'";
        }
    }elseif($_SESSION["codnivel"] != 1){
        $andTabela = " and codempresa = '{$_SESSION['codempresa']}'";
    }
}
// dados de conexão com o banco de dados a ser backupeado
include "../model/Conexao.php";
$conexao = new Conexao();

// gerando um arquivo sql. Como?
// a função fopen, abre um arquivo, que no meu caso, será chamado como: nomedobanco.sql
// note que eu estou concatenando dinamicamente o nome do banco com a extensão .sql.
$arquivo = "backup_painel_" . date("Y-m-d_H:i") . ".sql";
$back = fopen("../arquivos/".$arquivo, "w");

// aqui, listo todas as tabelas daquele banco selecionado acima
$res = $conexao->comando("SHOW TABLES")or die("Erro ao selecionar tabelas!!!");

// resgato cada uma das tabelas, num loop
while ($row = mysqli_fetch_row($res)) {
    $table = $row[0];    
// usando a função SHOW CREATE TABLE do mysql, exibo as funções de criação da tabela, 
// exportando também isso, para nosso arquivo de backup
    
    $res2 = $conexao->comando("SHOW CREATE TABLE $table");
// digo que o comando acima deve ser feito em cada uma das tabelas

    while ($lin = mysqli_fetch_row($res2)) {
// instruções que serão gravadas no arquivo de backup
        fwrite($back, "\n#\n# Criação da Tabela : $table\n#\n\n");
        fwrite($back, "$lin[1] ;\n\n#\n# Dados a serem incluídos na tabela\n#\n\n");

// seleciono todos os dados de cada tabela pega no while acima
// e depois gravo no arquivo .sql, usando comandos de insert
        $sql = "SELECT * FROM $table where 1 = 1 {$andTabela}";
        $res3 = $conexao->comando($sql);
        while ($r = mysqli_fetch_row($res3)) {
            $sql = "INSERT INTO $table VALUES (";

// este laço irá executar os comandos acima, gerando o arquivo ao final, 
// na função fwrite (gravar um arquivo)
// este laço também irá substituir as aspas duplas, simples e campos vazios
// por aspas simples, colocando espaços e quebras de linha ao final de cada registro, etc
// deixando o arquivo pronto para ser importado em outro banco

            for ($j = 0; $j < mysqli_num_fields($res3); $j++) {
                if (!isset($r[$j]))
                    $sql .= " '',";
                elseif ($r[$j] != "")
                    $sql .= " '" . addslashes($r[$j]) . "',";
                else
                    $sql .= " '',";
            }
//            $sql = ereg_replace(",$", "", $sql);
            $sql .= ");\n";

            fwrite($back, $sql);
        }
    }
}

// fechar o arquivo que foi gravado
fclose($back);
// gerando o arquivo para download, com o nome do banco e extensão sql.
Header("Content-type: application/sql");
Header("Content-Disposition: attachment; filename=$arquivo");
// lê e exibe o conteúdo do arquivo gerado
readfile("../arquivos/".$arquivo);
?>