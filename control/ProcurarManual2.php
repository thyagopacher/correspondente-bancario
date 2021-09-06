<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/Manual.php";
    $conexao = new Conexao();
    $manual  = new Manual($conexao);
    
    $and     = "";
    $and .= ' and (empresa.codempresa = '.$_SESSION["codempresa"].' or empresa.codpessoa in(select codpessoa from pessoa where codempresa = '.$_SESSION["codempresa"].'))';
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and manual.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data"]) && $_POST["data"] != NULL){
        $and .= " and manual.dtcadastro >= '{$_POST["data"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $and .= " and manual.dtcadastro <= '{$_POST["data2"]}'";
    }
    $sql = "select codmanual, manual.nome, DATE_FORMAT(manual.dtcadastro, '%d/%m/%Y') as dtcadastro2, manual.arquivo, banco.nome as banco
    from manual
    inner join banco on banco.codbanco = manual.codbanco
    inner join empresa on empresa.codempresa = manual.codempresa
    where 1 = 1
    {$and} order by manual.dtcadastro desc";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        ?>
        
                                    
<table class="tabela_procurar table table-hover">
<thead>
                                <tr>
                                    <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1"
                                        colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                        Data Cad.
                                    </th>
                                    <th>
                                        Nome
                                    </th>
                                    <th>
                                        Banco
                                    </th>
                                    <th>
                                        Download
                                    </th>
<th>
                                        Opções
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($manual = $conexao->resultadoArray($res)) {?>
                                <tr>
                                    <td>
                                        <?= $manual["dtcadastro2"] ?>
                                    </td>
                                    <td>
                                        <?= $manual["nome"] ?>
                                    </td>
                                    <td>
                                        <?= $manual["banco"] ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo '<a href="../arquivos/',$manual["arquivo"],'" target="_blank">Download</a>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($manual["codcategoria"] == 1 || $manual["codcategoria"] == 6) {
                                            $caminhoTelaPessoa = "Cliente";
                                        } else {
                                            $caminhoTelaPessoa = "Pessoa";
                                        }
                                        if ($manual["codcategoria"] == 6) {
                                            $complementoCaminho = "&callcenter=true";
                                        }
                                        echo '<a href="Manual.php?codmanual=',$manual["codmanual"],'" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                                        echo '<a href="#" onclick="excluirManual(',$manual["codmanual"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                                        ?>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                            
                        </table>
                    </div>
                </div>
<!--                                    <div class="col-sm-5">
                        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                            Mostrando 1 de 10 do total <?= $qtd ?> resultados
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            <ul class="pagination">
                                <li class="paginate_button previous disabled" id="example2_previous">
                                    <a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0">Anterior</a>
                                </li>
                                <li class="paginate_button active">
                                    <a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0">1</a>
                                </li>
                                <li class="paginate_button ">
                                    <a href="#" aria-controls="example2" data-dt-idx="2" tabindex="0">2</a>
                                </li>
                                <li class="paginate_button ">
                                    <a href="#" aria-controls="example2" data-dt-idx="3" tabindex="0">3</a>
                                </li>
                                <li class="paginate_button ">
                                    <a href="#" aria-controls="example2" data-dt-idx="4" tabindex="0">4</a>
                                </li>
                                <li class="paginate_button ">
                                    <a href="#" aria-controls="example2" data-dt-idx="5" tabindex="0">5</a>
                                </li>
                                <li class="paginate_button ">
                                    <a href="#" aria-controls="example2" data-dt-idx="6" tabindex="0">6</a>
                                </li>
                                <li class="paginate_button next" id="example2_next">
                                    <a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0">Posterior</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
        <?php
        
    }

?>