<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $sql = "select pessoa.codpessoa, pessoa.nome 
    from acesso 
    inner join pessoa on pessoa.codpessoa = acesso.codpessoa and pessoa.codempresa = acesso.codempresa
    where acesso.data = '".date('Y-m-d')."' and acesso.codempresa = '{$_POST["condominio"]}' and acesso.dtsaida = '0000-00-00 00:00:00' order by nome";

    $reslogado = $conexao->comando($sql);
    $qtd       = $conexao->qtdResultado($reslogado);
    
    if($qtd > 0){
        echo "<option value=''>--Selecione--</option>";
        while($logado = $conexao->resultadoArray($reslogado)){
            echo '<option value="',$logado["codpessoa"],'">',$logado["nome"],'</option>';
        }
    }else{
        echo '<option value="">--Nada encontrado--</option>';
    }