<?php

    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
include "../model/Conexao.php";
$and = "";
if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
    $and .= " and agenda.dtcadastro >= '{$_POST["data1"]} 00:00:00'";
}
if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
    $and .= " and agenda.dtcadastro <= '{$_POST["data2"]} 23:59:59'";
}
if(isset($_POST["operador"]) && $_POST["operador"] != NULL && $_POST["operador"] != ""){
    $and .= " and agenda.codfuncionario = '{$_POST["operador"]}'";
}
if(isset($_POST["codstatus"]) && $_POST["codstatus"] != NULL && $_POST["codstatus"] != ""){
    $and .= " and agenda.codstatus = '{$_POST["codstatus"]}'";
}
$conexao   = new Conexao();
$sql = "select status.nome as status, funcionario.nome as funcionario, DATE_FORMAT(agenda.dtcadastro, '%d/%m/%y') as dtcadastro2, 
cliente.nome as cliente, DATE_FORMAT(agenda.dtagenda, '%d/%m/%y') as dtagenda2, nivel.nome as nivel, cliente.cpf
from agenda
inner join statuspessoa as status on status.codstatus = agenda.codstatus
inner join pessoa as funcionario on funcionario.codpessoa = agenda.codfuncionario and funcionario.codempresa = agenda.codempresa
inner join nivel on nivel.codnivel = funcionario.codnivel 
inner join pessoa as cliente on cliente.codpessoa = agenda.codpessoa and cliente.codempresa = agenda.codempresa
where agenda.codempresa = '{$_SESSION['codempresa']}' {$and} order by agenda.dtcadastro desc";
$resagenda = $conexao->comando($sql)or die("<pre>$sql</pre>");
$qtdagenda = $conexao->qtdResultado($resagenda);
if($qtdagenda > 0){
        ?>
        
                                    
<table class="tabela_procurar table table-hover">
<thead>
                                <tr>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Operador
                                    </th>
                                    <th>
                                        Dt.Cadastro
                                    </th>
                                    <th>
                                        Cliente
                                    </th>
                                    <th>
                                        CPF
                                    </th>
                                    <th>
                                        Agendado P/
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($agenda = $conexao->resultadoArray($resagenda)) {?>
                                <tr>
                                    <td>
                                        <?= $agenda["status"] ?>
                                    </td>
                                    <td>
                                        <?= $agenda["funcionario"] ?>
                                    </td>
                                    <td>
                                        <?= $agenda["dtcadastro2"]?>
                                    </td>
                                    <td>
                                        <?= $agenda["cliente"] ?>
                                    </td>
                                    <td>
                                        <?= $agenda["cpf"] ?>
                                    </td>
                                    <td>
                                        <?= $agenda["dtagenda2"]?>
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