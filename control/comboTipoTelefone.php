<?php

    include("../model/Conexao.php");
    $conexao = new Conexao();
    $restipo = $conexao->comando("select * from tipotelefone order by nome");
    $qtdtipo = $conexao->qtdResultado($restipo);
    $option  = "";
    if($qtdtipo > 0){
        $option .= '<option value="">--Selecione--</option>';
        while($tipo = $conexao->resultadoArray($restipo)){
            if(isset($_POST["codtipo"]) && $_POST["codtipo"] == $tipo["codtipo"]){
                $option .=  '<option selected value="'.$tipo["codtipo"].'">'.$tipo["nome"].'</option>';
            }else{
                $option .=  '<option value="'.$tipo["codtipo"].'">'.$tipo["nome"].'</option>';
            }
        }
    }else{
        $option .=  '<option value="">--Nada encontrado--</option>';
    }
    
    echo json_encode(array('option' => $option));
