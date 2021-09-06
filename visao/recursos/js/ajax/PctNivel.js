function inserirPctNivel() {
    $.ajax({
        url: "../control/InserirPctNivel.php",
        type: "POST",
        data: $("#fpctnivel").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("PctNivel cadastrado", data.mensagem, "success");
                procurarPctNivel2(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarPctNivel() {
    $.ajax({
        url: "../control/AtualizarPctNivel.php",
        type: "POST",
        data: $("#fpctnivel").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("PctNivel atualizado", data.mensagem, "success");
                procurarPctNivel2(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirPctNivel() {
    if (window.confirm("Deseja realmente excluir esse pctnivel?")) {
        if (document.getElementById("codpct").value !== null && document.getElementById("codpct").value !== "") {
            $.ajax({
                url: "../control/ExcluirPctNivel.php",
                type: "POST",
                data: {codpct: document.getElementById("codpct").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("PctNivel excluido", data.mensagem, "success");
                        procurarPctNivel2(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em braixa", "Por favor escolha o pctnivel para excluir!", "error");
        }
    }
}

function excluir2PctNivel(codpct) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa pctnivel!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codpct !== null && codpct !== "") {
                $.ajax({
                    url: "../control/ExcluirPctNivel.php",
                    type: "POST",
                    data: {codpct: codpct},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("PctNivel excluido", data.mensagem, "success");
                            procurarPctNivel2(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em braixa", "Por favor escolha o pctnivel para excluir!", "error");
            }
        }
    });
}

function setaEditarPctNivel(pctnivel) {

    document.getElementById("codpct").value = pctnivel[0];
    document.getElementById("codnivelPct").value = pctnivel[1];
    document.getElementById("porcentagem").value = pctnivel[2];

    $("#btatualizarPctNivel").css("display", "");
    $("#btexcluirPctNivel").css("display", "");
    $("#btnovoPctNivel").css("display", "");
    $("#btinserirPctNivel").css("display", "none");
    $("#tabs").tabs({
        active: 1
    });
}

function btNovoPctNivel() {
    document.getElementById("codpct").value = "";
    document.getElementById("cpf").value = "";
    document.getElementById("valor").value = "";
    document.getElementById("dtcadastro").value = "";
    $("#btatualizarPctNivel").css("display", "none");
    $("#btexcluirPctNivel").css("display", "none");
    $("#btnovoPctNivel").css("display", "none");
    $("#btinserirPctNivel").css("display", "");
}

function procurarPctNivel(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarPctNivel.php",
        type: "POST",
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de pctnivels!", "error");
            }
            document.getElementById("listagemPctNivel").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function procurarPctNivel2(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarPctNivel.php",
        type: "POST",
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de pctnivels!", "error");
            }
            document.getElementById("listagemPctNivel").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioPctNivel() {
    document.getElementById("fPpctnivel").submit();
}

function abreRelatorioPctNivel2() {
    document.getElementById("fPpctnivel2").submit();
}

$(function () {

    procurarPctNivel();
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fpctnivel').ajaxForm({
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
                procurarPctNivel2(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});
