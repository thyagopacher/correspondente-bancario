<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";

    if(isset($_POST["quemfez"]) && $_POST["quemfez"] != NULL && $_POST["quemfez"] != ""){
        $and .= " and pessoa.nome like '%{$_POST["quemfez"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        if(strpos($_POST["data1"], "/")){
            $data1 = implode("-",array_reverse(explode("/", $_POST["data1"])));
            $and .= " and log.data >= '{$data1}'";
        }else{
            $and .= " and log.data >= '{$_POST["data1"]}'";
        }          
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        if(strpos($_POST["data2"], "/")){
            $data2 = implode("-",array_reverse(explode("/", $_POST["data2"])));
            $and .= " and log.data <= '{$data2}'";
        }else{
            $and .= " and log.data <= '{$_POST["data2"]}'";
        } 
    }
    if(isset($_POST["observacao"]) && $_POST["observacao"] != NULL && $_POST["observacao"] != ""){
        $and .= " and log.observacao like '%{$_POST["observacao"]}%'";
    }
    if(isset($_SESSION["codnivel"]) && $_SESSION["codnivel"] == 1){
        $andPessoa = " and (pessoa.codempresa = '{$_SESSION['codempresa']}')";
    }else{
        $andPessoa = " and pessoa.codempresa = '{$_SESSION['codempresa']}'";
    }
    $sql = "select codlog, DATE_FORMAT(data, '%d/%m/%Y') as data2, 
        DATE_FORMAT(hora, '%H:%i') as hora, pessoa.codpessoa, pessoa.nome as quemfez, pagina.nome as pagina, log.observacao
    from log 
    left join pagina on pagina.codpagina = log.codpagina
    inner join pessoa on pessoa.codpessoa = log.codpessoa $andPessoa
    where log.codempresa = '{$_SESSION['codempresa']}' {$and} order by log.data desc, log.hora desc";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        ?>
        
                                    
<table class="tabela_procurar table table-hover">
<thead>
                                <tr>
                                    <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1"
                                        colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                        Data
                                    </th>
                                    <th>
                                        Hora
                                    </th>
                                    <th>
                                        Por
                                    </th>
                                    <th>
                                        Obs
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($log = $conexao->resultadoArray($res)) {?>
                                <tr>
                                    <td>
                                        <?= $log["data2"] ?>
                                    </td>
                                    <td>
                                        <?= $log["hora"] ?>
                                    </td>
                                    <td>
                                        <?= $log["quemfez"] ?>
                                    </td>
                                    <td>
                                        <?= $log["observacao"] ?>
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