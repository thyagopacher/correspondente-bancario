<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/Baixa.php";
    $conexao = new Conexao();
    $baixa  = new Baixa($conexao);
    
    $and     = "";
    if(isset($_POST["cpf"]) && $_POST["cpf"] != NULL && $_POST["cpf"] != ""){
        $and .= " and baixa.cpf like '%{$_POST["cpf"]}%'";
    }
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and baixa.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL){
        $and .= " and baixa.dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $and .= " and baixa.dtcadastro <= '{$_POST["data2"]}'";
    }
    if(isset($_POST["codfuncionario"]) && $_POST["codfuncionario"] != NULL){
        $and .= " and baixa.codfuncionario = '{$_POST["codfuncionario"]}'";
    }
    $sql = "select * from nivel where codnivel = '{$_SESSION["codnivel"]}'";
    $nivel_logado = $conexao->comandoArray($sql);
    if(isset($nivel_logado["nome"]) && $nivel_logado["nome"] == "OPERADOR"){
        $and .= " and baixa.codfuncionario = '{$_SESSION['codpessoa']}'";
    }
    if($_POST["agrupar_colaborador"] == "n"){
        $valores = ", baixa.cpf, baixa.valor";
        $groupBy = "";
    }else{
        $groupBy = " group by baixa.codfuncionario";
        $valores = ", sum(baixa.valor) as valor";
    }
    $sql = "select baixa.codbaixa, 
    DATE_FORMAT(baixa.dtcadastro, '%d/%m/%Y') as dtcadastro2, pessoa.nome as funcionario, 
    baixa.codempresa, DATE_FORMAT(baixa.dtcadastro, '%Y-%m-%d') as dtcadastro, 
    baixa.codfuncionario {$valores}
    from baixa
    inner join pessoa on pessoa.codpessoa = baixa.codfuncionario
    where 1 = 1 and baixa.codempresa = {$_SESSION["codempresa"]}
    {$and} 
    {$groupBy}
    order by baixa.dtcadastro desc";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    if ($qtd > 0) {
        echo 'Encontrou ', $qtd, ' resultados<br>';
        ?>
                                                        
                        <table class="tabela_procurar  table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        Data Cad.
                                    </th>
                                    <th>
                                        Func.
                                    </th>
                                    <?php if($_POST["agrupar_colaborador"] == "n"){?>
                                    <th>
                                        CPF
                                    </th>
                                    <?php }?>
                                    <th>
                                        Valor
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($baixa = $conexao->resultadoArray($res)) {?>
                                <tr>
                                    <td>
                                        <?= $baixa["dtcadastro2"] ?>
                                    </td>
                                    <td>
                                        <?= $baixa["funcionario"] ?>
                                    </td>
                                    <?php if($_POST["agrupar_colaborador"] == "n"){?>
                                    <td>
                                        <?= $baixa["cpf"] ?>
                                    </td>
                                    <?php }?>
                                    <td>
                                        <?= number_format($baixa["valor"], 2, ",", ".") ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo '<a href="?codbaixa=', $baixa["codbaixa"],'" title="Clique aqui para editar"><img style="width: 20px;" src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                                        echo '<a href="javascript: excluirBaixa(',$baixa["codbaixa"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';                                        ?>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                            
                        </table>
 
        <?php
        
    }

?>