<?php

    include '../model/Conexao.php';
    $conexao     = new Conexao();
    
    $respessoa = $conexao->comando("select codpessoa from pessoa where codpessoa in(select codpessoa from telefone)");
    $qtdpessoa = $conexao->qtdResultado($respessoa);
    if($qtdpessoa > 0){
        while($pessoa = $conexao->resultadoArray($respessoa)){
            $restelefone = $conexao->comando("select * from telefone where ltrim(rtrim(numero)) <> '' and codpessoa = {$pessoa["codpessoa"]}");
            $qtdtelefone = $conexao->qtdResultado($restelefone);
            $qtdDuplicado = 0;
            if($qtdtelefone > 0){
                echo "Percorrendo {$qtdtelefone} resultados iniciais encontrados<br>";
                while($telefone = $conexao->resultadoArray($restelefone)){
                    $resTelefone2 = $conexao->comando("select codtelefone from telefone where numero = '{$telefone["numero"]}' and codpessoa = '{$telefone["codpessoa"]}'");
                    $qtdTelefone2 = $conexao->qtdResultado($resTelefone2);
                    if($qtdTelefone2 > 1){
                        $sql = "delete from telefone where numero = '{$telefone["numero"]}' and codtelefone <> '{$telefone["codtelefone"]}' and codpessoa = '{$telefone["codpessoa"]}'";
                        $resExcluirTelefoneDuplicado = $conexao->comando($sql);
                        if($resExcluirTelefoneDuplicado == FALSE){
                            echo "Problemas ao excluir telefones duplicados causado por:". mysqli_error($conexao->conexao);
                        }else{
                            $qtdDuplicado++;
                        }
                    }
                }
            }            
        }
    }
    

    
    echo "<br>Telefones duplicados encontrados:". $qtdDuplicado;