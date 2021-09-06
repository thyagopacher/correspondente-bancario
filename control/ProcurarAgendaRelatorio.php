<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";
    $sql     = "select * from nivel where codempresa = '{$_SESSION['codempresa']}' and codnivel = '{$_SESSION["codnivel"]}'";
    $nivel   = $conexao->comandoArray($sql);
    if($nivel["nome"] == "OPERADOR"){
        $_POST["codfuncionario"] = $_SESSION['codpessoa'];
        $_POST["data1"]          = date('Y-m-d');
        $_POST["data2"]          = date('Y-m-d');
    }
    if(isset($_POST["codcategoria"]) && $_POST["codcategoria"] != NULL && $_POST["codcategoria"] != ""){
        $and .= " and cliente.codcategoria = '{$_POST["codcategoria"]}'";
    }
    if(isset($_POST["codcliente"]) && $_POST["codcliente"] != NULL && $_POST["codcliente"] != ""){
        $and .= " and agenda.codcliente = '{$_POST["codcliente"]}'";
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
    $sql = "select agenda.*, DATE_FORMAT(agenda.dtagenda, '%d/%m/%Y %H:%i') as dtagenda2, 
    cliente.nome as cliente, funcionario.nome as funcionario, DATE_FORMAT(agenda.dtcadastro, '%d/%m/%Y') as dtcadastro2,
    cliente.cpf
    from agenda 
    left join pessoa as cliente on cliente.codpessoa = agenda.codpessoa and cliente.codempresa = agenda.codempresa
    left join pessoa as funcionario on funcionario.codpessoa = agenda.codfuncionario
    where agenda.codempresa = '{$_SESSION['codempresa']}' and agenda.atendido = 'n' {$and} order by agenda.dtagenda asc";

    $res = $conexao->comando($sql)or die("<pre>$sql</pre>");
    $qtd = $conexao->qtdResultado($res);
    $sql = "select * from nivel where codnivel = '{$_SESSION["codnivel"]}'";
    $nivel_logado = $conexao->comandoArray($sql);
//    echo "Nivel Logado: {$nivel_logado["nome"]}";

    if(isset($_POST["codcategoria"]) && $_POST["codcategoria"] == "1"){
    $sql = "SELECT count(distinct(pessoa.codpessoa)) as qtd 
    FROM emprestimo 
    inner join beneficiocliente as bc on bc.codpessoa = emprestimo.codpessoa and bc.codempresa = emprestimo.codempresa and bc.codbeneficio = emprestimo.codbeneficio
    inner join pessoa on pessoa.codpessoa = emprestimo.codpessoa and pessoa.codempresa = emprestimo.codempresa
    where ((emprestimo.parcelas - emprestimo.parcelas_aberto) >= (0.2 * emprestimo.parcelas)
    or (emprestimo.prazo < 60)
    or (bc.margem > 10)) 
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
    and emprestimo.codpessoa not in(select codpessoa from atendimento where codempresa = '{$_SESSION['codempresa']}' and dtcadastro >= '" . date('Y-m-d') . " 00:00:01' and dtcadastro <= '" . date('Y-m-d') . " 23:59:01')    
    and emprestimo.codpessoa not in(select codpessoa from agenda where codempresa = '{$_SESSION['codempresa']}' and dtagenda >= '" . date('Y-m-d') . " 00:00:01' and atendido = 'n')    
    and emprestimo.codempresa = '{$_SESSION['codempresa']}' {$andPessoa2}
    and emprestimo.codpessoa in(select codpessoa from pessoa where codcategoria = 1) limit $limiteDistr";    

    $resEmprestimo = $conexao->comando($sql);
    $qtdEmprestimo = $conexao->qtdResultado($resEmprestimo);
    }

    if($qtd > 0 || $qtdEmprestimo > 0){
        $qtdFinal = $qtdEmprestimo + $qtd;
        $nome  = 'Rel. Agenda - resultados encontrados: '. $qtdFinal;
        $html  = '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Cadastro</th>';
        $html .= '<th>Retorno</th>';
        $html .= '<th>Cliente</th>';
        $html .= '<th>CPF</th>';
        $html .= '<th>Operador</th>';
        if (isset($_POST["tipo"]) && $_POST["tipo"] == "xls") {
            $rescampo = $conexao->comando("select * from campoextra where codpagina = '{$_POST["codpagina"]}' and codempresa = '{$_SESSION['codempresa']}'");
            $qtdcampo = $conexao->qtdResultado($rescampo);
            if ($qtdcampo > 0) {
                while ($campo = $conexao->resultadoArray($rescampo)) {
                    $html .= '<th>' . $campo["titulo"] . '</th>';
                }
            }
        }        
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($agenda = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td>'.$agenda["dtcadastro2"].'</td>';
            $html .= '<td>'.$agenda["dtagenda2"].'</td>';
            $html .= '<td>'.$agenda["cliente"].'</td>';
            $html .= '<td>'.$agenda["cpf"].'</td>';
            $html .= '<td>'.$agenda["funcionario"].'</td>';
            $html .= '<td>';
            if(isset($_POST["codcategoria"]) && $_POST["codcategoria"] != NULL && $_POST["codcategoria"] == "6"){
                $complementoCaminho = "&callcenter=true";
            }else{
                $complementoCaminho = "";
            }
            $html .= '</td>';
            $html .= '</tr>';
        }
        if($qtdEmprestimo > 0 && isset($_POST["codcategoria"]) && $_POST["codcategoria"] == "1"){
            while($emprestimo = $conexao->resultadoArray($resEmprestimo)){
                $html .= '<tr>';
                $html .= '<td>'.$emprestimo["dtcadastro2"].'</td>';
                $html .= '<td title="Fila processada para seu atendimento">'.date("m/y").'</td>';
                $html .= '<td>'.$emprestimo["nome"].'</td>';
                $html .= '<td>'.$emprestimo["cpf"].'</td>';
                $html .= '<td>--fila--</td>';
                $html .= '<td>';

                if(isset($_POST["codcategoria"]) && $_POST["codcategoria"] != NULL && $_POST["codcategoria"] == "6"){
                    $complementoCaminho = "&callcenter=true";
                }else{
                    $complementoCaminho = "";
                }
                $html .= '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</tbody>';
        $html .= '</table>';
        
        $_POST["html"] = $html;
        $paisagem = "sim";
        
        include "../model/Log.php";
        $log = new Log($conexao);
        
        
        $log->acao       = "procurar";
        $log->observacao = "Procurado agenda relatório - em ". date('d/m/Y'). " - ". date('H:i');
        $log->codpagina  = "0";
        
        $log->hora = date('H:i:s');
        $log->inserir();          
        
        if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
            include "./GeraExcel.php";
        }else{
            include "./GeraPdf.php";
        }
    }else{
        echo '<script>alert("Sem pessoa encontrada!");window.close();</script>';
    }
