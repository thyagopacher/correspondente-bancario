<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";
    if(isset($_POST["email"]) && $_POST["email"] != NULL && $_POST["email"] != ""){
        $and .= " and registro.email like '%{$_POST["email"]}%'";
    }
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and registro.nome like '%{$_POST["nome"]}%'";
    }
    
    $sql = "select codregistro, codigo, spp, valor, tipo, DATE_FORMAT(registro.data, '%d/%m/%Y') as data2, DATE_FORMAT(registro.dtcadastro, '%d/%m/%Y') as dtcadastro2, incisao,
    pessoa.nome as quem_fez,  paciente,  bilateral
    from registro 
    inner join pessoa on pessoa.codpessoa = registro.codpessoa
    where 1 = 1  {$and}
    order by registro.dtcadastro";
//    echo "<pre>$sql</pre>";
    $res = $conexao->comando($sql)or die("<pre>$sql</pre>"); 
    $qtd = $conexao->qtdResultado($res);
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="width: 700px;">CONTROLE DE REGISTRO</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        $titulo = "";
        while($registro = $conexao->resultadoArray($res)){
            $sql = "SELECT distinct procedimentoregistro.codigo, upper(cbhpm.procedimento) as procedimento, cbhpm.porte, procedimentoregistro.valor, 
            cbhpm.codigo as codigo_procedimento, procedimentoregistro.tipo, procedimentoregistro.incisao, procedimentoregistro.bilateral
            FROM  `procedimentoregistro` 
            inner join registro on registro.codregistro = procedimentoregistro.codregistro
            inner join cbhpm on cbhpm.codcbhpm = procedimentoregistro.codigo
            where procedimentoregistro.codregistro = '{$registro["codregistro"]}'
            order by cbhpm.porte desc";
            $resProcedimento = $conexao->comando($sql);
            $qtdProcedimento = $conexao->qtdResultado($resProcedimento);
            if($qtdProcedimento == 0){
                continue; 
            }
            $totalRegistro = 0.0;
            echo '<tr>';
            echo '<td style="text-align: left;  width: 700px;">';
            echo 'Cód. Registro:', $registro["codregistro"], '<br>';
            echo 'Data:', $registro["data2"], '<br>';
            echo 'SPP:', $registro["spp"], ' - Paciente: ',$registro["paciente"],'<br>';
//            echo 'Tipo:',$registro["tipo"], '<br>';

            if($qtdProcedimento > 0){
                $linhaProcedimento = 1;
                while($procedimento = $conexao->resultadoArray($resProcedimento)){
                    
                    echo '',$procedimento["procedimento"],' / ', $procedimento["codigo_procedimento"], ' / ',$procedimento["porte"],'';
                    $valorCalculado = calculaHonorario($linhaProcedimento, $procedimento["porte"], $procedimento["tipo"], $procedimento["incisao"], $procedimento["bilateral"]);
                    if($valorCalculado > 0 && $procedimento["valor"] != $valorCalculado){
                        $procedimento["valor"] = $valorCalculado;
                    }
                    echo ' / R$', number_format($procedimento["valor"], 2, ",", "."), '';
                    echo ' / ', trocaIncisao($procedimento["incisao"]), ' / ',trocaBilateral($procedimento["bilateral"]),' / ',trocaUrgencia($procedimento["tipo"]),'<br>'; 
                    $totalRegistro += $procedimento["valor"];
                    $linhaProcedimento++;
                }
            }else{
                echo 'Procedimento não cadastrado<br>';
            }
            
            echo 'Dt. Cadastro:', $registro["dtcadastro2"], " - Por:", $registro["quem_fez"], '<br>';
            echo 'Resultado Final:', number_format($totalRegistro, 2, ",", "");
            echo '</td>';
            echo '<td>';
            echo '<a href="../visao/Registro.php?codregistro=',$registro["codregistro"],'" title="Clique aqui para editar"><img style="width: 20px;" src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirRegistro(',$registro["codregistro"],')" title="Clique aqui para excluir"><img style="width: 20px;" src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            echo '</td>';
            echo '</tr>';
            $titulo = "";
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo '';
    }

function trocaIncisao($incisao){
    switch ($incisao) {
        case "m":
            $incisao = "mesma via";
            break;
        case "d":
            $incisao = "diferente via";
            break;
        default:
            break;
    }
    return $incisao;
}

function trocaBilateral($bilateral){
    switch ($bilateral) {
        case "b":
            $bilateral = "bilateral";
            break;
        case "u":
            $bilateral = "unilateral";
            break;
        default:
            break;
    }
    return $bilateral;
}

function trocaUrgencia($urgencia){
    switch ($urgencia) {
        case "u":
            $urgencia = "urgência";
            break;
        case "e":
            $urgencia = "eletiva";
            break;
        default:
            break;
    }
    return $urgencia;
}

function porteValor($porte){
    $valor = 0.0;
    switch ($porte) {
        case 1:
            $valor = 249.7;
            break;
        case 2:
            $valor = 249.7;
            break;
        case 3:
            $valor = 249.7;
            break;
        case 4:
            $valor = 369.18;
            break;
        case 5:
            $valor = 571.07;
            break;
        case 6:
            $valor = 796.89;
            break;
    }
    return $valor;
}

function calculaHonorario($linha, $porte, $tipo, $incisao, $bilateral){
    $valorProcedimento = porteValor($porte);
    if($linha == 1){
        if($tipo === "u"){
            $valorHonorario = $valorProcedimento + ($valorProcedimento * 0.3);
        }else if($tipo === "e"){
            $valorHonorario = $valorProcedimento;
        }    
    }elseif($linha > 1){
        if($incisao === "m"){
            $valorHonorario = ($valorProcedimento * 0.5);
        }else if($incisao === "d"){
            $valorHonorario = ($valorProcedimento * 0.7);
        }
        if($tipo === "u"){
            $valorHonorario = $valorHonorario + ($valorHonorario * 0.3);
        }else if($tipo === "e"){
            $valorHonorario = $valorHonorario - ($valorHonorario * 0.3);
        }          
        if($bilateral === "b"){
            $valorHonorario = ($valorProcedimento * 0.7) + $valorHonorario;
        }         
    }
    return $valorHonorario;
}
