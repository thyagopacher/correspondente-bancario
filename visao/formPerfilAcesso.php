<form name="fperfilAcesso" id="fperfilAcesso" method="get" onsubmit="return false;">
    <input type="hidden" name="naomaster" id="naomaster" value="<?php if(isset($naomaster) && $naomaster == true){echo 'true';}?>"/>
    <div id="comboNivel"></div>
    <p>
        <button class="btn btn-primary" onclick="marcarPerfil();">Marcar todos</button>
        <button class="btn btn-primary" onclick="desmarcarPerfil();">Desmarcar todos</button>
        <button class="btn btn-primary" onclick="salvarPerfil();">Salvar</button>
        <button class="btn btn-primary" style="display: none" id="btCopiaNivel" onclick="copiaPerfil();" title="Isso copia o nivel em questão para todos os outras empresas">Copia Nivel</button>
    </p>
    <div id="listagemPerfilAcesso"></div>
</form>