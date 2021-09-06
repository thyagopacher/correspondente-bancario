<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";
    if(isset($_POST["codstatus"]) && $_POST["codstatus"] != NULL && $_POST["codstatus"] != ""){
        $and .= " and empresa.codstatus = '{$_POST["codstatus"]}'";
    }
    if(isset($_POST["codcategoria"]) && $_POST["codcategoria"] != NULL && $_POST["codcategoria"] != ""){
        $and .= " and empresa.codcategoria = '{$_POST["codcategoria"]}'";
    }
    if(isset($_POST["codramo"]) && $_POST["codramo"] != NULL && $_POST["codramo"] != ""){
        $and .= " and empresa.codramo = '{$_POST["codramo"]}'";
        $and .= " and empresa.codpessoa = {$_SESSION["codpessoa"]}";
    }
    if(isset($_POST["tipo"]) && $_POST["tipo"] != NULL && $_POST["tipo"] != ""){
        if($_SESSION["codnivel"] != '1'){
            $and .= " and empresa.codplano <> ''";
        }
    }
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and empresa.razao like '%".trim($_POST["nome"])."%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        $and .= " and empresa.dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and .= " and empresa.dtcadastro <= '{$_POST["data2"]}'";
    }
    if(isset($_POST["fornecedor"]) && $_POST["fornecedor"] != NULL && $_POST["fornecedor"] == "true"){
        $and .= " and empresa.codramo <> '7' and nomecontato <> ''";
    }elseif($_SESSION["codnivel"] != "1" && $_SESSION["codnivel"] != "19"){
        $and .= " and empresa.codempresa = '{$_SESSION['codempresa']}' and empresa.codramo = 6";
    }
    
    if(isset($_SESSION["codnivel"]) && $_SESSION["codnivel"] != NULL && $_SESSION["codnivel"] != "1"){
        $and .= " and empresa.codpessoa in(select codpessoa from pessoa where pessoa.codempresa = '{$_SESSION['codempresa']}')";
    }
 
    $sql = "select empresa.codempresa, empresa.razao, empresa.telefone, empresa.celular, 
    empresa.email, DATE_FORMAT(empresa.dtcadastro, '%d/%m/%Y') as data, status.nome as status, pessoa.nome as funcionario, empresa.codramo, empresa.codcategoria
    from empresa 
    inner join statusempresa as status on status.codstatus = empresa.codstatus
    inner join pessoa on pessoa.codpessoa = empresa.codpessoa
    where 1 = 1 {$and}";

    $res = $conexao->comando($sql);
    if($res == FALSE){
        die("Erro ocasionado por:". mysqli_error($conexao->conexao));
    }
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
                                        Razão
                                    </th>
                                    <th>
                                        Por
                                    </th>
                                    <th>
                                        E-mail
                                    </th>
<th>
                                        Opções
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($empresa = $conexao->resultadoArray($res)){?>
                                <tr>
                                    <td>
                                        <?= $empresa["data"] ?>
                                    </td>
                                    <td>
                                        <?= $empresa["razao"] ?>
                                    </td>
                                    <td>
                                        <?= $empresa["funcionario"] ?>
                                    </td>
                                    <td>
                                        <?= $empresa["email"] ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($empresa["codcategoria"] == 1){
                                            echo '<a href="Empresa.php?codempresa=', $empresa["codempresa"], '" title="Clique aqui para editar"><img style="width: 20px;" src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';                                            
                                        }elseif($empresa["codcategoria"] == 2){
                                            echo '<a href="Empresa.php?codempresa=', $empresa["codempresa"], '&codramo=6" title="Clique aqui para editar"><img style="width: 20px;" src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';                                            
                                        }elseif($empresa["codcategoria"] == 4){
                                            echo '<a href="Empresa.php?codempresa=', $empresa["codempresa"], '&codramo=6&tipo=p" title="Clique aqui para editar"><img style="width: 20px;" src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';                                            
                                        }
                                        echo '<a href="#" onclick="excluirEmpresa(', $empresa["codempresa"], ')" title="Clique aqui para excluir"><img style="width: 20px;" src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                                        ?>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                            
                        </table>

        <?php
        
    }

?>