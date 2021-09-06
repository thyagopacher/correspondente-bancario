<?php
    header('Content-Type: text/html; charset=utf-8');
//    $cpf = $_POST["cpf"];
    $cpf = "05820810929";
    if(isset($cpf) && $cpf != NULL && $cpf != ""){
        function __autoload($class_name) {
            if(file_exists("../model/".$class_name . '.php')){
                include "../model/".$class_name . '.php';
            }elseif(file_exists("../visao/".$class_name . '.php')){
                include "../visao/".$class_name . '.php';
            }elseif(file_exists("./".$class_name . '.php')){
                include "./".$class_name . '.php';
            }
        }
        $conexao   = new Conexao();
        $beneficio = new BeneficioCliente($conexao);
        /**limpando cpf para pesquisar*/
        $cpf       = str_replace(".", "", $cpf);
        $cpf       = str_replace("-", "", $cpf);
        $consulta_cpf = $beneficio->consultaCpfSiape($cpf);

//        $conteudo = file_get_contents("consultacpf.xml");
//        $consulta_cpf = simplexml_load_string(strtolower($conteudo));
        if(isset($consulta_cpf->consulta->ok) && $consulta_cpf->consulta->ok != NULL && $consulta_cpf->consulta->ok != ""){
            echo "<br>Status da consulta:".$consulta_cpf->consulta->ok;
            foreach ($consulta_cpf->consulta->consulta_cpf->resultado as $key => $resultado2) {
                echo "<br>Nome:".$resultado2->nome;
                echo "<br>Beneficio:".$resultado2->beneficio;
                echo "<br>Nascimento:".$resultado2->data_nascimento;
                echo "<br>Mãe:".$resultado2->mae;
                echo "<br>Dt. Inicio Beneficio:".$resultado2->data_inicio_beneficio;
                echo "<br>Espécie Beneficio:".$resultado2->especie;
                echo "<br>NIT:".$resultado2->nit;
                echo "<br>Municipio:".$resultado2->municipio;
                echo "<br>UF:".$resultado2->uf;            
            }
        }else{
            echo 'Nada encontrado!!!';
        }
    }else{
        echo 'Não pode consultar sem cpf...';
    }