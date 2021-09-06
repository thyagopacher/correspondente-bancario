function inserirLink() {
    $.ajax({
        url: "../control/InserirLink.php",
        type: "POST",
        data: $("#flink").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Link cadastrado", data.mensagem, "success");
                procurarLink(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarLink() {
    $.ajax({
        url: "../control/AtualizarLink.php",
        type: "POST",
        data: $("#flink").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Link atualizado", data.mensagem, "success");
                procurarLink(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirLink() {
    if (window.confirm("Deseja realmente excluir esse link?")) {
        if (document.getElementById("codlink").value !== null && document.getElementById("codlink").value !== "") {
            $.ajax({
                url: "../control/ExcluirLink.php",
                type: "POST",
                data: {codlink: document.getElementById("codlink").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Link excluido", data.mensagem, "success");
                        procurarLink(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha a link para excluir!", "error");
        }
    }
}

function excluir2Link(codlink) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa link!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codlink !== null && codlink !== "") {
                $.ajax({
                    url: "../control/ExcluirLink.php",
                    type: "POST",
                    data: {codlink: codlink},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Link excluido", data.mensagem, "success");
                            procurarLink(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha a link para excluir!", "error");
            }
        }
    });
}

function btNovoLink() {
    $("#codbanco1").val("");
    $("#codbanco").val("");
    $("#codconvenio").val("");
    $("#codtabela").val("");
    $("#prazo").val("");
    $("#vlsolicitado").val("");
    $("#codstatusLink").css("display", "none");
    $("#codstatusLink").val("");
    $("#btInserirLink").css("display", "");
    $("#btAtualizarLink").css("display", "none");
    $("#btExcluirLink").css("display", "none");
    document.getElementById("flink").action = "../control/InserirLink.php";
}

function procurarLink(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarLink.php",
        type: "POST",
        data: $("#fplink").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de links!", "error");
            }
            document.getElementById("listagemLink").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioLink() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fplink").submit();
}

function abreRelatorioLink2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fplink").submit();
}

function procurarLinkInicial() {
    TINY.box.show({url: '../control/ProcurarLink.php', width: 700, height: 300, opacity: 20, topsplit: 3});
}

/**daqui para baixa responsável pelo ajax de inserir ou atualizar arquivo e também pelo upload sem redirecionar página*/
(function () {

    if (document.getElementById("flink") != null) {
        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('#status');

        $("#flink").submit(function () {
            $(".progress").css("visibility", "visible");
        });

        $('#flink').ajaxForm({
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
                    procurarLink(true);
                } else if (data.situacao === false) {
                    swal("Erro", data.mensagem, "error");
                }
            }
        });
    }
})();
