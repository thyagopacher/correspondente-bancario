<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?php
    header ('Content-type: text/html; charset=UTF-8');
    function __autoload($class_name) {
        if(file_exists("../model/".$class_name . '.php')){
            include "../model/".$class_name . '.php';
        }elseif(file_exists("../visao/".$class_name . '.php')){
            include "../visao/".$class_name . '.php';
        }elseif(file_exists("./".$class_name . '.php')){
            include "./".$class_name . '.php';
        }
    }
    
    
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    $conexao          = new Conexao();
    $campos_separados = implode(",", $_POST["campos"]);
    $sql = "select {$campos_separados} from {$_POST["tabela"]} where {$_POST["tabela"]}.codempresa = '{$_SESSION['codempresa']}'";
    $res              = $conexao->comando($sql);
    $qtd              = $conexao->qtdResultado($res);
    $qtd_campos       = count($_POST["campos"]);
    if($qtd > 0){
        echo '<script type="text/javascript">';
        echo 'google.load("visualization", "1.1", {packages:["table"]});';
        echo 'google.setOnLoadCallback(drawTable);';
        echo 'function drawTable() {';
        echo 'var data = new google.visualization.DataTable();';
        foreach ($_POST["campos"] as $key => $campo) {
            if($campo == "codfuncionario"){
                $campo = "Funcionário";
            }elseif($campo == "codmorador"){
                $campo = "Morador";
            }elseif($campo == "codpessoa"){
                $campo = "Pessoa";
            }elseif($campo == "codservico"){
                $campo = "Serviço";
            }elseif($campo == "codambiente"){
                $campo = "Ambiente";
            }elseif($campo == "codstatus"){
                $campo = "Status";
            }elseif($campo == "codveiculo"){
                $campo = "Veiculo";
            }elseif($campo == "codvisitante"){
                $campo = "Visitante";
            }elseif($campo == "codtipo"){
                $campo = "Tipo";
            }elseif($campo == "codnivel"){
                $campo = "Nivel";
            }elseif($campo == "codcarteira"){
                $campo = "Carteira";
            }elseif($campo == "codbeneficio"){
                $campo = "Beneficio";
            }elseif($campo == "codespecie"){
                $campo = "Espécie";
            }
            $campo = str_replace("vacao", "vação", $campo);
            $campo = str_replace("hora", " hora ", $campo);
            echo 'data.addColumn("string", "',trim($campo),'");';
        }
        echo 'data.addRows([';
        $linhaDados      = 0;
        $linhaCampos     = 0;
        $separadorDados  = ",";
        $separadorCampos = ",";
        while($dados = $conexao->resultadoArray($res)){
            if($linhaDados == $qtd - 1){
                $separadorDados = '';
            }
            echo '[';
            foreach ($_POST["campos"] as $key => $campo) {
                if($linhaCampos == $qtd_campos - 1){
                    $separadorCampos = "";
                }
                if($_POST["tabela"] == "apartamentovisitante"){
                    $tabelaStatusTipo = "visitante";
                }else{
                    $tabelaStatusTipo = $_POST["tabela"];
                }
                if($campo == "codfuncionario" || $campo == "codoperador" || $campo == "codpessoa" || $campo == "codcliente"){
                    $pessoa = $conexao->comandoArray("select nome from pessoa where codpessoa = '{$dados[$campo]}'");
                    $valor  = $pessoa["nome"];
                    
                }elseif($campo == "codcarteira"){
                    $carteira = $conexao->comandoArray("select nome from carteira where codcarteira = '{$dados[$campo]}' and codempresa = '{$_SESSION['codempresa']}'");
                    $valor  = $carteira["nome"];
                }elseif($campo == "dtcadastro" || $campo == "dtagenda" || $campo == "data" || $campo == "dtsaida"){
                    $valor = date('d/m/Y H:i:s', strtotime($dados[$campo]));
                }elseif($campo == "codservico"){
                    $servico = $conexao->comandoArray("select nome from servico where codservico = '{$dados[$campo]}' and codempresa = '{$_SESSION['codempresa']}'");
                    $valor  = $servico["nome"];
                }elseif($campo == "codstatus"){
                    $status = $conexao->comandoArray("select nome from status{$tabelaStatusTipo} where codstatus = '{$dados[$campo]}'");
                    $valor  = $status["nome"];
                }elseif($campo == "codtipo"){
                    $tipo  = $conexao->comandoArray("select nome from tipo{$tabelaStatusTipo} where codtipo = '{$dados[$campo]}' and codempresa = '{$_SESSION['codempresa']}'");
                    $valor = $tipo["nome"];
                }elseif($campo == "codnivel"){
                    $nivel  = $conexao->comandoArray("select nome from nivel where codnivel = '{$dados[$campo]}'");
                    $valor  = $nivel["nome"];
                }elseif($campo == "codbeneficio"){
                    $beneficiocliente = $conexao->comandoArray("select nome from beneficiocliente where codbeneficio = '{$dados[$campo]}'");
                    $valor  = $beneficiocliente["nome"];
                }elseif($campo == "codespecie"){
                    $especie = $conexao->comandoArray("select nome from especie where codespecie = '{$dados[$campo]}'");
                    $valor  = $especie["nome"];
                }else{
                    $valor = $dados[$campo];
                }
                echo '"',$valor,'"',$separadorCampos;
                $linhaCampos++;
            }
            echo ']',$separadorDados;
            $linhaDados++;
            $separadorCampos = ",";
        }
        echo ']);';
        echo 'var table = new google.visualization.Table(document.getElementById("table_div"));';
        echo 'table.draw(data, {showRowNumber: true, width: "100%", height: "100%"});';
        echo '}';
        echo '</script>';
    }else{
        echo 'Nada encontrado!!!';
    }
    
    
    echo '<div id="table_div"></div>';