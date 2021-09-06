<?php

    session_start();
    
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }  
    
    function __autoload($class_name) {
        if(file_exists('../model/'.$class_name . '.php')){
            include '../model/'.$class_name . '.php';
        }elseif(file_exists("../visao/".$class_name . '.php')){
            include "../visao/".$class_name . '.php';
        }elseif(file_exists("./".$class_name . '.php')){
            include "./".$class_name . '.php';
        }
    }
    
    $conexao            = new Conexao();    
    $nivel_selecionado  = $conexao->comandoArray("select * from nivel where codnivel = '{$_POST["codnivel"]}'");
    $resnivel           = $conexao->comando("select * from nivelpagina where codnivel = '{$_POST["codnivel"]}'");
    $qtdnivel           = $conexao->qtdResultado($resnivel);
    
    //laço passa por nivel de mesmo nome em outros condominios
    $resoutrosnivel    = $conexao->comando("select * from nivel where nome = '{$nivel_selecionado["nome"]}' and codempresa <> '{$_SESSION['codempresa']}'");
    $qtdoutrosnivel    = $conexao->qtdResultado($resoutrosnivel);
    $arrayOutrosNivel  = array();
    $i                 = 0;
    while($outrosnivel = $conexao->resultadoArray($resoutrosnivel)){
        $arrayOutrosNivel[$i] = $outrosnivel["codnivel"];
        $i++;
    }
    $qtdArrayOutrosNivel = count($arrayOutrosNivel);
    if($qtdnivel > 0){
        while($permissao = $conexao->resultadoArray($resnivel)){
            if($qtdArrayOutrosNivel > 0){
                foreach ($arrayOutrosNivel as $key => $outrosNivel) {
                    $sql = "select * from nivelpagina where codpagina = '{$permissao["codpagina"]}' and codnivel = '{$outrosNivel}'"; 
                    $permissao_nivel2 = $conexao->comandoArray($sql);//verificando se o nivel alvo tem a permissão ja
                    if(isset($permissao_nivel2["codpagina"]) && $permissao_nivel2["codpagina"] != NULL && $permissao_nivel2["codpagina"] != ''){//se tiver atualiza
                        $sql = "update nivelpagina set inserir = '{$permissao["inserir"]}', atualizar = '{$permissao["atualizar"]}', excluir = '{$permissao["excluir"]}', 
                        procurar = '{$permissao["procurar"]}', mostrar = '{$permissao["mostrar"]}', gerapdf = '{$permissao["gerapdf"]}', geraexcel = '{$permissao["geraexcel"]}' 
                        where codnivel = '{$outrosNivel}' and codpagina = '{$permissao["codpagina"]}'";
                    }else{//senão inseri
                        $sql = "INSERT INTO `nivelpagina`(`codnivel`, `codpagina`, `inserir`, `atualizar`, `excluir`, `procurar`, `mostrar`, `gerapdf`, `geraexcel`, `dtcadastro`) 
                        VALUES ('{$outrosNivel}','{$permissao["codpagina"]}','{$permissao["inserir"]}','{$permissao["atualizar"]}','{$permissao["excluir"]}','{$permissao["procurar"]}','{$permissao["mostrar"]}','{$permissao["gerapdf"]}','{$permissao["geraexcel"]}','".date('Y-m-d H:i:s')."')";
                    }
                    $resAtualizarNivelPagina = $conexao->comando($sql);
                    if($resAtualizarNivelPagina == FALSE){
                        die(json_encode(array('mensagem' => "Problemas ao copiar perfil causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
                    }else{
                        mysqli_free_result($resAtualizarNivelPagina);
                    }  
                }
            }
        }
    }
    
    die(json_encode(array('mensagem' => "Perfil copiado com sucesso para os outros condominio!!!", 'situacao' => true)));
    