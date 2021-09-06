<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
include "../model/Conexao.php";
$conexao = new Conexao();
$and = "";
if (isset($_GET["tipo"]) && $_GET["tipo"] != NULL && $_GET["tipo"] != "") {
    $_POST["tipo"] = $_GET["tipo"];
}
if (isset($_GET["codcarteira"]) && $_GET["codcarteira"] != NULL && $_GET["codcarteira"] != "") {
    $and .= " and pessoa.codpessoa in(select codcliente from carteiracliente where codcarteira = {$_GET["codcarteira"]})";
}
if (isset($_POST["email"]) && $_POST["email"] != NULL && $_POST["email"] != "") {
    $and .= " and pessoa.email like '%{$_POST["email"]}%'";
}
if (isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != "") {
    if (strpos($_POST["data1"], "/")) {
        $data1 = implode("-", array_reverse(explode("/", $_POST["data1"])));
    } else {
        $data1 = $_POST["data1"];
    }
    $and .= " and pessoa.dtcadastro >= '{$data1}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != "") {
    if (strpos($_POST["data2"], "/")) {
        $data1 = implode("-", array_reverse(explode("/", $_POST["data2"])));
    } else {
        $data1 = $_POST["data2"];
    }
    $and .= " and pessoa.dtcadastro <= '{$_POST["data2"]}'";
}
if (isset($_POST["cpf"]) && $_POST["cpf"] != NULL && $_POST["cpf"] != "") {
    $cpf_limpo = str_replace(".", "", str_replace("-", "", $_POST["cpf"]));
    $and .= " and (pessoa.cpf = '{$_POST["cpf"]}' or pessoa.cpf = '{$cpf_limpo}')";
}
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and pessoa.nome like '%{$_POST["nome"]}%'";
}
if (isset($_POST["status"]) && $_POST["status"] != NULL && $_POST["status"] != "") {
    $and .= " and pessoa.status = '{$_POST["status"]}'";
}
if (isset($_POST["codstatus"]) && $_POST["codstatus"] != NULL && $_POST["codstatus"] != "") {
    $and .= " and pessoa.codstatus = '{$_POST["codstatus"]}'";
}
if (isset($_POST["carteira"]) && $_POST["carteira"] != NULL && $_POST["carteira"] != "") {
    $and .= " and pessoa.codpessoa in (select codcarteira from carteiracliente where codcarteira = '{$_POST["carteira"]}')";
}
if (isset($_POST["codstatus"]) && $_POST["codstatus"] != NULL && $_POST["codstatus"] != "") {
    $and .= " and pessoa.codstatus = '{$_POST["codstatus"]}'";
}
if (isset($_POST["codcategoria"]) && $_POST["codcategoria"] != NULL && $_POST["codcategoria"] != "") {
    $and .= " and pessoa.codcategoria = '{$_POST["codcategoria"]}'";
} else if(!isset($_GET["codcarteira"])){
    $and .= " and pessoa.codcategoria not in ('1','6')";
}
//margem inicial para buscar
if (isset($_POST["margem_inicial"]) && $_POST["margem_inicial"] != NULL && $_POST["margem_inicial"] != "") {
    $innerJoin .= " inner join beneficiocliente as beneficio on beneficio.codpessoa = pessoa.codpessoa and beneficio.codempresa = pessoa.codempresa";
    $campos .= ", beneficio.margem";
    $and .= " and beneficio.margem >= '" . str_replace(",", ".", $_POST["margem_inicial"]) . "'";
}
if (isset($_POST["margem_fim"]) && $_POST["margem_fim"] != NULL && $_POST["margem_fim"] != "") {
    $innerJoin .= " inner join beneficiocliente as beneficio on beneficio.codpessoa = pessoa.codpessoa and beneficio.codempresa = pessoa.codempresa";
    $campos .= ", beneficio.margem";
    $and .= " and beneficio.margem <= '" . str_replace(",", ".", $_POST["margem_fim"]) . "'";
}

//se com telefone
if (isset($_POST["ctelefone"]) && $_POST["ctelefone"] != NULL && $_POST["ctelefone"] != "" && $_POST["ctelefone"] == "s") {
    $innerJoin .= " inner join telefone on telefone.codpessoa = pessoa.codpessoa";
    $campos .= ", telefone.numero as telefone";
} elseif (isset($_POST["ctelefone"]) && $_POST["ctelefone"] != NULL && $_POST["ctelefone"] != "" && $_POST["ctelefone"] == "n") {
    $and .= " and pessoa.codpessoa not in(select codpessoa from telefone)";
}

//se com endereço
if (isset($_POST["cendereco"]) && $_POST["cendereco"] != NULL && $_POST["cendereco"] != "" && $_POST["cendereco"] == "s") {
    $campos .= ", pessoa.tipologradouro, pessoa.logradouro, pessoa.numero, pessoa.bairro, pessoa.cidade, pessoa.estado";
    $and .= " and pessoa.cidade <> '' and pessoa.estado <> ''";
} elseif (isset($_POST["cendereco"]) && $_POST["cendereco"] != NULL && $_POST["cendereco"] != "" && $_POST["cendereco"] == "n") {
    $and .= " and pessoa.cidade = '' and pessoa.estado = ''";
}

//se com beneficio
if (isset($_POST["cbeneficio"]) && $_POST["cbeneficio"] != NULL && $_POST["cbeneficio"] != "" && $_POST["cbeneficio"] == "s") {
    $innerJoin .= " inner join beneficiocliente as beneficio on beneficio.codpessoa = pessoa.codpessoa and beneficio.codempresa = pessoa.codempresa";
    $innerJoin .= " inner join especie on especie.codespecie = beneficio.codespecie";
    $campos .= ", beneficio.numbeneficio, beneficio.salariobase, beneficio.margem, especie.nome as especie";
} elseif (isset($_POST["cbeneficio"]) && $_POST["cbeneficio"] != NULL && $_POST["cbeneficio"] != "" && $_POST["cbeneficio"] == "n") {
    $and .= " and pessoa.codpessoa not in(select codpessoa from beneficiocliente)";
}

//se com empréstimo
if (isset($_POST["cemprestimo"]) && $_POST["cemprestimo"] != NULL && $_POST["cemprestimo"] != "" && $_POST["cemprestimo"] == "s") {
    $innerJoin .= " inner join emprestimo on emprestimo.codpessoa = pessoa.codpessoa";
    $campos .= ", emprestimo.prazo, emprestimo.quitacao, emprestimo.vlparcela, emprestimo.meio, emprestimo.situacao, emprestimo.parcelas_aberto";
} elseif (isset($_POST["cemprestimo"]) && $_POST["cemprestimo"] != NULL && $_POST["cemprestimo"] != "" && $_POST["cemprestimo"] == "n") {
    $and .= " and pessoa.codpessoa not in(select codpessoa from emprestimo)";
}

if (isset($_POST["ordem"])) {
    if ($_POST["ordem"] == "1") {
        $orderby = " order by pessoa.nome";
    } elseif ($_POST["ordem"] == "2") {
        $orderby = " order by pessoa.dtcadastro";
    }
}
if(!isset($_GET["codcarteira"])){
    if ((!isset($_POST["callcenter"]) || $_POST["callcenter"] == NULL || $_POST["callcenter"] == "") && !isset($_POST["codcategoria"])) {
        $and .= " and pessoa.codcategoria not in(1,6)";
    }
}

$sql = "select pessoa.codpessoa, pessoa.nome, pessoa.codcategoria, pessoa.cpf, 
    pessoa.email, DATE_FORMAT(pessoa.dtcadastro, '%d/%m/%Y') as data, pessoa.senha, categoria.nome as categoria, pessoa.status,
    nivel.nome as nivel, status.nome as status {$campos}
    from pessoa 
    left join categoriapessoa as categoria on categoria.codcategoria = pessoa.codcategoria 
    left join statuspessoa as status on status.codstatus = pessoa.codstatus
    inner join importacao on importacao.codimportacao = pessoa.codimportacao
    left join nivel on nivel.codnivel = pessoa.codnivel {$innerJoin}
    where 1 = 1 {$and}
    and pessoa.codempresa = '{$_SESSION['codempresa']}'    
    $orderby";
$res = $conexao->comando($sql)or die("<pre>$sql</pre>");
$qtd = $conexao->qtdResultado($res);
if ($qtd > 0) {
    $html = "";
    $nome = 'Rel Pessoa';
    $html .= '<table class="responstable" style="font-size: 12px">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Nome</th>';
    $html .= '<th>E-mail</th>';
    $html .= '<th>Situação Pessoa</th>';
    $html .= '<th>CPF</th>';
    $html .= '<th>Telefone</th>';
    if (isset($_POST["tipo"]) && $_POST["tipo"] == "xls") {
        if (isset($_POST["cbeneficio"]) && $_POST["cbeneficio"] != NULL && $_POST["cbeneficio"] != "" && $_POST["cbeneficio"] == "s") {
            $html .= '<th>N Beneficio</th>';
            $html .= '<th>Salário Base</th>';
            $html .= '<th>Margem</th>';
            $html .= '<th>Espécie</th>';
        } 
        if (isset($_POST["cemprestimo"]) && $_POST["cemprestimo"] != NULL && $_POST["cemprestimo"] != "" && $_POST["cemprestimo"] == "s") {
            $html .= '<th>Prazo</th>';
            $html .= '<th>Restante</th>';
            $html .= '<th>Quitação</th>';
            $html .= '<th>Vl. Parcela</th>';
            $html .= '<th>Meio</th>';
            $html .= '<th>Situação</th>';
        }
        if (isset($_POST["cendereco"]) && $_POST["cendereco"] != NULL && $_POST["cendereco"] != "" && $_POST["cendereco"] == "s") {
            $html .= '<th>T. Logradouro</th>';
            $html .= '<th>Logradouro</th>';
            $html .= '<th>Número</th>';
            $html .= '<th>Bairro</th>';
            $html .= '<th>Cidade</th>';
            $html .= '<th>Estado</th>';
        }
    }
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    $titulo = "";
    while ($pessoa = $conexao->resultadoArray($res)) {
        $html .= '<tr>';
        $html .= '<td>' . $pessoa["nome"] . '</td>';
        $html .= '<td>' . $pessoa["email"] . '</td>';
        $html .= '<td>' . $pessoa["status"] . '</td>';
        $html .= '<td>' . $pessoa["cpf"] . '</td>';
        $html .= '<td>' . $pessoa["telefone"] . '</td>';
        if (isset($_POST["tipo"]) && $_POST["tipo"] == "xls") {
            if (isset($_POST["cbeneficio"]) && $_POST["cbeneficio"] != NULL && $_POST["cbeneficio"] != "" && $_POST["cbeneficio"] == "s") {
                $html .= '<td>'.$pessoa["numbeneficio"].'</td>';
                $html .= '<td>'.number_format($pessoa["salariobase"], 2, ",", ".").'</td>';
                $html .= '<td>'.number_format($pessoa["margem"], 2, ",", ".").'</td>';
                $html .= '<td>'.$pessoa["especie"].'</td>';
            }
            if (isset($_POST["cemprestimo"]) && $_POST["cemprestimo"] != NULL && $_POST["cemprestimo"] != "" && $_POST["cemprestimo"] == "s") {
                $html .= '<td>'.$pessoa["prazo"].'</td>';
                $html .= '<td>'.$pessoa["parcelas_aberto"].'</td>';
                $html .= '<td>'.$pessoa["quitacao"].'</td>';
                $html .= '<td>'.$pessoa["vlparcela"].'</td>';
                $html .= '<td>'.$pessoa["meio"].'</td>';
                $html .= '<td>'.$pessoa["situacao"].'</td>';
            }
            if (isset($_POST["cendereco"]) && $_POST["cendereco"] != NULL && $_POST["cendereco"] != "" && $_POST["cendereco"] == "s") {
                $html .= '<td>'.$pessoa["tipologradouro"].'</td>';
                $html .= '<td>'.$pessoa["logradouro"].'</td>';
                $html .= '<td>'.$pessoa["numero"].'</td>';
                $html .= '<td>'.$pessoa["bairro"].'</td>';
                $html .= '<td>'.$pessoa["cidade"].'</td>';
                $html .= '<td>'.$pessoa["estado"].'</td>';
            }
        }        
        $html .= '</tr>';
        $titulo = "";
    }
    $html .= '</tbody>';
    $html .= '</table>';

    $_POST["html"] = $html;
    $paisagem = "sim";

    if (isset($_POST["tipo"]) && $_POST["tipo"] == "xls") {
        include "./GeraExcel.php";
    } else {
        include "./GeraPdf.php";
    }
} else {
    echo '<script>alert("Sem pessoa encontrada!");window.close();</script>';
}

