function inserirComunicado() {
    $.ajax({
        url: "../control/InserirComunicado.php",
        type: "POST",
        data: $("#fcomunicado").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Comunicado cadastrado", data.mensagem, "success");
                procurarComunicado(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarComunicado() {
    $.ajax({
        url: "../control/AtualizarComunicado.php",
        type: "POST",
        data: $("#fcomunicado").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Comunicado atualizado", data.mensagem, "success");
                procurarComunicado(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirComunicado() {
    if (window.confirm("Deseja realmente excluir esse comunicado?")) {
        if (document.getElementById("codcomunicado").value !== null && document.getElementById("codcomunicado").value !== "") {
            $.ajax({
                url: "../control/ExcluirComunicado.php",
                type: "POST",
                data: {codcomunicado: document.getElementById("codcomunicado").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Comunicado excluido", data.mensagem, "success");
                        procurarComunicado(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha a comunicado para excluir!", "error");
        }
    }
}

function excluir2Comunicado(codcomunicado) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa comunicado!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codcomunicado !== null && codcomunicado !== "") {
                $.ajax({
                    url: "../control/ExcluirComunicado.php",
                    type: "POST",
                    data: {codcomunicado: codcomunicado},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Comunicado excluido", data.mensagem, "success");
                            procurarComunicado(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha a comunicado para excluir!", "error");
            }
        }
    });
}

function setaComunicado(comunicado) {
    $("#codcomunicado").val(comunicado[0]);
    $("#codbanco").val(comunicado[1]);
    $("#codconvenio").val(comunicado[2]);
    $("#codtabela").val(comunicado[3]);
    $("#prazo").val(comunicado[4]);
    $("#vlsolicitado").val(comunicado[5]);
    $("#codstatusComunicado").css("display", "");
    $("#codstatusComunicado").val(comunicado[6]);
    $("#btInserirComunicado").css("display", "none");
    $("#btAtualizarComunicado").css("display", "");
    $("#btExcluirComunicado").css("display", "");
    $("#status_comunicado").css("display", "");
    document.getElementById("fcomunicado").action = "../control/AtualizarComunicado.php";
}

function btNovoComunicado() {
    $("#codbanco1").val("");
    $("#codbanco").val("");
    $("#codconvenio").val("");
    $("#codtabela").val("");
    $("#prazo").val("");
    $("#vlsolicitado").val("");
    $("#codstatusComunicado").css("display", "none");
    $("#codstatusComunicado").val("");
    $("#btInserirComunicado").css("display", "");
    $("#btAtualizarComunicado").css("display", "none");
    $("#btExcluirComunicado").css("display", "none");
    document.getElementById("fcomunicado").action = "../control/InserirComunicado.php";
}

function procurarComunicado(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarComunicado2.php",
        type: "POST",
        data: $("#fPcomunicado").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de comunicados!", "error");
            }
            document.getElementById("listagemComunicado").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioComunicado() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fPcomunicado").submit();
}

function abreRelatorioComunicado2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fPcomunicado").submit();
}

function procurarComunicadoInicial(codcomunicado) {
    TINY.box.show({url: '../control/TextoComunicado.php?codcomunicado=' + codcomunicado, width: 700, height: 300, opacity: 20, topsplit: 3});
}

/**daqui para baixa responsável pelo ajax de inserir ou atualizar arquivo e também pelo upload sem redirecionar página*/
(function () {

    if (document.getElementById("fcomunicado") != null) {
        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('#status');

        $("#fcomunicado").submit(function () {
            $(".progress").css("visibility", "visible");
        });

        $('#fcomunicado').ajaxForm({
            beforeSend: function () {
                status.empty();
                var percentVal = '0%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            uploadProgress: function (event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                bar.width(percentVal)
                percent.html(percentVal);
            },
            success: function () {
                var percentVal = '100%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            complete: function (xhr) {
                var data = JSON.parse(xhr.responseText);
                if (data.situacao === true) {
                    swal("Cadastro", data.mensagem, "success");
                    procurarComunicado(true);
                } else if (data.situacao === false) {
                    swal("Erro", data.mensagem, "error");
                }
            }
        });
    }
})();
