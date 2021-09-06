<?php
    session_start();
    header('Content-Type: text/html; charset=utf-8');
    $cpf = $_POST["cpf"];
    if(isset($_POST["cpf"]) && $_POST["cpf"] != NULL && $_POST["cpf"] != ""){
        
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
        
        $sql = 'select consultade from configuracao where codempresa = '. $_SESSION["codempresa"];
        $configuracaop  = $conexao->comandoArray($sql);

        /**limpando cpf para pesquisar*/
        $cpf       = str_replace("-", "", str_replace(".", "", $cpf));
        $consulta_cpf = $beneficio->consultaCpfInss($cpf);

        if(isset($consulta_cpf->consulta->ok) && $consulta_cpf->consulta->ok != NULL && $consulta_cpf->consulta->ok != ""){
            echo '<table class="table table-bordered table-striped dataTable">';
            echo '<tr><td>Status da consulta</td><td>'.$consulta_cpf->consulta->ok.'</td></tr>';
            $qtdbeneficio = count($consulta_cpf->consulta->consulta_cpf->resultado);
            $numeros_nb   = "";
            $separador    = ",";
            $linhaConsulta = 0;
            foreach ($consulta_cpf->consulta->consulta_cpf->resultado as $key => $resultado2) {
                echo "<tr><td>Nome</td><td>".$resultado2->nome.'</td></tr>';
                if($linhaConsulta == $qtdbeneficio - 1){
                    $separador = "";
                }
                $numeros_nb .= "'$resultado2->beneficio'" .$separador;
                
                echo "<tr><td>Beneficio:</td><td><a style='color: blue;' href='javascript: consultaBeneficioInss({$resultado2->beneficio})' title='Clique aqui para abrir detalhadamento do beneficio'>".$resultado2->beneficio.'</a></td></tr>';
                if(isset($configuracaop["consultade"]) && $configuracaop["consultade"] != NULL && $configuracaop["consultade"] == "1"){
                    echo "<tr><td>Nascimento:</td><td>".implode("/",array_reverse(explode("/",$resultado2->data_nascimento))).'</td></tr>';
                }else{
                    echo "<tr><td>Nascimento:</td><td>".implode("/",array_reverse(explode("/",$resultado2->data_nascimento))).'</td></tr>';
                }
                echo "<tr><td>Mãe:</td><td>".$resultado2->mae.'</td></tr>';
                echo "<tr><td>Dt. Inicio Beneficio:</td><td>".$resultado2->data_inicio_beneficio.'</td></tr>';
                echo "<tr><td>Espécie Beneficio:</td><td>".$resultado2->especie.'</td></tr>';
                echo "<tr><td>NIT:</td><td>".$resultado2->nit.'</td></tr>';
                echo "<tr><td>Municipio:</td><td>".$resultado2->municipio.'</td></tr>';
                echo "<tr><td>UF:</td><td>".$resultado2->uf.'</td></tr>';   
                $linhaConsulta++;
            }
            if($qtdbeneficio > 1){
                $arrayJavascript = "new Array($numeros_nb)";
                echo '<tr><td><a href="javascript: consultaBeneficioInss(',$arrayJavascript,')">Consultar Beneficios</a></td></tr>';
            }
            echo '</table>';
            
            include "../model/Log.php";
            $log             = new Log($conexao);
            $log->acao       = "procurar";
            $log->codpagina  = 4;
            $log->observacao =  "Consulta CPF INSS: CPF : {$cpf}";
            $log->inserir();             
        }else{
            echo '<span style="color: red">Retorno msg VIPER: '.$consulta_cpf->msg. '</span>';
        }
    }else{
        echo 'Não pode consultar sem cpf...';
    }