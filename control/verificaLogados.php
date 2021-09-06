<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
include "../model/Conexao.php";
$conexao = new Conexao();
if ($_SESSION["codnivel"] != "1") {
    $andNivelChat = " and (pessoa.codempresa = '{$_SESSION['codempresa']}' or pessoa.codnivel = 1)";
}
$sql = "select pessoa.codpessoa, pessoa.nome, pessoa.imagem, empresa.razao as empresa, acesso.hora, DATE_FORMAT(dtsaida, '%H:%i:%s') as hora_saida,
        DATE_FORMAT(ultimaacao, '%H:%i:%s') as hora2
        from acesso 
        inner join pessoa on pessoa.codpessoa = acesso.codpessoa and pessoa.codempresa = acesso.codempresa
        inner join nivel on nivel.codnivel = pessoa.codnivel and nivel.codempresa = acesso.codempresa
        inner join empresa on empresa.codempresa = pessoa.codempresa
        where acesso.data = '" . date('Y-m-d') . "' 
        and acesso.dtsaida = '0000-00-00 00:00:00' 
        and nivel.nome not like 'Morador%' 
        and pessoa.codpessoa <> '{$_SESSION['codpessoa']}'
        {$andNivelChat}
        order by nome";      
if (isset($_POST["chatMinimizado"]) && $_POST["chatMinimizado"] != NULL && $_POST["chatMinimizado"] == "s") {
    $displayChat = ";display: none";
} else {
    $displayChat = "";
}
$respessoa = $conexao->comando($sql)or die("<pre>$sql</pre>");
$qtdpessoa = $conexao->qtdResultado($respessoa);
if ($qtdpessoa > 0) {
    echo '<a title="" style="margin-top: 60px; margin-right: 20px;" href="javascript: fecharChat();">Minimizar</a>';
    while ($pessoa = $conexao->resultadoArray($respessoa)) {
        echo '<div class="sidebar-name" style="width: 600px; height: 40px;text-transform: initial', $displayChat, '">';
        if ($_SESSION["codnivel"] == 1) {
            $titulo = $pessoa["empresa"]; 
        } else {
            $titulo = "";
        }
        if (!isset($pessoa["hora_saida"]) || $pessoa["hora_saida"] == NULL || $pessoa["hora_saida"] == "00:00:00") {
            $minutos_gastos = diferencaHora($pessoa["hora2"], date('H:i:s'));
            if ($minutos_gastos <= 3) {
                $cor_bolinha = "green";
            } elseif ($minutos_gastos > 3) {
                $cor_bolinha = "yellow";
            }
        } else {
            $cor_bolinha = "preto";
        }
        $nomes = explode(" ", $pessoa["nome"]);
        ?>
        <a style="width: 100%" title='<?= $titulo ?>' href="javascript:register_popup('<?= $pessoa["codpessoa"] ?>', '<?= $nomes[0] ?>');">
            <?php
            if (isset($pessoa["imagem"]) && $pessoa["imagem"] != NULL && $pessoa["imagem"] != "" && file_exists("../arquivos/{$pessoa["imagem"]}")) {
                echo '<img style="float: left;" width="30" height="30" src="../arquivos/', $pessoa["imagem"], '" alt="Imagem perfil de usuário"/>';
            } else {
                echo '<img style="float: left;" width="30" height="30" src="../visao/recursos/img/sem_imagem_chat.png" alt="Imagem perfil de usuário"/>';
            }
            echo '<p style="background: ', $cor_bolinha, '; width: 10px; height: 10px;border-radius: 20px; float: left; margin-left: 0px;border: 1px solid black;"></p>';
            echo '<p style="float: left;margin: 0;padding: 0;width: 30px;height: 34px;margin-top: 10px;">';

            echo $nomes[0];

            echo '</p>';
            echo '</a>';
            echo '</div>';
        }
    }

    function diferencaHora($horainicio, $horafim) {
        $horaInicio = explode(":", $horainicio);
        $horaFim = explode(":", $horafim);

        $difHoras = $horaFim[0] - $horaInicio[0];
        $difMin = $horaFim[1] - $horaInicio[1];
        $difFInal = $difMin + ($difHoras * 60);
        return $difFInal;
    }
    ?>


