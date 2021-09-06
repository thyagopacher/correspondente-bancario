function inserir() {
    if ($("#enderecoip").val() !== null && $("#enderecoip").val() !== "") {
        $.ajax({
            url: "../control/InserirBloqueio.php",
            type: "POST",
            data: $("#fbloqueio").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Bloqueio cadastrado", data.mensagem, "success");
                    procurarBloqueio(true);
                } else if (data.situacao === false) {
                    swal("Erro ao cadastrar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else if ($("#enderecoip").val() === null || $("#enderecoip").val() === "") {
        swal("Campo em branco", "Por favor defina um endereço IP para o bloqueio!", "error");
    }
}

function atualizar() {
    if ($("#enderecoip").val() !== null && $("#enderecoip").val() !== "") {
        $.ajax({
            url: "../control/AtualizarBloqueio.php",
            type: "POST",
            data: $("#fbloqueio").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Bloqueio atualizado", data.mensagem, "success");
                    procurarBloqueio(true);
                } else if (data.situacao === false) {
                    swal("Erro ao atualizar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else if ($("#enderecoip").val() === null || $("#enderecoip").val() === "") {
        swal("Campo em branco", "Por favor defina um endereço IP para o bloqueio!", "error");
    }
}

function excluir() {
    if (window.confirm("Deseja realmente excluir esse bloqueio?")) {
        if (document.getElementById("codbloqueio").value !== null && document.getElementById("codbloqueio").value !== "") {
            $.ajax({
                url: "../control/ExcluirBloqueio.php",
                type: "POST",
                data: {codbloqueio: document.getElementById("codbloqueio").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Bloqueio excluido", data.mensagem, "success");
                        procurarBloqueio(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o bloqueio para excluir!", "error");
        }
    }
}

function excluir2(codbloqueio) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa bloqueio!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codbloqueio !== null && codbloqueio !== "") {
                $.ajax({
                    url: "../control/ExcluirBloqueio.php",
                    type: "POST",
                    data: {codbloqueio: codbloqueio},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Bloqueio excluido", data.mensagem, "success");
                            procurarBloqueio(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha o bloqueio para excluir!", "error");
            }
        }
    });
}

function setaEditar(bloqueio) {
    document.getElementById("codbloqueio").value = bloqueio[0];
    document.getElementById("nome").value = bloqueio[1];
    document.getElementById("telefone").value = bloqueio[2];
    document.getElementById("email").value = bloqueio[3];
    document.getElementById("senha").value = bloqueio[4];
    document.getElementById("celular").value = bloqueio[5];
    document.getElementById("imagemCarregada").innerHTML = "<img src='../arquivos/" + bloqueio[6] + "' alt='Imagem da bloqueio' title='Imagem da bloqueio'/>";
    $("#btatualizarBloqueio").css("display", "");
    $("#btexcluirBloqueio").css("display", "");
    $("#btnovoBloqueio").css("display", "");
    $("#btinserirBloqueio").css("display", "none");
    $("#codnivel option[value='" + bloqueio[7] + "']").attr("selected", true);
}

function btNovo() {
    location.href = "Bloqueio.php";
}

function procurarBloqueio(acao){
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarBloqueio.php",
        type: "POST",
        data: $("#fPbloqueio").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(data !== "0"){
                document.getElementById("listagem").innerHTML = data;
            }else if(acao === false && data === "0"){
                swal("Atenção", "Nada encontrado de bloqueios!", "error");
                document.getElementById("listagem").innerHTML = "";
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioBloqueio() {
    document.getElementById("fPbloqueio").submit();
}

function abreRelatorioBloqueio2() {
    document.getElementById("fPbloqueio2").submit();
}

$(function () {
    $("#nome_procurar").keyup(function () {
        if ($("#nome_procurar").val().length > 2) {
            procurarBloqueio(true);
        }
    });
    $("#codnivel").change(function () {
        if ($("#codnivel option:selected").val() === "3") {
            $(".morador").show();
            $("#apartamento").attr("required", true);
            $("#bloco").attr("required", true);
        } else {
            $(".morador").hide();
            $("#apartamento").attr("required", false);
            $("#bloco").attr("required", false);
        }
    });
    
    var options =  { 
        onKeyPress: function(cep, event, currentField, options){
            if(cep){
              var ipArray = cep.split(".");
              var lastValue = ipArray[ipArray.length-1];
              if(lastValue !== "" && parseInt(lastValue) > 255){
                  ipArray[ipArray.length-1] =  '255';
                  currentField.attr('value', ipArray.join("."));
              }
        }             
    }};

    $('.ip_address').mask("999.999.999.999", options);    
    
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fbloqueio').ajaxForm({
        beforeSend: function () {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        uploadProgress: function (event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        success: function () {
            var percentVal = '100%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        complete: function (xhr) {
            var data = JSON.parse(xhr.responseText);
            if (data.situacao === true) {
                swal("Alteração", data.mensagem, "success");
                if (data.imagem !== null && data.imagem !== "") {
                    $("#imagemCarregada").html("<img width='150' src='../arquivos/" + data.imagem + "'  alt='Imagem usuário'/>");
                }
                procurarBloqueio(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});
