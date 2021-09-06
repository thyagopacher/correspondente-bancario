function inserir() {
    $.ajax({
        url: "../control/InserirBaixa.php",
        type: "POST",
        data: $("#fbaixa").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Baixa cadastrado", data.mensagem, "success");
                procurarBaixa2(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizar() {
    $.ajax({
        url: "../control/AtualizarBaixa.php",
        type: "POST",
        data: $("#fbaixa").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Baixa atualizado", data.mensagem, "success");
                procurarBaixa2(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirBaixa() {
    if (window.confirm("Deseja realmente excluir esse baixa?")) {
        if (document.getElementById("codbaixa").value !== null && document.getElementById("codbaixa").value !== "") {
            $.ajax({
                url: "../control/ExcluirBaixa.php",
                type: "POST",
                data: {codbaixa: document.getElementById("codbaixa").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Baixa excluido", data.mensagem, "success");
                        procurarBaixa2(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em braixa", "Por favor escolha o baixa para excluir!", "error");
        }
    }
}

function excluir2Baixa(codbaixa) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa baixa!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codbaixa !== null && codbaixa !== "") {
                $.ajax({
                    url: "../control/ExcluirBaixa.php",
                    type: "POST",
                    data: {codbaixa: codbaixa},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Baixa excluido", data.mensagem, "success");
                            procurarBaixa2(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em braixa", "Por favor escolha o baixa para excluir!", "error");
            }
        }
    });
}

function setaEditarBaixa(baixa) {
    document.getElementById("codbaixa").value = baixa[0];
    document.getElementById("cpf").value = baixa[1];
    document.getElementById("valor").value = baixa[2];
    document.getElementById("dtcadastro").value = baixa[3];
    $("#btatualizarBaixa").css("display", "");
    $("#btexcluirBaixa").css("display", "");
    $("#btnovoBaixa").css("display", "");
    $("#btinserirBaixa").css("display", "none");
    $("#tabs").tabs({
        active: 0
    });        
}

function btNovoBaixa() {
    document.getElementById("codbaixa").value = "";
    document.getElementById("cpf").value = "";
    document.getElementById("valor").value = "";
    document.getElementById("dtcadastro").value = "";
    $("#btatualizarBaixa").css("display", "none");
    $("#btexcluirBaixa").css("display", "none");
    $("#btnovoBaixa").css("display", "none");
    $("#btinserirBaixa").css("display", "");
}

function procurarBaixa(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarBaixa.php",
        type: "POST",
        data: $("#fPbaixa").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de baixas!", "error");
            }
            document.getElementById("listagemBaixa").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function procurarBaixa2(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarBaixa2.php",
        type: "POST",
        data: $("#fPbaixa").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de baixas!", "error");
            }
            document.getElementById("listagemBaixa").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioBaixa() {
    document.getElementById("fPbaixa").submit();
}

function abreRelatorioBaixa2() {
    document.getElementById("fPbaixa2").submit();
}

$(function () {
   
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fbaixa').ajaxForm({
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
                procurarBaixa2(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});
