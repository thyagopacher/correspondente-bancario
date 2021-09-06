<?php
    session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }      
    
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";
    $sql     = "select * from nivel 
    where ((codempresa = '{$_SESSION['codempresa']}' and nivel.padrao <> 's') or nivel.padrao = 's')
    and codnivel = '{$_SESSION["codnivel"]}'";
    $nivel   = $conexao->comandoArray($sql);
    if(strtoupper($nivel["nome"]) != "ADMINISTRADOR"){
        $_POST["codfuncionario"] = $_SESSION['codpessoa'];
        $_POST["data1"]          = date('Y-m-d');
        $_POST["data2"]          = date('Y-m-d');
    }
    if(isset($_POST["codcategoria"]) && $_POST["codcategoria"] != NULL && $_POST["codcategoria"] != ""){
        $and .= " and cliente.codcategoria = '{$_POST["codcategoria"]}'";
    }
    if(isset($_POST["codcliente"]) && $_POST["codcliente"] != NULL && $_POST["codcliente"] != ""){
        $and .= " and agenda.codpessoa = '{$_POST["codcliente"]}'";
    }
    if(isset($_POST["dtcadastro1"]) && $_POST["dtcadastro1"] != NULL && $_POST["dtcadastro1"] != ""){
        $data1 = implode("-",array_reverse(explode("/", $_POST["dtcadastro1"])));
        $and .= " and agenda.dtcadastro >= '{$data1} 00:00:01'";
    }
    if(isset($_POST["dtcadastro2"]) && $_POST["dtcadastro2"] != NULL && $_POST["dtcadastro2"] != ""){
        $data2 = implode("-",array_reverse(explode("/",$_POST["dtcadastro2"])));
        $and .= " and agenda.dtcadastro <= '{$data2} 23:59:59'";
    }    
    if(isset($_POST["codpessoa"]) && $_POST["codpessoa"] != NULL && $_POST["codpessoa"] != ""){
        $and .= " and agenda.codpessoa = '{$_POST["codpessoa"]}'";
    }
    if(isset($_POST["codfuncionario"]) && $_POST["codfuncionario"] != NULL && $_POST["codfuncionario"] != ""){
        $and .= " and agenda.codfuncionario = '{$_POST["codfuncionario"]}'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        $and .= " and agenda.dtagenda >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and .= " and agenda.dtagenda <= '{$_POST["data2"]}'";
    }
    if(isset($_POST["codcategoria"]) && $_POST["codcategoria"] == "1"){//evita mostra para clientes agendados que já foram atendidos hoje
        $and .= " and agenda.dtagenda >= '".date('Y-m-d')." 00:00:00' and agenda.dtagenda <= '".date('Y-m-d')." 23:59:59'";
        $and .= " and agenda.codpessoa not in(select codpessoa from atendimento where codempresa = '{$_SESSION['codempresa']}' and dtcadastro >= '" . date('Y-m-d') . " 00:00:01' and dtcadastro <= '" . date('Y-m-d') . " 23:59:01')";
    }
    $sql = "select agenda.*, DATE_FORMAT(agenda.dtagenda, '%d/%m/%Y %H:%i') as dtagenda2, 
    cliente.nome as cliente, funcionario.nome as funcionario, DATE_FORMAT(agenda.dtcadastro, '%d/%m/%Y') as dtcadastro2, cliente.cpf 
    from agenda 
    left join pessoa as cliente on cliente.codpessoa = agenda.codpessoa and cliente.codempresa = agenda.codempresa
    left join pessoa as funcionario on funcionario.codpessoa = agenda.codfuncionario
    where agenda.codempresa = '{$_SESSION['codempresa']}' and funcionario.status <> 'i' 
    and agenda.atendido = 'n' {$and} 
    and cliente.codstatus not in(1, 6, 8, 13)
    order by agenda.dtagenda asc";
    
    $res = $conexao->comando($sql)or die("<pre>$sql</pre>");
    $qtd = $conexao->qtdResultado($res);
    $sql = "select * from nivel where codnivel = '{$_SESSION["codnivel"]}'";
    $nivel_logado = $conexao->comandoArray($sql);

    if(isset($_POST["codcategoria"]) && $_POST["codcategoria"] == "1"){
        $sql = "SELECT count(distinct(pessoa.codpessoa)) as qtd 
        FROM emprestimo 
        inner join beneficiocliente as bc on bc.codpessoa = emprestimo.codpessoa and bc.codempresa = emprestimo.codempresa and bc.codbeneficio = emprestimo.codbeneficio
        inner join pessoa on pessoa.codpessoa = emprestimo.codpessoa and pessoa.codempresa = emprestimo.codempresa
        where ((emprestimo.parcelas - emprestimo.parcelas_aberto) >= (0.2 * emprestimo.parcelas)
        or (emprestimo.prazo < 60)
        or (bc.margem > 10)) 
        and bc.situacao = 'ativo' 
        and emprestimo.codpessoa not in(select codpessoa from atendimento where codempresa = '{$_SESSION['codempresa']}' and dtcadastro >= '" . date('Y-m-d') . " 00:00:01' and dtcadastro <= '" . date('Y-m-d') . " 23:59:01')    
        and emprestimo.codpessoa not in(select codpessoa from agenda where codempresa = '{$_SESSION['codempresa']}' and dtagenda >= '" . date('Y-m-d') . " 00:00:01' and atendido = 'n')
        and emprestimo.codempresa = '{$_SESSION['codempresa']}' {$andPessoa2}
        and emprestimo.codpessoa in(select codpessoa from pessoa where codcategoria = 1)";

        $QtdPessoa      = $conexao->comandoArray($sql);    
        $QtdFuncionario = $conexao->comandoArray("select count(1) as qtd from pessoa where codempresa = '{$_SESSION['codempresa']}' and codcategoria not in(1,6)");
        $QtdAtendimento = $conexao->comandoArray("select count(1) as qtd from atendimento where codempresa = '{$_SESSION['codempresa']}' and codpessoa in(select codpessoa from pessoa where codcategoria = 1 and codempresa = '{$_SESSION['codempresa']}') and codfuncionario = '{$_SESSION["codfuncionario"]}'");
        $limiteDistr    = (int)($QtdPessoa["qtd"] / $QtdFuncionario["qtd"]);    

        $sql = "SELECT distinct(pessoa.codpessoa) as codpessoa, pessoa.*,
        DATE_FORMAT(emprestimo.dtcadastro, '%d/%m/%Y') as dtcadastro2 
        FROM emprestimo 
        inner join beneficiocliente as bc on bc.codpessoa = emprestimo.codpessoa and bc.codempresa = emprestimo.codempresa and bc.codbeneficio = emprestimo.codbeneficio
        inner join pessoa on pessoa.codpessoa = emprestimo.codpessoa and pessoa.codempresa = emprestimo.codempresa
        where ((emprestimo.parcelas - emprestimo.parcelas_aberto) >= (0.2 * emprestimo.parcelas)
        or (emprestimo.prazo < 60)
        or (bc.margem > 10)
        ) 
        and pessoa.codstatus <> '1' 
        and emprestimo.codpessoa not in(select codpessoa from atendimento where codempresa = '{$_SESSION['codempresa']}' and dtcadastro >= '" . date('Y-m-d') . " 00:00:01' and dtcadastro <= '" . date('Y-m-d') . " 23:59:01')    
        and emprestimo.codpessoa not in(select codpessoa from agenda where codempresa = '{$_SESSION['codempresa']}' and dtagenda >= '" . date('Y-m-d') . " 00:00:01' and atendido = 'n')    
        and emprestimo.codempresa = '{$_SESSION['codempresa']}' {$andPessoa2}
        and emprestimo.codpessoa in(select codpessoa from pessoa where codcategoria = 1) limit $limiteDistr";    

        $resEmprestimo = $conexao->comando($sql);
        $qtdEmprestimo = $conexao->qtdResultado($resEmprestimo);
    }

    if($qtd > 0 || $qtdEmprestimo > 0){
        $qtdFinal = $qtdEmprestimo + $qtd;
        echo 'Encontrou ', $qtdFinal, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Cadastro</th>';
        echo '<th>Retorno</th>';
        if(isset($_POST["codcategoria"]) && $_POST["codcategoria"] != NULL && $_POST["codcategoria"] == "1"){
            echo '<th>Código Cliente</th>';
        }elseif(isset($_POST["codcategoria"]) && $_POST["codcategoria"] != NULL && $_POST["codcategoria"] == "6"){
            echo '<th>Nome Cliente</th>';
            echo '<th>CPF</th>';
        }
        echo '<th>Operador</th>';
        echo '<th>Editar</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($agenda = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td>',$agenda["dtcadastro2"],'</td>';
            echo '<td>',$agenda["dtagenda2"],'</td>';
            if(isset($_POST["codcategoria"]) && $_POST["codcategoria"] != NULL && $_POST["codcategoria"] == "1"){
                echo '<td>',$agenda["codpessoa"],'</td>';
            }elseif(isset($_POST["codcategoria"]) && $_POST["codcategoria"] != NULL && $_POST["codcategoria"] == "6"){
                echo '<td>',$agenda["cliente"],'</td>';
                echo '<td>',$agenda["cpf"],'</td>';
            }            
            echo '<td>',$agenda["funcionario"],'</td>';
            echo '<td>';
            if($nivel_logado["nome"] != NULL && $nivel_logado["nome"] != "" && strpos($nivel["nome"], "OPERADOR")){
                echo '<a href="#" onclick="excluirAgenda(',$agenda["codagenda"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            }
            if(isset($_POST["codcategoria"]) && $_POST["codcategoria"] != NULL && $_POST["codcategoria"] == "6"){
                $complementoCaminho = "&callcenter=true";
            }else{
                $complementoCaminho = "";
            }
            echo '<a href="Cliente.php?codpessoa=',$agenda["codpessoa"],$complementoCaminho,'" onclick="" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '</td>';
            echo '</tr>';
        }
        if($qtdEmprestimo > 0 && isset($_POST["codcategoria"]) && $_POST["codcategoria"] == "1"){
            while($emprestimo = $conexao->resultadoArray($resEmprestimo)){
                echo '<tr>';
                echo '<td>',$emprestimo["dtcadastro2"],'</td>';
                echo '<td title="Fila processada para seu atendimento">',date("m/y"),'</td>';
                echo '<td>',$emprestimo["codpessoa"],'</td>';
                echo '<td>--fila--</td>';
                echo '<td>';
                if($nivel_logado["nome"] != NULL && $nivel_logado["nome"] != "" && trim($nivel_logado["nome"]) !== "OPERADOR"){
                    echo '<a href="#" onclick="excluirAgenda(',$agenda["codagenda"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                }
                if(isset($_POST["codcategoria"]) && $_POST["codcategoria"] != NULL && $_POST["codcategoria"] == "6"){
                    $complementoCaminho = "&callcenter=true";
                }else{
                    $complementoCaminho = "";
                }
                echo '<a href="Cliente.php?codpessoa=',$emprestimo["codpessoa"],$complementoCaminho,'" onclick="" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                echo '</td>';
                echo '</tr>';
            }
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo 'Nada encontrado!';
    }

    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "procurar";
    $log->observacao = "Procurado agenda - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();  