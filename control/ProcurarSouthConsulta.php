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

    $res = $conexao->comando("select codconsulta, empresa.razao, sc.qtdconsulta, DATE_FORMAT(sc.dtcadastro, '%d/%m/%Y %H:%i:%s') as dtcadastro2 
        from southconsulta as sc
        inner join empresa on empresa.codempresa = sc.codempresa 
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
                                        Qtd. Consulta
                                    </th>
                                    <th>
                                        Opções
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($sc = $conexao->resultadoArray($res)) {?>
                                <tr>
                                    <td>
                                        <?= $sc["dtcadastro2"] ?>
                                    </td>
                                    <td>
                                        <?= $sc["razao"] ?>
                                    </td>
                                    <td>
                                        <?= $sc["qtdconsulta"] ?>
                                    </td>
                                    <td>
                                        <a href="?codconsulta=<?=$sc["codconsulta"]?>" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>
                                        <a href="javascript: excluirSouthConsulta(<?=$sc["codconsulta"]?>)" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>
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