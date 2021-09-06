<?php include "validacaoLogin.php";?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Tirar Fotos</title>
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <style>
            video { border: 1px solid #ccc; display: block; margin: 0 0 20px 0; }
            #canvas {border: 1px solid #ccc; display: block; }
        </style>
    </head>
    <body>

        <div style="width: 50%; float: left;"><video id="video" width="640" height="480" autoplay></video></div>
        <div style="width: 50%; float: left;"><canvas id="canvas" width="640" height="480"></canvas></div>        
        <div>
            <button id="snap">Tirar Foto</button>
            <button id="save">Salvar Foto</button>
        </div>

        <script type="text/javascript" src="../visao/recursos/js/TiraFoto.js"></script>    
    </body>
</html> 