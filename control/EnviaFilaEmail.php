<?php
include("../model/Conexao.php");
include("../model/FilaEmail.php");
$conexao = new Conexao();
$fila    = new FilaEmail($conexao);
$resfila = $fila->procuraNEnviado();
$qtdfila = $conexao->qtdResultado($resfila);
if ($qtdfila > 0) {
    include("./Email.php");
    $email = new Email();
    while ($fila2 = $conexao->resultadoArray($resfila)) {
        $email->assunto      = $fila2["assunto"];
        $email->para         = $fila2["para"];
        $email->para_email   = $fila2["para_email"];
        $email->mensagem     = $fila2["texto"];
        $resenvioEmail       = $email->envia(); 
        if($resenvioEmail){
            $sql = "update filaemail set situacao = 's' where codfila = '{$fila2["codfila"]}'
            and codempresa = '{$fila2["codempresa"]}'";
            $resfilaAtualizacao = $conexao->comando($sql);
            if($resfilaAtualizacao == FALSE){
                $texto = "A fila de e-mails falhou dia ".date("d/m/Y H:i"). " 
                ao enviar para {$fila2["para"]}, foram enviados porém não conseguiu atualizar status do enviado!";
                mail("thyago.pacher@gmail.com", "Falha no envio da fila de e-mails gestccon", $texto);                
            }
        }else{
            $texto = "A fila de e-mails falhou dia ".date("d/m/Y H:i"). " ao enviar para {$fila2["para"]}. Erro caussado por:". $email->erro; 
            mail("thyago.pacher@gmail.com", "Falha no envio da fila de e-mails gestccon", $texto);
            break;
        }
    }
}