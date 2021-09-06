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

        <script>
            window.addEventListener("DOMContentLoaded", function () {
                var canvas = document.getElementById("canvas"),
                        context = canvas.getContext("2d"),
                        video = document.getElementById("video"),
                        videoObj = {"video": true},
                errBack = function (error) {
                    console.log("Video capture error: ", error.code);
                };
                if (navigator.getUserMedia) {
                    navigator.getUserMedia(videoObj, function (stream) {
                        video.src = stream;
                        video.play();
                    }, errBack);
                } else if (navigator.webkitGetUserMedia) {
                    navigator.webkitGetUserMedia(videoObj, function (stream) {
                        video.src = window.webkitURL.createObjectURL(stream);
                        video.play();
                    }, errBack);
                }
                else if (navigator.mozGetUserMedia) {
                    navigator.mozGetUserMedia(videoObj, function (stream) {
                        video.src = window.URL.createObjectURL(stream);
                        video.play();
                    }, errBack);
                }
                document.getElementById("snap").addEventListener("click", function () {
                    canvas.getContext("2d").drawImage(video, 0, 0, 640, 480);
                    //alert(canvas.toDataURL());
                });
                document.getElementById("save").addEventListener("click", function () {
                    $.post('../control/SalvarFotoConta.php', {imagem: canvas.toDataURL(), codconta: <?= $_GET["codconta"] ?>}, function (data) {
                        if(data.situacao == true){
                            alert(data.mensagem);
                            window.close();
                            window.opener.location.reload();
                        }else{
                            alert(data.mensagem);
                        }
                    }, 'json');
                });
            }, false);

        </script>    
    </body>
</html>