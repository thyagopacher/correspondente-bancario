
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
                    $.post('../control/SalvarFotoPessoa.php', {imagem: canvas.toDataURL(), codpessoa: $("#codpessoa").val()}, function (data) {
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

        