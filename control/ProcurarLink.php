<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/Link.php";
    $conexao = new Conexao();
    $link  = new Link($conexao);
    
    $and     = "";
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and link.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data"]) && $_POST["data"] != NULL){
        $and .= " and link.dtcadastro >= '{$_POST["data"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $and .= " and link.dtcadastro <= '{$_POST["data2"]}'";
    }
    $sql = "select link.codlink, link.nome, DATE_FORMAT(link.dtcadastro, '%d/%m/%Y') as dtcadastro2, link.link
    from link
    inner join empresa on empresa.codempresa = link.codempresa
    where 1 = 1
    and (empresa.codempresa = {$_SESSION["codempresa"]} or empresa.codpessoa in(select codpessoa from pessoa where codempresa = {$_SESSION["codempresa"]}))
    {$and} order by link.dtcadastro desc";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        ?>
                        <table class="tabela_procurar">
                            <thead>
                                <tr>
                                    <th>
                                        Data Cad.
                                    </th>
                                    <th>
                                        Nome
                                    </th>  
                                    <th>
                                        Link
                                    </th>
                                    <th>
                                        Opções
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($link = $conexao->resultadoArray($res)) {?>
                                <tr>
                                    <td>
                                        <?= $link["dtcadastro2"] ?>
                                    </td>
                                    <td>
                                        <?= $link["nome"] ?>
                                    </td>
                                    <td>
                                        <?= $link["link"] ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($link["codcategoria"] == 1 || $link["codcategoria"] == 6) {
                                            $caminhoTelaPessoa = "Cliente";
                                        } else {
                                            $caminhoTelaPessoa = "Pessoa";
                                        }
                                        if ($sms["codcategoria"] == 6) {
                                            $complementoCaminho = "&callcenter=true";
                                        }
                                        echo '<a href="Link.php?codlink=',$link["codlink"],'" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                                        echo '<a href="#" onclick="excluirLink(',$link["codlink"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
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
 
        include "../model/Log.php";
        $log = new Log($conexao);
        
        $log->acao       = "procurar";
        $log->observacao = "Procurado link - em ". date('d/m/Y'). " - ". date('H:i');
        $log->codpagina  = "0";
        
        $log->hora = date('H:i:s');
        $log->inserir();        
 