<?php

header ('Content-type: text/html; charset=UTF-8'); 

function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

$conexao     = new Conexao();

$msg_retorno = "";
$sit_retorno = true;

$tabelasNome = array('Nova', 'Refinanciamento', 'Refin + Margem', 'Portabilidade', 'Cartão + Saque', 'Cartão', 'Saque do Cartão');

$sql = 'select codempresa from empresa where 1 = 1 order by rand()';
$resempresa = $conexao->comando($sql);
$qtdempresa = $conexao->qtdResultado($resempresa);

if ($qtdempresa > 0) {
    echo 'Encontrou '. $qtdempresa. ' empresas para colocar tabela<br>';
    while($empresa = $conexao->resultadoArray($resempresa)) {
        echo "Começando inserção na empresa: {$empresa["codempresa"]}<br>";
        $sql = 'select codbanco 
        from banco 
        where nome <> "" 
        order by rand() limit 1';
        $resbanco = $conexao->comando($sql);
        $qtdbanco = $conexao->qtdResultado($resbanco);

        if ($qtdbanco > 0) {
            echo 'Encontrou '. $qtdbanco. ' bancos<br>';
            while ($banco = $conexao->resultadoArray($resbanco)) {
                echo 'Começando inserção no banco: ', $banco["codbanco"], '<br>';
                $sql = 'select count(1) as qtd from tabela where nome in("' . implode('","', $tabelasNome) . '") 
                and codempresa = '.$empresa["codempresa"].'    
                and codbanco = '.$banco["codbanco"];

                $tabelasEmpresa = $conexao->comandoArray($sql);
                if($tabelasEmpresa["qtd"] > 0){
                    $andConvenio = ' and codconvenio not in(select codconvenio 
                        from tabela where nome in("' . implode('","', $tabelasNome) . '") 
                        and codempresa = '.$empresa["codempresa"].'    
                        and codbanco = '.$banco["codbanco"].')';
                }else{
                    $andConvenio = '';
                }
                $sql = 'select codconvenio from convenio 
                where nome <> ""'.$andConvenio;
 
                $resconvenio = $conexao->comando($sql);
                $qtdconvenio = $conexao->qtdResultado($resconvenio);
                if ($qtdconvenio > 0) {
                    echo 'Encontrou '. $qtdconvenio. ' convenios<br>';
                    while ($convenio = $conexao->resultadoArray($resconvenio)) {
                        echo 'Começando inserção no convenio: ', $convenio["codconvenio"], '<br>';
                        foreach ($tabelasNome as $key => $nome_tabela) {
                            $sql = 'select codtabela 
                            from tabela 
                            where nome = "' . $nome_tabela . '"
                            and codbanco = ' . $banco["codbanco"] . '
                            and codconvenio = ' . $convenio["codconvenio"] . '    
                            and codempresa = ' . $empresa["codempresa"];
                            $tabelap = $conexao->comandoArray($sql);
                            if (isset($tabelap["codtabela"]) && $tabelap["codtabela"] != NULL && $tabelap["codtabela"] != "") {
                                continue;
                            }
                            
                            $tabela = new Tabela($conexao);
                            $tabela->codbanco = $banco["codbanco"];
                            $tabela->codconvenio = $convenio["codconvenio"];
                            $tabela->codfuncionario = 6;
                            $tabela->nome = $nome_tabela;
                            $tabela->codempresa = $empresa["codempresa"];
                            $resInserirTabela = $tabela->inserir();
                            if($resInserirTabela == FALSE){
                                die("Erro ao inserir tabela causado por:". mysqli_error($conexao->conexao));
                            }
                            $codigo_tabela = mysqli_insert_id($conexao->conexao);

                            $tprazo = new TabelaPrazo($conexao);
                            $tprazo->codtabela = $codigo_tabela;
                            $tprazo->prazode = 1;
                            $tprazo->prazoate = 72;
                            if($nome_tabela == "Refinanciamento" || $nome_tabela == "Refin + Margem" || $nome_tabela == "Portabilidade"){
                                $tprazo->pgto_liquido = 's';
                            }else{
                                $tprazo->pgto_liquido = 'n';
                            }
                            $tprazo->dtinicio = '2001-01-01';
                            $tprazo->inserir();
                        }
                    }
                }
            }
        }
    }
}
echo 'Importação de tabelas padrão feita!!!';
