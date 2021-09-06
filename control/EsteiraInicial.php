<?php

/*
 * @author Thyago Henrique Pacher - thyago.pacher@gmail.com
 */
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
}

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
$cache = new Cache(); 
$telefone = new Telefone($conexao);
//$esteiraTela = $cache->read('esteira_tela_' . $_SESSION['codempresa'] . '_' . $_SESSION["codnivel"] . '_' . $_SESSION["codpessoa"]);
if (!isset($esteiraTela) || $esteiraTela == NULL) {
    if ($_SESSION["codnivel"] == '16') {
        $and = ' and proposta.codfuncionario = ' . $_SESSION["codpessoa"];
    } else {
        $and = '';
    }
    $sql = "select cliente.nome as cliente, DATE_FORMAT(proposta.dtcadastro, '%d/%m/%Y') as dtcadastro2, 
                                                         proposta.codproposta, proposta.codcliente, status.nome as status, proposta.prazo, 
                                                         proposta.vlsolicitado, banco.nome as banco, funcionario.nome as operador, proposta.codstatus
                                                         from proposta 
                                                         inner join pessoa as cliente on cliente.codpessoa = proposta.codcliente 
                                                         inner join pessoa as funcionario on funcionario.codpessoa = proposta.codfuncionario
                                                         inner join banco on banco.codbanco = proposta.codbanco
                                                         inner join statusproposta as status on status.codstatus = proposta.codstatus  
                                                         inner join empresa on empresa.codempresa = proposta.codempresa
                                                         where proposta.codstatus <>  22
                                                         and (empresa.codempresa = '{$_SESSION['codempresa']}' or empresa.codpessoa in(select codpessoa from pessoa where codempresa = {$_SESSION["codempresa"]}))
                                                         {$and} order by proposta.codproposta desc limit 10";    
    $resproposta = $conexao->comando($sql) or die("<pre>$sql</pre>");
    $qtdproposta = $conexao->qtdResultado($resproposta);

    if ($qtdproposta > 0) {
        
        while ($proposta = $conexao->resultadoArray($resproposta)) {
            $esteiraTela .= '<div style="cursor: pointer;" onclick="mostraLinhaProposta(' . $proposta["codproposta"] . ')" class="box-header">';
            $esteiraTela .= '<i class="fa fa-th"></i>';
            $esteiraTela .= '<h3 style="width: 245px; font-size: 14px !important" class="box-title">' . $proposta["cliente"] . '</h3>';
            $esteiraTela .= '<small class="label label-default"><i class="fa fa-clock-o"></i>' . $proposta["dtcadastro2"] . '</small>';
            $esteiraTela .= '<div class="box-tools pull-right">';
            $esteiraTela .= $proposta["status"];
            $esteiraTela .= '<a href="javascript: mostraLinhaProposta(' . $proposta["codproposta"] . ')" class="btn bg-teal btn-sm" data-widget="collapse"><i id="icone_botao_proposta' . $proposta["codproposta"] . '" class="fa fa-plus"></i></a>';
            $esteiraTela .= '</div>';
            $esteiraTela .= '</div>';
            $esteiraTela .= '<div class="box-body border-radius-none" id="proposta_oculta' . $proposta["codproposta"] . '" style="display: none;">';
            $esteiraTela .= $proposta["banco"] . ' ' . $proposta["prazo"] . 'X - Vlr. CL: R$ ' . number_format($proposta["vlsolicitado"], 2, ',', '.');
            $esteiraTela .= ' Operador: ' . $proposta["operador"] . '<br>';
            if (isset($proposta["codstatus"]) && $proposta["codstatus"] != NULL && $proposta["codstatus"] == '3') {
                $esteiraTela .= '<button onclick="clienteAvisado(' . $proposta["codproposta"] . ');">Cliente Avisado</button><br>';
            }

            $sql = 'select numero from telefone where codempresa = ' . $_SESSION["codempresa"] . ' and codpessoa = ' . $proposta["codcliente"];
            $restelefone = $conexao->comando($sql);
            $qtdtelefone = $conexao->qtdResultado($restelefone);
            if ($qtdtelefone > 0) {
                $esteiraTela .= '<ul style="list-style-type: none;">';
                while ($telefonep = $conexao->resultadoArray($restelefone)) {
                    $esteiraTela .= '<li>';
                    $esteiraTela .= $telefone->arrumaTelefone($telefonep["numero"]);
                    $esteiraTela .= '</li>';
                }
                $esteiraTela .= '</ul>';
            }
            $esteiraTela .= '</div>';
        }
    }
    $cache->save('esteira_tela_' . $_SESSION['codempresa'] . '_' . $_SESSION["codnivel"] . '_' . $_SESSION["codpessoa"], $esteiraTela, '5 minutes');
}
echo $esteiraTela;
