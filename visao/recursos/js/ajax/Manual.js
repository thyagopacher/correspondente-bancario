function inserirManual() {
    $.ajax({
        url: "../control/InserirManual.php",
        type: "POST",
        data: $("#fmanual").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Manual cadastrada", data.mensagem, "success");
                procurarManual(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarManual() {
    $.ajax({
        url: "../control/AtualizarManual.php",
        type: "POST",
        data: $("#fmanual").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Manual atualizada", data.mensagem, "success");
                procurarManual(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirManual() {
    if (window.confirm("Deseja realmente excluir esse manual?")) {
        if (document.getElementById("codmanual").value !== null && document.getElementById("codmanual").value !== "") {
            $.ajax({
                url: "../control/ExcluirManual.php",
                type: "POST",
                data: {codmanual: document.getElementById("codmanual").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Manual excluida", data.mensagem, "success");
                        procurarManual(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha a manual para excluir!", "error");
        }
    }
}

function excluir2Manual(codmanual) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa manual!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codmanual !== null && codmanual !== "") {
                $.ajax({
                    url: "../control/ExcluirManual.php",
                    type: "POST",
                    data: {codmanual: codmanual},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Manual excluida", data.mensagem, "success");
                            procurarManual(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha a manual para excluir!", "error");
            }
        }
    });
}

function setaManual(manual){
    $("#codmanual").val(manual[0]);
    $("#codbanco").val(manual[1]);
    $("#codconvenio").val(manual[2]);
    $("#codtabela").val(manual[3]);
    $("#prazo").val(manual[4]);
    $("#vlsolicitado").val(manual[5]);
    $("#codstatusManual").css("display", "");
    $("#codstatusManual").val(manual[6]);
    $("#btInserirManual").css("display", "none");
    $("#btAtualizarManual").css("display", "");
    $("#btExcluirManual").css("display", "");    
    $("#status_manual").css("display", "");
    document.getElementById("fmanual").action = "../control/AtualizarManual.php";
}

function btNovoManual() {
    $("#codbanco1").val("");
    $("#codbanco").val("");
    $("#codconvenio").val("");
    $("#codtabela").val("");
    $("#prazo").val("");
    $("#vlsolicitado").val("");
    $("#codstatusManual").css("display", "none");
    $("#codstatusManual").val("");    
    $("#btInserirManual").css("display", "");
    $("#btAtualizarManual").css("display", "none");
    $("#btExcluirManual").css("display", "none");
    document.getElementById("fmanual").action = "../control/InserirManual.php";
}

function procurarManual(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarManual2.php",
        type: "POST",
        data: $("#fPmanual").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de manuais!", "error");
            }
            document.getElementById("listagemManual").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioManual() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fPmanual").submit();
}

function abreRelatorioManual2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fPmanual").submit();
}

function procurarManualInicial() {
    TINY.box.show({url: '../control/ProcurarManual.php', width: 700, height: 300, opacity: 20, topsplit: 3});
}

/**daqui para baixa responsável pelo ajax de inserir ou atualizar arquivo e também pelo upload sem redirecionar página*/
(function () {
    if(document.getElementById("fmanual") != null){
        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('#status');

        $("#fmanual").submit(function(){
            $(".progress").css("visibility", "visible"); 
        });

        $('#fmanual').ajaxForm({
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
                if(data.situacao === true){
                    swal("Cadastro", data.mensagem, "success");
                    procurarManual(true);
                }else if(data.situacao === false){
                    swal("Erro", data.mensagem, "error");
                }    
            }
        });
    }
})();
