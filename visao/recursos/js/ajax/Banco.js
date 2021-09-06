function inserir() {
    $.ajax({
        url: "../control/InserirBanco.php",
        type: "POST",
        data: $("#fbanco").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Banco cadastrado", data.mensagem, "success");
                procurarBanco(true);
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
        url: "../control/AtualizarBanco.php",
        type: "POST",
        data: $("#fbanco").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Banco atualizado", data.mensagem, "success");
                procurarBanco(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirBanco() {
    if (window.confirm("Deseja realmente excluir esse banco?")) {
        if (document.getElementById("codbanco").value !== null && document.getElementById("codbanco").value !== "") {
            $.ajax({
                url: "../control/ExcluirBanco.php",
                type: "POST",
                data: {codbanco: document.getElementById("codbanco").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Banco excluido", data.mensagem, "success");
                        procurarBanco(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o banco para excluir!", "error");
        }
    }
}

function excluir2Banco(codbanco) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa banco!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codbanco !== null && codbanco !== "") {
                $.ajax({
                    url: "../control/ExcluirBanco.php",
                    type: "POST",
                    data: {codbanco: codbanco},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Banco excluido", data.mensagem, "success");
                            procurarBanco(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha o banco para excluir!", "error");
            }
        }
    });
}

function setaEditar(banco) {
    document.getElementById("codbanco").value = banco[0];
    document.getElementById("nome").value = banco[1];
    document.getElementById("telefone").value = banco[2];
    document.getElementById("email").value = banco[3];
    document.getElementById("senha").value = banco[4];
    document.getElementById("celular").value = banco[5];
    document.getElementById("imagemCarregada").innerHTML = "<img src='../arquivos/" + banco[6] + "' alt='Imagem da banco' title='Imagem da banco'/>";
    $("#btatualizarBanco").css("display", "");
    $("#btexcluirBanco").css("display", "");
    $("#btnovoBanco").css("display", "");
    $("#btinserirBanco").css("display", "none");
    $("#codnivel option[value='" + banco[7] + "']").attr("selected", true);
}

function btNovoBanco() {
    location.href = "Banco.php";
}

function procurarBanco(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarBanco2.php",
        type: "POST",
        data: $("#fProcurarBanco").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de bancos!", "error");
            }
            document.getElementById("listagemBanco").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioBanco() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fProcurarBanco").submit();
}

function abreRelatorioBanco2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fProcurarBanco").submit();
}

$(function () {
   
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fbanco').ajaxForm({
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
                procurarBanco(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});
