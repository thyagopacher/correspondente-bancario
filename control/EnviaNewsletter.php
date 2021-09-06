<?php
include("../model/Conexao.php");
include("../model/Newsletter.php");
$conexao = new Conexao();
$newsletter    = new Newsletter($conexao);
$resnewsletter = $newsletter->procuraNEnviado();
$qtdnewsletter = $conexao->qtdResultado($resnewsletter);
if ($qtdnewsletter > 0) {
    include("./Email.php");
    $email = new Email();
    while ($newsletter2 = $conexao->resultadoArray($resnewsletter)) {
        $email->assunto      = $newsletter2["assunto"];
        $email->para         = $newsletter2["para"];
        $email->para_email   = $newsletter2["para_email"];
        $email->mensagem     = $newsletter2["texto"];
        $resenvioEmail       = $email->envia(); 
        if($resenvioEmail){
            $sql = "update newsletter set situacao = 's' where codnewsletter = '{$newsletter2["codnewsletter"]}'
            and codempresa = '{$newsletter2["codempresa"]}'";
            $resnewsletterAtualizacao = $conexao->comando($sql);
            if($resnewsletterAtualizacao == FALSE){
                $texto = "A newsletter de e-mails falhou dia ".date("d/m/Y H:i"). " 
                ao enviar para {$newsletter2["para"]}, foram enviados porém não conseguiu atualizar status do enviado!";
                mail("thyago.pacher@gmail.com", "Falha no envio da newsletter de e-mails gestccon", $texto);                
            }
        }else{
            $texto = "A newsletter de e-mails falhou dia ".date("d/m/Y H:i"). " ao enviar para {$newsletter2["para"]}. Erro caussado por:". $email->erro; 
            mail("thyago.pacher@gmail.com", "Falha no envio da newsletter de e-mails gestccon", $texto);
            break;
        }
    }
}