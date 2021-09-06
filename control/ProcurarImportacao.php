<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
$conexao = new Conexao();

$and = "";
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and empresa.razao like '%{$_POST["nome"]}%'";
}
if (isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != "") {
    $and .= " and importacao.data >= '{$_POST["data1"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != "") {
    $and .= " and importacao.data <= '{$_POST["data2"]}'";
}        
$sql = "select importacao.codimportacao, importacao.codfuncionario, importacao.qtdimportado,
    importacao.qtdnimportado, DATE_FORMAT(importacao.data, '%d/%m/%Y') as data2, pessoa.nome as funcionario,
    carteira.nome as carteira, importacao.codcarteira, empresa.razao as filial, carteira.codcarteira, importacao.codempresa
    from importacao  
    inner join carteira on carteira.codcarteira = importacao.codcarteira and carteira.codempresa = importacao.codempresa   
    inner join empresa on empresa.codempresa = importacao.codempresa
    inner join pessoa on pessoa.codpessoa = importacao.codfuncionario
    where 1 = 1 {$and} and importacao.codempresa = '{$_SESSION['codempresa']}'";
$res = $conexao->comando($sql);
if ($res == FALSE) {
    die("Erro ocasionado por:" . mysqli_error($conexao->conexao));
}
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    ?>
    <div class="box-body">
        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row">
                
                </div>
                
                </div>
            </div>
            <div class="row">
                
                    <table
                           >
                        <thead>
                            <tr>
                                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1"
                                    colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                    Data Cad.
                                </th>
                                <th
                                    >
                                    Carteira 
                                </th>
                                <th
                                    >
                                    Filial
                                </th>
                                <th
                                    >
                                    Qtd. Cliente
                                </th>
                                    <th
                                    >
                                    Qtd. Pendente
                                </th>
                                <th
                                    colspan="1" aria-label="Engine version: activate to sort column ascending">
                                    Opções
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            
                            <?php while ($importacao = $conexao->resultadoArray($res)) { 
                                $sql = "select count(distinct(pessoa.codpessoa)) as qtd 
                                from pessoa 
                                inner join carteiracliente as cc on cc.codcliente = pessoa.codpessoa and cc.codempresa = pessoa.codempresa                                
                                where pessoa.codempresa = '{$importacao["codempresa"]}' 
                                and   cc.codcarteira = '{$importacao["codcarteira"]}'";
                                $qtdTotalCarteira = $conexao->comandoArray($sql);                                  
                                
                                if(!isset($qtdTotalCarteira["qtd"]) || $qtdTotalCarteira["qtd"] == NULL || $qtdTotalCarteira["qtd"] == 0){
                                    $sql = 'select count(1) as qtd 
                                    from pessoa where codimportacao = '. $importacao["codimportacao"]. ' and codempresa = '. $_SESSION["codempresa"];
                                    $qtdTotalCarteira = $conexao->comandoArray($sql);
                                }
                                
                                $sql = "select count(distinct(cc.codcliente)) as qtd
                                from carteiracliente as cc 
                                where cc.codempresa = '{$importacao["codempresa"]}'  
                                and   cc.codcarteira = '{$importacao["codcarteira"]}'
                                and cc.codcliente not in(select codpessoa from atendimento where codcarteira = cc.codcarteira and codempresa = cc.codempresa)";
                                $qtdPendente = $conexao->comandoArray($sql);
                                
                              
                                ?>
                            
                                <tr>
                                    <td>
                                        <?= $importacao["data2"] ?>
                                    </td>
                                    <td><?= $importacao["carteira"] ?></td>
                                    <td><?= $importacao["filial"] ?></td>
                                    <td><?= $qtdTotalCarteira["qtd"] ?></td>
                                    <td><?= $qtdPendente["qtd"] ?></td>
                                    <td>
                                        <?php echo '<a href="javascript: excluirImportacao2(', $importacao["codcarteira"], ')"><img src="/visao/recursos/img/excluir.png" alt="excluir carteira"/></a>'; ?>
                                        <?php echo '<a href="javascript: mostraRelatorio(', $importacao["codcarteira"] ,', 1)"><img src="../visao/recursos/img/excel.png" alt="baixar planilha"/></a>';?>
                                        <?php echo '<a href="javascript: mostraRelatorio(', $importacao["codcarteira"] ,', 2)"><img src="../visao/recursos/img/pdf.png" alt="baixar pdf"/></a>';?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <?php
} else {
    echo '';
}


include "../model/Log.php";
$log = new Log($conexao);
$log->codpessoa = $_SESSION['codpessoa'];

$log->acao = "procurar";
$log->observacao = "Procurado importação - em " . date('d/m/Y') . " - " . date('H:i');
$log->codpagina = "0";

$log->hora = date('H:i:s');
$log->inserir();
