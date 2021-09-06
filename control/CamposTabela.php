<?php

include "../model/Conexao.php";
$conexao = new Conexao();
$res     = $conexao->comando("DESC {$_POST["tabela"]}");
$qtd     = $conexao->qtdResultado($res);
if($qtd > 0){
    echo '<ul>';
    $linhaCampo = 0;
    while($campo = $conexao->resultadoArray($res)){
        if($campo["Field"] == "codempresa"){
            continue;
        }
        echo '<li>';
        echo '<input type="checkbox" name="campos[]" id="campo',$linhaCampo,'" value="',$campo["Field"],'" title="Clique aqui para esse campo fazer parte do relatório"/>';
        if($campo["Field"] == "codfuncionario"){
            echo 'Funcionário';
        }elseif($campo["Field"] == "codoperador"){
            echo 'Operador';
        }elseif($campo["Field"] == "codcliente"){
            echo 'Cliente';
        }elseif($campo["Field"] == "codnivel"){
            echo 'Nivel';
        }elseif($campo["Field"] == "codcarteira"){
            echo 'Carteira';
        }elseif($campo["Field"] == "codpessoa"){
            echo 'Pessoa';
        }elseif($campo["Field"] == "codvisitante"){
            echo 'Visitante';
        }elseif($campo["Field"] == "dtcadastro"){
            echo 'Dt. Cadastro';
        }elseif($campo["Field"] == "codambiente"){
            echo 'Ambiente';
        }elseif($campo["Field"] == "codstatus"){
            echo 'Status';
        }elseif($campo["Field"] == "codtipo"){
            echo 'Tipo';
        }elseif($campo["Field"] == "codveiculo"){
            echo 'Veiculo';
        }elseif($campo["Field"] == "codservico"){
            echo 'Serviço';
        }elseif($campo["Field"] == "codbeneficio"){
            echo 'Beneficio';
        }elseif($campo["Field"] == "codespecie"){
            echo 'Espécie';
        }else{
            $valor_campo = str_replace("hora", " hora ", $campo["Field"]);
            $valor_campo = str_replace("vacao", "vação", $valor_campo);
            $valor_campo = str_replace("_", " ", $valor_campo);
            $valor_campo = str_replace("salario", " salário ", $valor_campo);
            $valor_campo = str_replace("cartao", " cartão ", $valor_campo);
            $valor_campo = str_replace("situacao", " situação ", $valor_campo);
            $valor_campo = str_replace("especie", " espécie ", $valor_campo);
            echo trim($valor_campo);
        }
        echo '</li>';
        $linhaCampo++;
    }
    echo '</ul>';
}

