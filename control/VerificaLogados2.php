<!--começar laço aqui-->
<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
if(isset($_POST["nome_pesquisado"]) && $_POST["nome_pesquisado"] != NULL && $_POST["nome_pesquisado"] != ""){
    $and .= " and pessoa.nome like '%{$_POST["nome_pesquisado"]}%'";
    $limitador = " order by acesso.codacesso desc limit 1";
}else{
    $and .= " and acesso.data = '" . date('Y-m-d') . "' and acesso.dtsaida = '0000-00-00 00:00:00'";
    $limitador = "";
}
if($_SESSION["codnivel"] != "1"){
    $and .= " and pessoa.codempresa = '{$_SESSION['codempresa']}'";
}
include "../model/Conexao.php";
$conexao = new Conexao();
$sql = "select pessoa.codpessoa, pessoa.nome, pessoa.imagem, DATE_FORMAT(ultimaacao, '%H:%i') as ultimacaoacao_hora, pessoa.codempresa, DATE_FORMAT(dtsaida, '%H:%i:%s') as hora_saida, pessoa.codnivel
from pessoa    
inner join acesso on acesso.codpessoa = pessoa.codpessoa and acesso.codempresa = pessoa.codempresa
inner join nivel on nivel.codnivel = pessoa.codnivel and nivel.codempresa = pessoa.codempresa
where nivel.nome not like 'Morador%' {$and} and pessoa.codpessoa <> '{$_SESSION['codpessoa']}' {$limitador}";

$respessoa = $conexao->comando($sql);
$qtdpessoa = $conexao->qtdResultado($respessoa);
if ($qtdpessoa > 0) {
    $alturaAcima = 0;
    while ($pessoa = $conexao->resultadoArray($respessoa)) {
        if (!isset($pessoa['hora_saida']) || $pessoa['hora_saida'] == NULL || $pessoa['hora_saida'] == "00:00:00") {
            $minutos_gastos = diferencaHora($pessoa['ultimacaoacao_hora'], date('H:i:s'));
            if ($minutos_gastos <= 3) {
                $cor_bolinha = 'green';
            } elseif ($minutos_gastos > 3 && $minutos_gastos <= 30) {
                $cor_bolinha = 'yellow';
            }elseif($minutos_gastos > 30){
                $cor_bolinha = 'white';
            }
            $titulo .= '-Ultima ação há:'.$minutos_gastos.' min';
        } else {
            $cor_bolinha = 'white';
        }
        if($pessoa["codnivel"] == 1){
            $souDe = "Sou seu suporte!!!";
        }else{
            $souDe = "Sou do condominio ".$pessoa["condominio"];
        }        
        ?>
        <div class="infinite-list-item infinite-list-item-transition" style="z-index: 4; height: 72px; will-change: transform; transform: translate3d(0px, 0%, 0px); float: left;width: 100%;height: 70px;margin-top: <?= $alturaAcima ?>px;"
             codpessoa="<?= $pessoa["codpessoa"] ?>" onclick='trocaPessoaFalando(<?= $pessoa["codpessoa"] ?>)'>
            <div class="chat-drag-cover" data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us">
                <div class="chat" data-ignore-capture="any" contextmenu="false" data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0">
                    <div class="chat-avatar" data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.0">
                        <div class="avatar icon-user-default" data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.0.0">
                            <?php if (isset($pessoa["imagem"]) && $pessoa["imagem"] != NULL && $pessoa["imagem"] != "") { ?>
                                <img src="/sistema/arquivos/<?= $pessoa["imagem"] ?>" class="avatar-image is-loaded" id="imagem_pessoa<?= $pessoa["codpessoa"] ?>">
                            <?php } else { ?>
                                <img src="/sistema/visao/recursos/img/sem_imagem_chat.png" class="avatar-image is-loaded" id="imagem_pessoa<?= $pessoa["codpessoa"] ?>">
                            <?php } ?>
                            <p title="<?=$titulo?>" style="background:<?=$cor_bolinha?>;width:10px; height:10px;border-radius:20px; float:left; margin-left: 50px;border: 1px solid black;margin-top: -15px;position: fixed;"></p>   
                        </div>
                    </div>
                    <div class="chat-body" data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.1">
                        <div class="chat-main" data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.1.0">
                            <div class="chat-title" data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.1.0.0">
                                <span class="emojitext ellipsify" dir="auto" title="<?= $souDe ?>" id="nome_pessoa<?= $pessoa["codpessoa"] ?>">
                                    <?= $pessoa["nome"] ?>
                                </span>
                            </div>
                            <div class="chat-meta" data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.1.0.1">
                                <span class="chat-time" id='ultima_hora<?= $pessoa["codpessoa"] ?>'>
                                    <?= $pessoa["ultimacaoacao_hora"] ?>
                                </span>
                            </div>
                        </div>
                        <div class="chat-secondary" data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.1.1">
                            <div class="chat-status" data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.1.1.0">
                                <span class="emojitext ellipsify" dir="auto" data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.1.1.0.$status">
                                    <?php
                                    $ultimo_chat = $conexao->comandoArray("select texto from chat where codpessoa1 = '{$pessoa["codpessoa"]}' and codempresa = '{$pessoa["codempresa"]}' order by codchat desc limit 1");
                                    echo $ultimo_chat["texto"];
                                    ?>
                                </span>
                            </div>
                            <div class="chat-meta" data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.1.1.1">
                                <span data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.1.1.1.0">
                                </span>
                                <span data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.1.1.1.1">
                                </span>
                                <span data-reactid=".0.$main.4.2.0.0.$5511987263937@c=1us.$5511987263937@c=1us.0.1.1.1.2">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $alturaAcima = $alturaAcima + 70;
    }
}


    function diferencaHora($horainicio, $horafim) {
        $horaInicio = explode(':', $horainicio);
        $horaFim = explode(':', $horafim);

        $difHoras = $horaFim[0] - $horaInicio[0];
        $difMin = $horaFim[1] - $horaInicio[1];
        $difFInal = $difMin + ($difHoras * 60);
        return $difFInal;
    }
?>