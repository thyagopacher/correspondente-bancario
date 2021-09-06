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
        $and .= " and empresa.nome like '%{$_POST["nome"]}%'";
    }

    $res = $conexao->comando("select cs.codconsulta, empresa.razao, pessoa.nome as funcionario, 
        DATE_FORMAT(dtcadastro, '%d/%m/%Y %H:%i:%s') as dtcadastro2, empresa.razao, cs.valor, cs.campo 
        from consultassouth as cs
        inner join empresa on empresa.codempresa = cs.codempresa 
        inner join pessoa  on pessoa.codpessoa   = cs.codfuncionario
        where 1 = 1 {$and} 
        order by sc.dtcadastro desc");
    $qtd = $conexao->qtdResultado($res);
    if ($qtd > 0) {
    
        ?>
        
                                    
<table class="tabela_procurar">
<thead>
                                <tr>
                                    <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1"
                                        colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                        Dt. Cadastro
                                    </th>
                                    <th>
                                        Empresa
                                    </th>
                                    <th>
                                        Funcionário
                                    </th>
                                    <th>
                                        Campo
                                    </th>
                                    <th>
                                        Valor
                                    </th>
                                    <th>
                                        Opções
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($cs = $conexao->resultadoArray($res)) {?>
                                <tr>
                                    <td>
                                        <?= $cs["dtcadastro2"] ?>
                                    </td>
                                    <td>
                                        <?= $cs["razao"] ?>
                                    </td>
                                    <td>
                                        <?= $cs["funcionario"] ?>
                                    </td>
                                    <td>
                                        <?= $cs["campo"] ?>
                                    </td>
                                    <td>
                                        <?= $cs["valor"] ?>
                                    </td>
                                    <td>
                                        <a href="?codconsulta=<?=$cs["codconsulta"]?>" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>
                                        <a href="javascript: excluirConsulta(<?=$cs["codconsulta"]?>)" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>
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