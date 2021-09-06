<?php
ob_start("ob_gzhandler");
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
include "../model/Conexao.php";
$conexao = new Conexao();
$pessoa = $conexao->comandoArray("select * from pessoa where codpessoa = '{$_POST["codpessoa"]}' and codempresa = '{$_SESSION['codempresa']}'");
?>
<link rel="stylesheet" type="text/css" href="/visao/recursos/css/tabela.css"> 
<div style="width: 920px;">
    <h4 style="text-decoration: underline; float: right; margin-right: 65px;font-size: 25px;margin-top: 10px;margin-bottom: 10px;">FICHA DE CADASTRO</h4>
    <table style="width: 900px;" class="tabela_formulario">
        <tr>
            <td>NOME</td>
            <td>
                <input type='text' style="width: 225px;" required name="nome" id="nome" size="50" maxlength="250" placeholder="Digite seu nome aqui" value="<?php
                if (isset($pessoa["nome"])) {
                    echo $pessoa["nome"];
                }
                ?>">
            </td>             
            <td style="width: 120px;">Status</td>
            <td>
                <select style="width: 225px;border: 1px solid red;" name="status" id="statusPessoa">
                    <option value="">--Selecione--</option>
                    <option value="a" <?php
                    if (isset($pessoa["status"]) && trim($pessoa["status"]) == "a") {
                        echo "selected";
                    }
                    ?>>Ativo</option>
                    <option value="i" <?php
                    if (isset($pessoa["status"]) && trim($pessoa["status"]) == "i") {
                        echo "selected";
                    }
                    ?>>Inativo</option>
                </select>
            </td>

        </tr>       
        <tr>
            <td>CPF</td>
            <td>
                <input style="width: 225px;" type='text' autocomplete="off" required name="cpf" id="cpf" class="cpf" value="<?php
                if (isset($pessoa["cpf"])) {
                    echo $pessoa["cpf"];
                }
                ?>" placeholder="CPF">
            </td>
            <td>RG</td>
            <td>
                <input style="width: 225px;" type='text' autocomplete="off"  <?= $requireForm ?> name="rg" id="rg" value="<?php
                if (isset($pessoa["rg"])) {
                    echo $pessoa["rg"];
                }
                ?>" placeholder="RG">
            </td>            
        </tr>
        <tr>
            <td>ORG. EMISSOR</td>
            <td><input style="width: 225px;" type='text' name="orgaoemissor" id="orgaoemissor" value="<?php
                if (isset($pessoa["orgaoemissor"])) {
                    echo $pessoa["orgaoemissor"];
                }
                ?>"/></td>
            <td>UF</td>
            <td>
                <select style="width: 225px;" name="ufrg" id="ufrg">
                    <?php
                    $resestado = $conexao->comando("select * from estado order by nome");
                    $qtdestado = $conexao->qtdResultado($resestado);
                    if ($qtdestado > 0) {
                        echo '<option value="">--Selecione--</option>';
                        while ($estado = $conexao->resultadoArray($resestado)) {
                            if (isset($pessoa["ufrg"]) && $pessoa["ufrg"] == $estado["codestado"]) {
                                echo '<option selected value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                            } else {
                                echo '<option value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                            }
                        }
                    } else {
                        echo '<option value="">--Nada encontrado--</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>DT. EMISSÃO</td>
            <td>
                <input style="width: 225px;" type='text' name="dtemissaorg" id="dtemissaorg" class="data" value="<?php
                if (isset($pessoa["dtemissaorg"])) {
                    echo implode("/", array_reverse(explode("-", $pessoa["dtemissaorg"])));
                }
                ?>" title="Digite aqui a data de emissão do RG"/>
            </td>
            <td>SEXO</td>
            <td>
                <select style="width: 225px;" name="sexo" id="sexo" <?= $requireForm ?>>
                    <option value="">--Selecione--</option>
                    <option value="m" <?php
                    if (isset($pessoa["sexo"]) && $pessoa["sexo"] == "m") {
                        echo "selected";
                    }
                    ?>>Masculino</option>
                    <option value="f" <?php
                    if (isset($pessoa["sexo"]) && $pessoa["sexo"] == "f") {
                        echo "selected";
                    }
                    ?>>Feminino</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>NASCIMENTO</td>
            <td><input style="width: 225px;" type='text' name="dtnascimento" id="dtnascimento" class="data" value="<?php
                if (isset($pessoa["dtnascimento"])) {
                    echo implode("/", array_reverse(explode("-", $pessoa["dtnascimento"])));
                }
                ?>" title="Digite aqui a data de nascimento"/></td>
            <td>LOCAL NASCIMENTO</td>
            <td><input style="width: 225px;" type='text' name="localnascimento" id="localnascimento" value="<?php
                if (isset($pessoa["localnascimento"])) {
                    echo $pessoa["localnascimento"];
                }
                ?>"/></td>
        </tr>
        <tr>
            <td>CEP</td>
            <td colspan="3">
                <input style="width: 225px;max-width: 225px;" <?= $requireForm ?> type='text' name="cep" id="cep" value="<?php
                if (isset($pessoa["cep"])) {
                    echo $pessoa["cep"];
                }
                ?>" title="Digite aqui cep" placeholder="Digite CEP">            
            </td>
        </tr>
        <tr>
            <td>TIPO LOG.</td>
            <td>
                <input list="tiposlogradouro" style="width: 225px;max-width: 225px;" <?= $requireForm ?> type='text' name="tipologradouro" id="tipologradouro" value="<?php
                if (isset($pessoa["tipologradouro"])) {
                    echo $pessoa["tipologradouro"];
                }
                ?>" title="Digite aqui tipo logradouro" placeholder="rua, bairro, avenida, etc...">            
                <datalist id="tiposlogradouro">
                    <option>AEROPORTO</option>
                    <option>ALAMEDA</option>
                    <option>APARTAMENTO</option>
                    <option>AVENIDA</option>
                    <option>BECO</option>
                    <option>BLOCO</option>
                    <option>CAMINHO</option>
                    <option>ESCADINHA</option>
                    <option>ESTAÇÃO</option>
                    <option>ESTRADA</option>
                    <option>FAZENDA</option>
                    <option>FORTALEZA</option>
                    <option>GALERIA</option>
                    <option>LADEIRA</option>
                    <option>LARGO</option>
                    <option>PRAÇA</option>
                    <option>PARQUE</option>
                    <option>PRAIA</option>
                    <option>QUADRA</option>
                    <option>QUILÔMETRO</option>
                    <option>QUINTA</option>
                    <option>RODOVIA</option>
                    <option>RUA</option>
                    <option>SUPER QUADRA</option>
                    <option>TRAVESSA</option>
                    <option>VIADUTO</option>
                    <option>VILA</option>                    
                </datalist>
            </td>
            <td>LOGRADOURO</td> 
            <td>
                <input style="width: 225px;max-width: 225px;" <?= $requireForm ?> type='text' name="logradouro" id="logradouro" value="<?php
                if (isset($pessoa["logradouro"])) {
                    echo $pessoa["logradouro"];
                }
                ?>" title="Digite aqui logradouro" placeholder="Digite aqui logradouro">
            </td>
        </tr>
        <tr>
            <td>NÚMERO</td>
            <td>
                <input style="width: 225px;max-width: 225px;" <?= $requireForm ?> type='text' name="numero" id="numero" value="<?php
                if (isset($pessoa["numero"])) {
                    echo $pessoa["numero"];
                }
                ?>" title="Digite aqui numero" placeholder="Digite aqui numero">
            </td>
            <td>BAIRRO</td>
            <td>
                <input style="width: 225px;max-width: 225px;" <?= $requireForm ?> type='text' name="bairro" id="bairro" value="<?php
                if (isset($pessoa["bairro"])) {
                    echo $pessoa["bairro"];
                }
                ?>" title="Digite aqui bairro" placeholder="Digite aqui bairro">
            </td>
        </tr>
        <tr>
            <td>CIDADE</td>
            <td>
                <input style="width: 225px;max-width: 225px;" <?= $requireForm ?> type='text' name="cidade" id="cidade" value="<?php
                if (isset($pessoa["cidade"])) {
                    echo $pessoa["cidade"];
                }
                ?>" title="Digite aqui cidade" placeholder="Digite aqui cidade">
            </td>
            <td>ESTADO</td>
            <td>
                <select style="width: 225px;" name="estado" id="uf">
                    <?php
                    $resestado = $conexao->comando("select * from estado order by nome");
                    $qtdestado = $conexao->qtdResultado($resestado);
                    if ($qtdestado > 0) {
                        echo '<option value="">--Selecione--</option>';
                        while ($estado = $conexao->resultadoArray($resestado)) {
                            if (isset($pessoa["estado"]) && strtolower($pessoa["estado"]) == strtolower($estado["sigla"])) {
                                echo '<option sigla="', $estado["sigla"], '" selected value="', $estado["sigla"], '">', strtoupper($estado["nome"]), '</option>';
                            } else {
                                echo '<option sigla="', $estado["sigla"], '" value="', $estado["sigla"], '">', strtoupper($estado["nome"]), '</option>';
                            }
                        }
                    } else {
                        echo '<option value="">--Nada encontrado--</option>';
                    }
                    ?>
                </select>                
            </td>
        </tr>
        <tr>
            <td>EST. CIVIL</td>
            <td>
                <select style="width: 225px;max-width: 230px;" name="estadocivil" id="estadocivil" <?= $requireForm ?>>
                    <?php
                    $resestado = $conexao->comando("select * from estadocivil order by nome");
                    $qtdestado = $conexao->qtdResultado($resestado);
                    if ($qtdestado > 0) {
                        echo '<option value="">--Selecione--</option>';
                        while ($estado = $conexao->resultadoArray($resestado)) {
                            if (isset($pessoa["estadocivil"]) && $pessoa["estadocivil"] == $estado["codestado"]) {
                                echo '<option selected value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                            } else {
                                echo '<option value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                            }
                        }
                    } else {
                        echo '<option value="">--Nada encontrado--</option>';
                    }
                    ?>
                </select>
            </td>
            <td>PAI</td>
            <td>
                <input style="width: 225px;" type='text' name="pai" id="pai" value="<?php
                if (isset($pessoa["pai"])) {
                    echo $pessoa["pai"];
                }
                ?>"/>
            </td>
        </tr>
        <tr>
            <td>MÃE</td>
            <td><input style="width: 225px;" type='text' name="mae" id="mae" value="<?php
                if (isset($pessoa["mae"])) {
                    echo $pessoa["mae"];
                }
                ?>"/></td>
            <td>E-MAIL</td>
            <td><input style="width: 225px;" type="email" name='email' id="email" value="<?php
                if (isset($pessoa["email"])) {
                    echo $pessoa["email"];
                }
                ?>"/></td>
        </tr>
        <tr>
            <td>Categoria</td>
            <td>
                <select style="width: 225px;" name="codcategoria" id="codcategoria">
                    <?php
                    if (!isset($_GET["callcenter"])) {
                        $andCategoria = " and codcategoria = 1;";
                    } else {
                        $andCategoria = "and codcategoria in(1,6)";
                    }
                    $rescategoria3 = $conexao->comando("select * from categoriapessoa where 1 = 1 {$andCategoria}");
                    $qtdcategoria3 = $conexao->qtdResultado($rescategoria3);
                    if ($qtdcategoria3 > 0) {
                        while ($categoria3 = $conexao->resultadoArray($rescategoria3)) {
                            if (isset($pessoa["codcategoria"]) && $pessoa["codcategoria"] == $categoria3["codcategoria"]) {
                                echo '<option selected value="', $categoria3["codcategoria"], '">', strtoupper($categoria3["nome"]), '</option>';
                            } else {
                                echo '<option value="', $categoria3["codcategoria"], '">', strtoupper($categoria3["nome"]), '</option>';
                            }
                        }
                    } else {
                        echo '<option value="">--Nada encontrado--</option>';
                    }
                    ?>
                </select>
            </td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <?php
    $sql = "select proposta.codproposta, proposta.nome, DATE_FORMAT(proposta.dtcadastro, '%d/%m/%Y') as dtcadastro2, proposta.codfuncionario, 
        funcionario.nome as funcionario, cliente.nome as cliente, cliente.cpf, proposta.vlsolicitado, convenio.nome as convenio, proposta.codbanco, proposta.codconvenio, 
        proposta.codtabela, proposta.prazo, banco.nome as banco, tabela.nome as tabela, status.nome as status, proposta.codstatus, proposta.codcliente, proposta.vlparcela, proposta.vlliberado, proposta.codbeneficio,
        status.cor, proposta.codbanco2, proposta.agencia, proposta.conta, proposta.operacao, proposta.poupanca, proposta.dtvenda, proposta.observacao, proposta.pendente,
        DATE_FORMAT(proposta.dtpago, '%d/%m/%Y') as dtpago2, proposta.dtpago
        from proposta
        inner join pessoa as funcionario on funcionario.codpessoa = proposta.codfuncionario 
        inner join pessoa as cliente on cliente.codpessoa = proposta.codcliente and cliente.codempresa = proposta.codempresa
        inner join convenio on convenio.codconvenio = proposta.codconvenio
        inner join banco on banco.codbanco = proposta.codbanco
        inner join tabela on tabela.codtabela = proposta.codtabela
        inner join statusproposta as status on status.codstatus = proposta.codstatus
        where 1 = 1
        and cliente.codpessoa = '{$pessoa["codpessoa"]}' and cliente.codempresa = '{$_SESSION['codempresa']}' 
        order by proposta.dtcadastro desc";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    if ($qtd > 0) {
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Cadastro</th>';
        echo '<th>Banco</th>';
        echo '<th>Convênio</th>';
        echo '<th>Tabela</th>';
        echo '<th>Prazo</th>';
        echo '<th>Valor</th>';
        echo '<th>Dt Pgto</th>';
        echo '<th>Status</th>';
        echo '<th>Operador</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while ($proposta = $conexao->resultadoArray($res)) {
            $sql = "select observacao from observacaoproposta as obs where obs.codempresa = '{$_SESSION['codempresa']}' and obs.codcliente = '{$proposta["codcliente"]}' and obs.observacao <> '' and codstatus = '7' order by codobservacao desc";
            $observacao = $conexao->comandoArray($sql);

            $sql = "select * from tabelaprazo where codtabela = '{$proposta["codtabela"]}' and prazoate >= '{$proposta["prazo"]}' and prazode <= '{$proposta["prazo"]}'";
            $comissoes = $conexao->comandoArray($sql);
            echo '<tr class="', $proposta["cor"], '">';
            echo '<td style="text-align: left;">', $proposta["dtcadastro2"], '</td>';
            echo '<td style="text-align: left;">', $proposta["banco"], '</td>';
            echo '<td style="text-align: left;">', $proposta["convenio"], '</td>';
            echo '<td style="text-align: left;">', $proposta["tabela"], '</td>';
            echo '<td style="text-align: left;">', $proposta["prazo"], '</td>';
            echo '<td style="text-align: left;">', number_format($proposta["vlsolicitado"], 2, ",", ""), '</td>';
            echo '<td style="text-align: left;">', $proposta["dtpago2"], '</td>';
            echo '<td title="', $observacao["observacao"], '" style="text-align: left;">', $proposta["status"], '</td>';
            echo '<td style="text-align: left;">', $proposta["funcionario"], '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
    echo '</div>';
    $html = ob_get_clean ();
    echo preg_replace('/\s+/', ' ',  $html);    
