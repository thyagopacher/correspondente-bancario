<?php
include("./Email.php");
include("../model/Conexao.php");
$conexao = new Conexao();
$resempresa = $conexao->comando("select codempresa, razao from empresa where codramo = 6 order by razao");
$qtdempresa = $conexao->qtdResultado($resempresa);
if ($qtdempresa > 0) {
    while ($empresa = $conexao->resultadoArray($resempresa)) {
        $sql = "select distinct baixa.codfuncionario, pessoa.email, pessoa.nome as funcionario
        from baixa
        inner join pessoa on pessoa.codpessoa = baixa.codfuncionario
        where baixa.dtcadastro >= '" . date("Y-m-") . "01' 
        and baixa.dtcadastro <= '" . date("Y-m-") . "30' 
        and baixa.enviado = 'n' and baixa.codempresa = '{$empresa["codempresa"]}'
        order by baixa.codfuncionario";
        $resEmail = $conexao->comando($sql);
        $qtdEmail = $conexao->qtdResultado($resEmail);
        if ($qtdEmail > 0) {
            while ($email2 = $conexao->resultadoArray($resEmail)) {
                $sql = "select DATE_FORMAT(baixa.dtcadastro, '%d/%m/%Y') as dtcadastro2, pessoa.nome as funcionario, baixa.codbaixa, baixa.codempresa,
                pessoa.email as email_funcionario, baixa.valor, pessoa.codpessoa
                from baixa 
                inner join pessoa on pessoa.codpessoa = baixa.codfuncionario
                where baixa.codfuncionario = '{$email2["codfuncionario"]}' 
                and baixa.dtcadastro >= '" . date("Y-m-") . "01' 
                and baixa.dtcadastro <= '" . date("Y-m-") . "30'  
                and baixa.enviado = 'n' order by baixa.dtcadastro";
                $resbaixa = $conexao->comando($sql)or die("Erro ao selecionar descrição da baixa:" . mysqli_error($conexao->conexao));
                $qtdbaixa = $conexao->qtdResultado($resbaixa);

                $texto = "";
                $totalVenda = 0.0;
                $totalMetaBaixa = 0.0;
                $totalRestanteBaixa = 0.0;
                if ($qtdbaixa > 0) {
                    $linha = 0;
                    while ($baixa = $conexao->resultadoArray($resbaixa)) {
                        $texto .= "<div style='border-bottom: 1px solid black;'>";
                        $texto .= "Dt. da Venda: {$baixa["dtcadastro2"]}<br>";
                        $texto .= "Funcionário: {$baixa["funcionario"]}<br>";
                        $texto .= "Valor da Venda:" . number_format($baixa["valor"], 2, ",", ".") . "<br>";
                        $sql = "select sum(baixa.valor) as valor
                        from baixa 
                        inner join pessoa on pessoa.codpessoa = baixa.codfuncionario
                        where baixa.codfuncionario = '{$email2["codfuncionario"]}' 
                        and baixa.dtcadastro >= '" . date("Y-m-") . "01' 
                        and baixa.dtcadastro <= '" . date("Y-m-") . "30'";
                        $totbaixa = $conexao->comandoArray($sql);
                        $texto .= "Total de Vendas: ".  number_format($totbaixa["valor"], 2, ",", ".")."<br>";
                        $sql = "select valor from metafuncionario where codfuncionario = '{$email2["codfuncionario"]}' 
                        and dtcadastro >= '" . date("Y-m-") . "01 00:00:00'    
                        and dtcadastro <= '" . date("Y-m-") . "30 23:59:59'            
                        order by codmeta desc";
                        $metaFuncionario = $conexao->comandoArray($sql);
                        
                        if($linha < $qtd - 1){
                            $sql = "select valor from baixa where codbaixa > '{$baixa["codbaixa"]}' and codfuncionario = '{$baixa["codfuncionario"]}' and codempresa = '{$baixa["codempresa"]}'";
                            $baixa_posterior = $conexao->comandoArray($sql);
                            $total_Vendas = $totbaixa["valor"] - $baixa_posterior["valor"];
                        }else{
                            $total_Vendas = $totbaixa["valor"];
                        }
                        $restante = $metaFuncionario["valor"] - $total_Vendas;
                        $texto .= "Total de Vendas: ".  number_format($total_Vendas, 2, ",", ".")."<br>";
                        $texto .= "Meta:" . number_format($metaFuncionario["valor"], 2, ",", ".") . "<br>";
                        $totalVenda += $baixa["valor"];
                        $totalMetaBaixa += $metaFuncionario["valor"];
                        $totalRestanteBaixa += $restante;
                        $texto .= "Restante:" . number_format($restante, 2, ",", ".") . "<br>";
                        $texto .= "</div>";
                        $conexao->comando("update baixa set enviado = 's' where codbaixa = '{$baixa["codbaixa"]}' and codempresa = '{$empresa["codempresa"]}'");
                        $linha++;
                    }
                    if ($texto != "") {
                        $texto .= "<br>Totalizando:<br>";
                        $texto .= "Filial: ". strtoupper($empresa["razao"]). "<br>";
                        $totalBaixaFilial = $conexao->comandoArray("select sum(valor) as valor 
                        from baixa where codempresa = '{$empresa["codempresa"]}'
                        and baixa.dtcadastro >= '" . date("Y-m-") . "01' 
                        and baixa.dtcadastro <= '" . date("Y-m-") . "30'");
                        $texto .= "Venda: R$ " . number_format($totalBaixaFilial["valor"], 2, ",", ".") . "<br>";
                        
                        $sql = "select sum(valor) as valor 
                        from metafuncionario 
                        where codfuncionario in(select codpessoa from pessoa where codempresa = '{$empresa["codempresa"]}')
                        and dtcadastro >= '" . date("Y-m-") . "01 00:00:00'    
                        and dtcadastro <= '" . date("Y-m-") . "30 23:59:59'";
                        $totalMetaFilial = $conexao->comandoArray($sql);
                        $texto .= "Meta: R$ " . number_format($totalMetaFilial["valor"], 2, ",", ".") . "<br>";
                        $totalRestanteFilial = $totalMetaFilial["valor"] - $totalBaixaFilial["valor"];
                        $texto .= "Restante: R$ " . number_format($totalRestanteFilial, 2, ",", ".") . "<br>";

                        $email = new Email();
                        $email->assunto = "Aviso de baixas South Negócios";
                        $email->mensagem = $texto;
                        $email->para = "Tiago South";
                        $email->para_email = "tiagofranco.silveira@gmail.com";
//                        $email->copia = "thyago pacher";
//                        $email->copia_email = "thyago.pacher@gmail.com";
                        $resEnvioEmail = $email->envia();
                    }
                }
            }
        }
    }
}