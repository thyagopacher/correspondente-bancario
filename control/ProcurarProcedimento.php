<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";
    if(isset($_POST["codregistro"]) && $_POST["codregistro"] != NULL && $_POST["codregistro"] != ""){
        $and2 .= " and registro.codregistro = '{$_POST["codregistro"]}%'";
    }
    if(isset($_POST["letra"]) && $_POST["letra"] != NULL && $_POST["letra"] != ""){
        $and .= " and cbhpm.procedimento like '{$_POST["letra"]}%'";
    }elseif(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and cbhpm.procedimento like '{$_POST["nome"]}%'";
    }
    
    $input_check = "";
    $procedimento1 = "";
    $sql = "select codcbhpm, codigo, upper(procedimento) as procedimento, porte from cbhpm where 1 = 1 {$and} order by procedimento";
    $res = $conexao->comando($sql)or die(mysqli_error($conexao->conexao));
    $qtd = $conexao->qtdResultado($res);
    if($qtd > 0){
        $procedimento1 .= '<ul>';
        $linha = 0;
        if(isset($_POST["procedimento"]) && $_POST["procedimento"] != NULL){
            $_POST["procedimento"] = array_filter($_POST["procedimento"]);
        }
        while($procedimento = $conexao->resultadoArray($res)){
            $arrayJavascript = "new Array('{$procedimento["porte"]}', '{$procedimento["codigo"]}', '{$linha}', '{$procedimento["procedimento"]}', '".porteValor($procedimento["porte"])."', '{$procedimento["codcbhpm"]}')";
            $procedimento1 .= '<li><input class="check_procedimento" '.$input_check.' onclick="defineRegistro('.$arrayJavascript.')" type="checkbox" name="busca_procedimento[]" id="busca_procedimento'.$linha.'" value="'.$procedimento["codcbhpm"].'"/>'.$procedimento["procedimento"].'-Pt:'.$procedimento["porte"].'</li>';
            $linha++;
        }
        $procedimento1 .= '</ul>';
    }else{
        echo '';
    }

    echo json_encode(array('procedimentos1' => $procedimento1));
    
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
