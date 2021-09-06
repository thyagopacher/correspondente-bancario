<?php

header('Content-Type: text/html; charset=utf-8');
echo '<link rel="stylesheet" type="text/css" href="/visao/recursos/css/style.min.css">';
echo '<link rel="stylesheet" type="text/css" href="/visao/recursos/css/tabela.css">';
$numero = $_GET["numero"];
if(isset($_POST["numero"])){
    $numero = $_POST["numero"];
}
//    $numero = "1566216084";
if (isset($numero) && $numero != NULL && $numero != "") {

    function __autoload($class_name) {
        if (file_exists("../model/" . $class_name . '.php')) {
            include "../model/" . $class_name . '.php';
        } elseif (file_exists("../visao/" . $class_name . '.php')) {
            include "../visao/" . $class_name . '.php';
        } elseif (file_exists("./" . $class_name . '.php')) {
            include "./" . $class_name . '.php';
        }
    }

    $conexao = new Conexao();
    $beneficio = new BeneficioCliente($conexao);

    if (strpos($numero, ";")) {
        $numeros = explode(";", $numero);
    } else {
        $numeros[0] = $numero;
    }
    foreach ($numeros as $key => $numero) {
        /*         * limpando NB para pesquisar */
        $numero = str_replace(".", "", $numero);
        $numero = str_replace("-", "", $numero);
        if(strlen($numero) == 9){
            $numero = "0". $numero;
        }        
        $consulta_nb = $beneficio->consultaBenInss($numero);
        
        $log = new Log($conexao);
        $log->acao = "procurar";
        $log->codpagina = 4;
        $log->observacao = "Consulta Beneficio INSS : NB - {$numero}";
        $log->inserir();
//        $conteudo    = file_get_contents("consultaNBINSS.xml");
//        $consulta_nb = simplexml_load_string(strtolower($conteudo));
        if($consulta_nb->consulta->detalhamento->rubricas->msg == "DADOS DO EXTRATO OFFLINE"){
            echo "Dados do VIPER desatualizados!!!";
        }        
        
        if (isset($consulta_nb->consulta->beneficio) && $consulta_nb->consulta->beneficio != NULL && $consulta_nb->consulta->beneficio != "") {
            echo '<table class="responstable" style="float: left;width: 556px;">';
            echo '<thead>';
            echo '<tr>';
            echo '<th colspan="2">DADOS CADASTRAIS</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr><td>SIT. BENEFICIO:</td><td>', $consulta_nb->consulta->informacoes_beneficio->situacao_beneficio, '</td></tr>';
            echo '<tr><td>NB:</td><td>', $consulta_nb->consulta->beneficio, '</td></tr>';
            echo '<tr><td>NOME:</td><td>', $consulta_nb->consulta->informacoes_beneficio->nome, '</td></tr>';
            echo '<tr><td>NASCIMENTO:</td><td>', $consulta_nb->consulta->informacoes_beneficio->data_nascimento, '</td></tr>';
            echo '<tr><td>CPF:</td><td>', $consulta_nb->consulta->informacoes_beneficio->cpf, '</td></tr>';
            echo '<tr><td>TELEFONE:</td><td>', $consulta_nb->consulta->informacoes_beneficio->telefone, '</td></tr>';
            echo '<tr><td>ENDEREÇO:</td><td>', $consulta_nb->consulta->informacoes_beneficio->endereco, '</td></tr>';
            echo '<tr><td>BAIRRO:</td><td>', $consulta_nb->consulta->informacoes_beneficio->bairro, '</td></tr>';
            echo '<tr><td>CIDADE:</td><td>', $consulta_nb->consulta->informacoes_beneficio->cidade, '</td></tr>';
            echo '<tr><td>ESTADO:</td><td>', $consulta_nb->consulta->informacoes_beneficio->estado, '</td></tr>';
            echo '<tr><td>CEP:</td><td>', $consulta_nb->consulta->informacoes_beneficio->cep, '</td></tr>';
            echo '</tbody>';
            echo '</table>';

            echo '<table class="responstable" style="float: left;width: 600px;margin-left: 20px;">';
            echo '<thead>';
            echo '<tr>';
            echo '<th colspan="2">DADOS BANCÁRIOS</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr><td>MEIO DE PAGAMENTO:</td><td>', $consulta_nb->consulta->informacoes_beneficio->meio_pagamento, '</td></tr>';
            echo '<tr><td>BANCO:</td><td>', $consulta_nb->consulta->informacoes_beneficio->banco, '</td></tr>';
            echo '<tr><td>CÓD. BANCO:</td><td>', $consulta_nb->consulta->informacoes_beneficio->codigo_banco, '</td></tr>';
            echo '<tr><td>AGÊNCIA:</td><td>', $consulta_nb->consulta->informacoes_beneficio->agencia, '</td></tr>';
            echo '<tr><td>CÓD. AGÊNCIA:</td><td>', $consulta_nb->consulta->informacoes_beneficio->codigo_agencia, '</td></tr>';
            echo '<tr><td>NOME DA AGÊNCIA:</td><td>', $consulta_nb->consulta->informacoes_beneficio->nome_agencia, '</td></tr>';
            echo '<tr><td>END. AGÊNCIA:</td><td>', $consulta_nb->consulta->informacoes_beneficio->endereco_agencia, '</td></tr>';
            echo '<tr><td>CONTA CORRENTE:</td><td>', $consulta_nb->consulta->informacoes_beneficio->conta_corrente, '</td></tr>';
            echo '<tr><td>ORGÃO PAGADOR:</td><td>', $consulta_nb->consulta->informacoes_beneficio->orgao_pagador, '</td></tr>';
            echo '<tr><td>ESPÉCIE:</td><td>', $consulta_nb->consulta->informacoes_beneficio->especie, '</td></tr>';
            echo '<tr><td>DT. CONSULTA:</td><td>', $consulta_nb->consulta->informacoes_beneficio->data_consulta, '</td></tr>';
            echo '<tr><td>VALOR BENEFICIO:</td><td>', $consulta_nb->consulta->detalhamento->valor_beneficio, '</td></tr>';
            echo '<tr><td>VALOR MR:</td><td>', $consulta_nb->consulta->detalhamento->valor_mr, '</td></tr>';
            echo '<tr><td>VALOR DÉBITOS FIXOS:</td><td>', $consulta_nb->consulta->detalhamento->valor_debitos_fixos, '</td></tr>';
            echo '<tr><td>VALOR OUTROS CRÉDITOS:</td><td>', $consulta_nb->consulta->detalhamento->valor_outros_creditos, '</td></tr>';
            echo '<tr><td>CARTÃO RMC:</td><td>', $consulta_nb->consulta->detalhamento->cartao_rmc, '</td></tr>';
            echo '<tr><td>VL. CARTÃO RMC:</td><td>', $consulta_nb->consulta->detalhamento->valor_cartao_rmc, '</td></tr>';
            echo '<tr><td>VL. MARGEM:</td><td>', $consulta_nb->consulta->detalhamento->valor_margem_consignavel, '</td></tr>';
            echo '</tbody>';
            echo '</table>';

            echo '<table class="responstable" style="width: 100%">';
            echo '<thead>';
            echo '<tr>';
            echo '<th colspan="11">CONSIGNAÇÕES</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo '<td>SITUAÇÃO</td>';
            echo '<td>BANCO</td>';
            echo '<td>COD.</td>';
            echo '<td>INICIO</td>';
            echo '<td>TERMINO</td>';
            echo '<td>PARC.</td>';
            echo '<td>PARC. ABERTAS</td>';
            echo '<td>VALOR PARCELA</td>';
            echo '<td>SALDO APROX.</td>';
            echo '<td>COEFICIENTE</td>';
            echo '<td>TROCO</td>';
            echo '</tr>';
            foreach ($consulta_nb->consulta->consignacoes->consignacao as $key => $resultado2) {
                echo '<tr>';
                echo "<td>" . $resultado2->situacao . "</td>";
                echo "<td>" . $resultado2->banco . "</td>";
                echo "<td>" . $resultado2->codigo_banco . "</td>";
                echo "<td>" . $resultado2->data_inicio . "</td>";
                echo "<td>" . $resultado2->data_termino . "</td>";
                echo "<td>" . $resultado2->parcelas . "</td>";
                echo "<td>" . $resultado2->parcelas_aberto . "</td>";
                echo "<td>" . $resultado2->valor_parcela . "</td>";
                echo "<td>" . $resultado2->saldo_aproximado . "</td>";
                echo "<td>--</td>";
                echo "<td>--</td>";
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo $consulta_nb->msg;
        }
    }
} else {
    echo 'Não pode consultar sem número de beneficio INSS...';
}
    
