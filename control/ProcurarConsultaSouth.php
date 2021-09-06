<?php

function __autoload($class_name) {
    if (file_exists('../model/' . $class_name . '.php')) {
        include '../model/' . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

session_start();

if(!isset($_SESSION["codempresa"]) || $_SESSION["codempresa"] == NULL || $_SESSION["codempresa"] == ""){
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
}

$southconsultap = $conexao->comandoArray('select validade, dtcadastro, qtdconsulta  from southconsulta as sc where sc.codempresa = '. $_SESSION["codempresa"]. ' order by codconsulta desc limit 1');

if(isset($southconsultap["validade"]) && $southconsultap["validade"] != NULL && $southconsultap["validade"] != ""){
    $diaMais        = date('Y-m-d', strtotime('+'.$southconsultap["validade"].' days', strtotime($southconsultap["dtcadastro"])));
    $time_inicial   = strtotime(date("Y-m-d"));
    $time_final     = strtotime($diaMais);
    $diferenca      = $time_final - $time_inicial; // 19522800 segundos
    $diasExpira     = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
    
    $diasMaisBrasil = date("d/m/Y", strtotime($diaMais));
}

//consultas usadas
$consultassouthp = $conexao->comandoArray('select count(1) as qtd from consultassouth as cs where cs.codempresa = '. $_SESSION["codempresa"]);
$limiteConsulta  = $southconsultap["qtdconsulta"] - $consultassouthp["qtd"];
if($limiteConsulta <= 0){
    die(json_encode(array('mensagem' => 'Acabaram seus créditos por favor consulte a administração!!!', 'situacao' => false)));
}

if (!isset($_POST["valor_procurar"]) || $_POST["valor_procurar"] == NULL || $_POST["valor_procurar"] == "") {
    die(json_encode(array('mensagem' => "Não pode consultar sem valor de busca!!!", 'situacao' => false)));
}
if (!isset($_POST["check_consulta"]) || $_POST["check_consulta"] == NULL || count($_POST["check_consulta"]) == 0) {
    die(json_encode(array('mensagem' => "Não pode consultar sem campo de busca!!!", 'situacao' => false)));
}elseif(count($_POST["check_consulta"]) > 1){
    die(json_encode(array('mensagem' => "Só pode pesquisar por um campo!!!", 'situacao' => false)));
}

$conexao            = new Conexao();
$cs                 = new ConsultasSouth($conexao);
$cs->campo          = $_POST["check_consulta"][0];
$cs->valor          = $_POST["valor_procurar"];
$resInserirConsulta = $cs->inserir();
if($resInserirConsulta == FALSE){
    die(json_encode(array('mensagem' => 'Não conseguiu gravar log de consulta!!!', 'situacao' => false)));
}

$info    = new InfoPesq();
if($cs->campo == 1){
    $retorno = $info->procuraCPF($cs->valor);
}elseif($cs->campo == 2){
    $retorno = $info->procuraNome($cs->valor);
}elseif($cs->campo == 6){
    $retorno = $info->procuraBeneficio($cs->valor);
}elseif($cs->campo == 7){
    $retorno = $info->procuraCNPJ($cs->valor);
}

if(isset($retorno) && $retorno != NULL && (!isset($retorno[0]) || $retorno[0] == NULL || $retorno[0] == "")){
    if($cs->campo == 1){
        $tabela .= '<h3>Informações pessoais:</h3>';
        $tabela .= 'Nome: '. $retorno->CLIENTE->NOME. ' - Profissão: '.$retorno->CLIENTE->PROFISSAO.'<br>';
        $tabela .= 'CPF: '.$retorno->CLIENTE->CPF. ' - Sexo:'. $retorno->CLIENTE->SEXO. '<br>';
        $tabela .= 'Nascimento: '.$retorno->CLIENTE->NASCTO. ' - Idade: '. $retorno->CLIENTE->IDADE. '<br>';
        $tabela .= 'Mãe: '. $retorno->CLIENTE->NOME_MAE. '<br>';
        $cliente = (array)$retorno->CLIENTE;
        $tabela .= 'Obito: '. $cliente["CONS.OBITO"].'<br>';

        if(isset($retorno->FONESMOVEL) && $retorno->FONESMOVEL != NULL && count($retorno->FONESMOVEL) > 0){
            $tabela .= '<h3>Telefones celulares:</h3>';
            $linhaTelefone = 0;
            foreach ($retorno->FONESMOVEL->FONEMOVEL as $key => $fonesmovel) {
                $tabela .= 'Tel. Celular: '.$fonesmovel[0]. ' - Operadora: '. $retorno->FONESMOVEL->OPERADORA[$linhaTelefone]. '<br>';
                $linhaTelefone++;
            }
        }
        
        if(isset($retorno->FONESFIXO) && $retorno->FONESFIXO != NULL && count($retorno->FONESFIXO) > 0){
            $tabela .= '<h3>Telefones fixo:</h3>';
            $linhaTelefone = 0;
            foreach ($retorno->FONESFIXO->FONEFIXO as $key => $fonesfixo) {
                $tabela .= 'Tel. fixo: '.$fonesfixo[0]. ' - Operadora: '. $retorno->FONESFIXO->OPERADORA[$linhaTelefone]. '<br>';
                $linhaTelefone++;
            }
        }
        
        if(isset($retorno->ENDERECOS->ENDERECO) && $retorno->ENDERECOS->ENDERECO != NULL && count($retorno->ENDERECOS->ENDERECO) > 0){
            $tabela .= '<h3>Seus endereços:</h3>';
            foreach ($retorno->ENDERECOS->ENDERECO as $key => $endereco) {
                $tabela .= 'Cidade: '. $endereco->CIDADE. ' - Estado: '.$endereco->ESTADO.'<br>';
                $tabela .= 'Logradouro: '. $endereco->LOGRADOURO. ' - Bairro: '. $endereco->BAIRRO.' - CEP: '. $endereco->CEP. '<br>';
            }
        }
        if(isset($retorno->EMAILS) && $retorno->EMAILS != NULL && count($retorno->EMAILS) > 0){
            $tabela .= '<h3>Seus e-mails:</h3>';
            foreach ($retorno->EMAILS as $key => $email) {
                $tabela .= 'E-mail: '.$email.'<br>';
            }
        }
        if(isset($retorno->TRABALHA->EMPRESA) && $retorno->TRABALHA->EMPRESA != NULL && count($retorno->TRABALHA->EMPRESA) > 0){
            $tabela .= '<h3>Empresas que trabalha:</h3>';
            foreach ($retorno->TRABALHA->EMPRESA as $key => $empresa) {
                $tabela .= 'CNPJ: '.$empresa->CNPJ.' - Situação: '.$empresa->SITUACAO_CNPJ.' - Razão: '.$empresa->RAZAO.'<br>';
                $tabela .= 'Atividade: '.$empresa->ATIVIDADE.' - Nat. Juridica: '.$empresa->NATUREZA_JURIDICA.'<br>';
                $tabela .= 'Profissão: '.$empresa->PROFISAO.' - Remuneração: '.$empresa->REMUNERACAO.'<br>';
                $tabela .= 'Entrada: '.$empresa->ENTRADA.' - Desligado: '.$empresa->DESLIGADO.'<br>';
            }
        }
    }elseif($cs->campo == 2){
        $tabela .= 'Beneficio: '.$retorno->dados_cadastrais->beneficio. ' - Nascimento: '. date("d/m/Y", strtotime($retorno->dados_cadastrais->nascto)). '<br>';
        
    }
    die(json_encode(array('tabela' => $tabela, 'situacao' => true)));
}elseif(isset($retorno[0])){
    if($cs->campo == 1){
        die(json_encode(array('mensagem' => 'Msg. retorno: '. $retorno[0], 'situacao' => false)));
    }elseif($cs->campo == 7){
        die(json_encode(array('mensagem' => 'Msg. retorno: '. $retorno->consulta->erro, 'situacao' => false)));
    }
}else{
    die(json_encode(array('mensagem' => 'Nada retornou da pesquisa!!!', 'situacao' => false)));
}
    