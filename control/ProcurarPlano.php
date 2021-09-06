<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    
    $and     = "";
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and plano.nome like '%{$_POST["nome"]}%'";
    }

    $sql = "select *
    from plano
    where 1 = 1
    {$and} order by plano.nome";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
if ($qtd > 0) {
    
        ?>
        
                                    
<table class="tabela_procurar">
<thead>
                                <tr>
                                    <th>
                                        Nome
                                    </th>
                                    <th>
                                        Qtd. Filial
                                    </th>
                                    <th>
                                        Qtd. Usuário Correspondente
                                    </th>
                                    <th>
                                        Qtd. Usuário Filial
                                    </th>
                                    <th>
                                        Vl. Mensalidade
                                    </th>
<th>
                                        Opções
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($plano = $conexao->resultadoArray($res)) {?>
                                <tr>
                                    <td>
                                        <?= $plano["nome"] ?>
                                    </td>
                                    <td>
                                        <?= $plano["qtdfilial"] ?>
                                    </td>
                                    <td>
                                        <?= $plano["qtdusuariomatriz"] ?>
                                    </td>
                                    <td>
                                        <?= $plano["qtdusuariofilial"] ?>
                                    </td>
                                    <td>
                                        <?= number_format($plano["vlmensalidade"], 2, ",", "") ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo '<a href="Plano.php?codplano=', $plano["codplano"], $complementoCaminho, '" title="Clique aqui para editar"><img style="width: 20px;" src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                                        echo '<a href="#" onclick="excluirPlano(', $plano["codplano"], ')" title="Clique aqui para excluir"><img style="width: 20px;" src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                                        ?>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th rowspan="1" colspan="1">
                                        Nome
                                    </th>
                                    <th rowspan="1" colspan="1">
                                        Qtd. Filial
                                    </th>
                                    <th rowspan="1" colspan="1">
                                        Qtd. Usuário Matriz
                                    </th>
                                    <th rowspan="1" colspan="1">
                                        Qtd. Usuário Filial	
                                    </th>
                                    <th rowspan="1" colspan="1">
                                        Vl. Mensalidade
                                    </th>
                                    <th rowspan="1" colspan="1">
                                        Opções
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
    }

?>