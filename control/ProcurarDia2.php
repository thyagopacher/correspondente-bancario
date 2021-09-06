<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/Dia.php";
    $conexao = new Conexao();
    $dia  = new Dia($conexao);
    
    $and     = "";
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and dia.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL){
        $and .= " and dia.dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $and .= " and dia.dtcadastro <= '{$_POST["data2"]}'";
    }
    $sql = "select coddia, DATE_FORMAT(dia.dtcadastro, '%d/%m/%Y') as dtcadastro2, DATE_FORMAT(data, '%d/%m/%Y') as data2, pessoa.nome as funcionario
    from dia
    inner join pessoa on pessoa.codpessoa = dia.codfuncionario
    where 1 = 1
    {$and} order by dia.dtcadastro desc";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    if($qtd > 0){
        ?>    
                        <table class="tabela_procurar table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        Dt. Cadastro
                                    </th>
                                    <th>
                                        Por
                                    </th>
                                    <th>
                                        Data
                                    </th>
<th>
                                        Opções
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($conta = $conexao->resultadoArray($res)) {?>
                                <tr>
                                    <td>
                                        <?= $conta["dtcadastro2"] ?>
                                    </td>
                                    <td>
                                        <?= $conta["funcionario"] ?>
                                    </td>
                                    <td>
                                        <?= $conta["data2"] ?>
                                    </td>
                                    <td>
                                        <?php
                                            echo '<a href="',$link,'?coddia=',$conta["coddia"],'" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                                            echo '<a href="#" onclick="excluirDia(',$conta["coddia"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                                        ?>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
    }

?>