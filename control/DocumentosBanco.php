<?php
include "../model/Conexao.php";
$conexao = new Conexao();
echo '<table style="width: 100%">';
$resdocumento = $conexao->comando("select * from documento where codbanco = '{$_POST["codbanco"]}' order by nome");
$qtddocumento = $conexao->qtdResultado($resdocumento);
if ($qtddocumento > 0) {
    $linhaDocumento = 0;
    while ($documento = $conexao->resultadoArray($resdocumento)) {
        echo '<tr>';
        echo '<td>';
        $sql = "select nome, link from arquivopessoa where nome = 'documento{$documento["coddocumento"]}' and codpessoa = '{$_GET["codpessoa"]}'";
        $arquivop = $conexao->comandoArray($sql);                
        if(isset($arquivop["nome"]) && $arquivop["nome"] != NULL && $arquivop["nome"] != ""){
            $situacao = "OK";
            $requiredPadrao = "";
        }else{
            $situacao = ""; 
            $requiredPadrao = "required";
        }                   
        echo $documento["nome"],' - ',$situacao,'<br>';
        echo '<input type="file" name="arquivopessoa',$documento["coddocumento"],'" id="arquivopessoa',$documento["nome"],'" title="Por favor insira o documento de ',$documento["nome"],'" ',$requiredPadrao,' id="arquivopessoa',$documento["nome"],'"/>';
        if(isset($arquivop["link"]) && $arquivop["link"] != NULL && $arquivop["link"] != ""){
            echo '<a href="../arquivos/',$arquivop["link"],'" target="_blank">Download arquivo</a>';
        }        
        $linhaDocumento++;
        echo '</td>';
        echo '</tr>';
    }
}
echo '</table>';

    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "procurar";
    $log->observacao = "listado documentos banco - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();   