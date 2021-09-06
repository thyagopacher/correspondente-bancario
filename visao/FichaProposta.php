<!DOCTYPE html>
<!--
@author Thyago Henrique Pacher - thyago.pacher@gmail.com
-->
<?php
    if(!isset($_GET["codproposta"]) || $_GET["codproposta"] == NULL || $_GET["codproposta"] == ""){
        die('<script>alert("Não pode abrir fica sem definir proposta!!!");window.close();</script>');
    }

    session_start();
    include '../model/Conexao.php';
    include '../model/Telefone.php';
    $conexao    = new Conexao();
    $telefone   = new Telefone($conexao);
    
    $empresap   = $conexao->comandoArray('select * from empresa where codempresa = '. $_SESSION["codempresa"]);
    $sql = 'select pessoa.*, DATE_FORMAT(pessoa.dtnascimento, "%d/%m/%Y") as dtnascimento, 
    DATE_FORMAT(pessoa.dtemissaorg, "%d/%m/%Y") as dtemissaorg 
    from pessoa 
    where codempresa = '. $_SESSION["codempresa"].' 
    and   codpessoa = '. $_GET["codpessoa"];
    $pessoap    = $conexao->comandoArray($sql);
    $telefonep  = $conexao->comandoArray('select numero from telefone 
        where codempresa = '. $_SESSION["codempresa"].' 
        and codtipo   = 1    
        and numero <> ""
        and codpessoa = '. $_GET["codpessoa"]);
    $celularp  = $conexao->comandoArray('select numero from telefone 
        where codempresa = '. $_SESSION["codempresa"].' 
        and codtipo   = 3    
        and numero <> ""
        and codpessoa = '. $_GET["codpessoa"]);
    

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>FICHA DE PROPOSTA</title>
        <style>
            body{
                font-family: arial;
            }
            h3.titulo_central{
                text-align: center; font-size: 18px;
            }
            .linha_cinza{
                background: #F1F1F1;
            }
            .coluna_cinza{
                background: rgba(128, 128, 128, 0.34)
            }
        </style>
    </head>
    <body>
        
        <?php
            echo '<h3 class="titulo_central">FICHA DE PROPOSTA</h3>';
            echo '<p style="text-align: center;">',$empresap["razao"], '</p>';
            echo '<p style="text-align: center;">';
            echo "{$empresap["tipologradouro"]} {$empresap["logradouro"]}, {$empresap["numero"]} - {$empresap["bairro"]} - {$empresap["cidade"]}/{$empresap["estado"]} - CEP: {$empresap["cep"]}<br>";
            echo "Fones: {$empresap["telefone"]} | {$empresap["celular"]}";
            echo '</p>';
            
            echo '<table style="width: 100%;">';
            echo "<tr><td colspan=4>NOME CLIENTE</td><td>CPF</td></tr>";
            echo "<tr class='linha_cinza'><td colspan=4>{$pessoap["nome"]}</td><td>{$pessoap["cpf"]}</td></tr>";
            echo "<tr><td>DOC DE IDENTIDADE</td><td>ORGAO EMISSOR</td><td>UF</td><td>DATA EMISSAO</td><td>DATA DE NASCIMENTO</td></tr>";
            echo "<tr class='linha_cinza'><td>{$pessoap["rg"]}</td><td>{$pessoap["orgaoemissor"]}</td><td>{$pessoap["ufrg"]}</td><td>{$pessoap["dtemissaorg"]}</td><td>{$pessoap["dtnascimento"]}</td></tr>";
            echo "<tr><td>NATURALIDADE</td><td>NACIONALIDADE</td><td>SEXO</td><td colspan=2>ESTADO CIVIL</td></tr>";
            echo "<tr class='linha_cinza'><td>{$pessoap["cidade"]}</td><td>Brasil</td><td>{$pessoap["sexo"]}</td><td colspan=2>{$pessoap["estadocivil"]}</td></tr>";
            echo "<tr><td>CEP</td><td colspan=3>ENDEREÇO</td><td>NUMERO</td></tr>";
            echo "<tr class='linha_cinza'><td>{$pessoap["cep"]}</td><td colspan=3>{$pessoap["tipologradouro"]}{$pessoap["logradouro"]}</td><td>{$pessoap["numero"]}</td></tr>";
            echo "<tr><td colspan=2>BAIRRO</td><td colspan=2>CIDADE</td><td>UF</td></tr>";
            echo "<tr class='linha_cinza'><td colspan=2>{$pessoap["bairro"]}</td><td colspan=2>{$pessoap["cidade"]}</td><td>{$pessoap["estado"]}</td></tr>";
            echo "<tr><td colspan=2>COMPLEMENTO</td><td colspan=2>TELEFONE</td><td colspan=2>CELULAR</td></tr>";
            $telefoneNumero = $telefone->arrumaTelefone($telefonep["numero"]);
            $celularNumero  = $telefone->arrumaTelefone($celularp["numero"]);
            if($telefoneNumero == ""){ 
                $telefoneNumero = "--";
            }
            if($celularNumero == ""){
                $celularNumero = "--";
            }
            echo "<tr class='linha_cinza'><td colspan=2></td><td colspan=2>{$telefoneNumero}</td><td colspan=2>{$celularNumero}</td></tr>";
            echo '</table>';
            
            echo '<h3 class="titulo_central">DADOS DA PROPOSTA</h3>';
      
            $sql = 'select beneficiocliente.*, especie.nome as especie, banco.nome as banco 
                from beneficiocliente 
                inner join especie on especie.codespecie = beneficiocliente.codespecie
                inner join banco on banco.codbanco = beneficiocliente.codbanco
                where beneficiocliente.codempresa = '. $_SESSION["codempresa"].' 
                and beneficiocliente.codpessoa = '. $_GET["codpessoa"];
            $resbeneficio  = $conexao->comando($sql);    
            $qtdbeneficio  = $conexao->qtdResultado($resbeneficio);
            if($qtdbeneficio > 0){
                while($beneficiop = $conexao->resultadoArray($resbeneficio)){
                    if($beneficiop["especie"] == ""){
                        $beneficiop["especie"] = '--';
                    }
                    if($beneficiop["numbeneficio"] == ""){
                        $beneficiop["numbeneficio"] = '--';
                    }
                    echo '<table style="width: 30%; float: left; margin-right: 30%">';
                    echo "<tr><td class='coluna_cinza'>Espécie</td><td style='border-top: 1px solid black;'>{$beneficiop["especie"]}</td></tr>";
                    echo "<tr><td class='coluna_cinza'>Numero Beneficio</td><td style='border-top: 1px solid black;'>{$beneficiop["numbeneficio"]}</td></tr>";
                    echo '</table>';

                    echo '<table style="width: 30%; float: left;">';
                    echo "<tr><td class='coluna_cinza'>Banco para Depósito</td><td style='border-top: 1px solid black;'>{$beneficiop["banco"]}</td></tr>";
                    echo "<tr><td class='coluna_cinza'>Agência</td><td style='border-top: 1px solid black;'>{$beneficiop["agencia"]}</td></tr>";
                    echo "<tr><td class='coluna_cinza'>Conta</td><td style='border-top: 1px solid black;'>{$beneficiop["contacorrente"]}</td></tr>";
                    echo "<tr><td class='coluna_cinza'>Tipo de Conta</td><td style='border-top: 1px solid black;'>   </td></tr>";
                    echo "<tr><td class='coluna_cinza'>Tipo de Operação</td><td style='border-top: 1px solid black;'>   </td></tr>";
                    echo '</table>';

                    echo '<table style="width: 100%;">';
                    echo "<tr><td>CONVÊNIO</td><td>BANCO PORTADO</td><td>PARC. PAGAS</td><td>TOTAL PARC.</td><td>PRAZO</td><td>PARCELA</td><td>LIQUIDO PREVISTO</td></tr>";
                    echo "<tr><td>CONVÊNIO</td><td>BANCO PORTADO</td><td>PARC. PAGAS</td><td>TOTAL PARC.</td><td>PRAZO</td><td>PARCELA</td><td>LIQUIDO PREVISTO</td></tr>";
                    echo '</table>';                    
                }
            }

            echo '<table style="width: 100%;">';
            echo "<tr><td>ASSINATURA DO CLIENTE: ________________________________________  </td><td>LÍQUIDO PREVISTO:</td></tr>";
            echo "<tr><td>REPRESENTANTE: ________________________________________________  </td><td>DATA: ____/____/________</td></tr>";
            echo '</table>';
            echo '<p style="font-weight: bolder;">Obs.: O crédito liberado só começará a ser descontado após a quitação das parcelas a vencer.</p>';
        ?>
        
    </body>
</html>
