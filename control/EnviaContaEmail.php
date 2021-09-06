<?php
include("../model/Conexao.php");
$conexao  = new Conexao();
$sql = "select distinct conta.codfuncionario, pessoa.email, pessoa.nome as funcionario
    from conta
    inner join pessoa on pessoa.codpessoa = conta.codfuncionario
where conta.dtpagamento = '0000-00-00 00:00:00' and conta.data <= '".date('Y-m-d')."' and conta.enviado = 'n'
order by conta.codfuncionario";
$resEmail = $conexao->comando($sql);
$qtdEmail = $conexao->qtdResultado($resEmail);
if ($qtdEmail > 0) {
    include("./Email.php");
    
    while($email2 = $conexao->resultadoArray($resEmail)){
        $sql = "select conta.nome, tipo.nome as tipo, DATE_FORMAT(conta.dtcadastro, '%d/%m/%Y') as dtcadastro2, pessoa.nome as funcionario, conta.movimentacao, conta.codconta, conta.codempresa,
        pessoa.email as email_funcionario, conta.valor, DATE_FORMAT(conta.data, '%d/%m/%Y') as data2
        from conta 
        inner join tipoconta as tipo on tipo.codtipo = conta.codtipo and tipo.codempresa = conta.codempresa
        inner join pessoa on pessoa.codpessoa = conta.codfuncionario
        where conta.codfuncionario = '{$email2["codfuncionario"]}' and conta.dtpagamento = '0000-00-00 00:00:00' and conta.data <= '".date('Y-m-d')."' and conta.enviado = 'n'";
        $resconta = $conexao->comando($sql)or die("Erro ao selecionar descrição da conta:". mysqli_error($conexao->conexao));
        $qtdconta = $conexao->qtdResultado($resconta);
        
        $texto    = "";
        if($qtdconta > 0){
            while($conta = $conexao->resultadoArray($resconta)){
                $texto .= "<div style='border-bottom: 1px solid black;'>";
                $texto .= "Conta: {$conta["nome"]} - Dt. Cadastro: {$conta["dtcadastro2"]}<br>";
                $texto .= "Tipo: {$conta["tipo"]} - Funcionário: {$conta["funcionario"]}<br>";
                $texto .= "Vencimento {$conta["data2"]}<br>";
                $texto .= "Movimentação: ".trocaMovimentacao($conta["movimentacao"]) . " - Valor:". number_format($conta["valor"], 2, ",", ".");
                $texto .= "</div>";
                $conexao->comando("update conta set enviado = 's' where codconta = '{$conta["codconta"]}' and codempresa = '{$conta["codempresa"]}'");               
            }
            if($texto != ""){
                $email = new Email();
                $email->assunto    = "Aviso de vencimento de contas South Negócios";
                $email->mensagem   = $texto;
                $email->para       = $email2["funcionario"];
                $email->para_email = $email2["email"];
                $resEnvioEmail = $email->envia();
            }
        }
    }
}

function trocaMovimentacao($movimentacao){
    switch ($movimentacao) {
        case "D":
            $movimentacao = "Despesa";
            break;
        case "R":
            $movimentacao = "Receita";
            break;
    }
    return $movimentacao;
}