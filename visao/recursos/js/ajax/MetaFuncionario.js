function inserirMetaFuncionario() {
    $.ajax({
        url: "../control/InserirMetaFuncionario.php",
        type: "POST",
        data: $("#fmeta").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Meta Funcionário cadastrado", data.mensagem, "success");
                procurarMetaFuncionario(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarMetaFuncionario() {
    $.ajax({
        url: "../control/AtualizarMetaFuncionario.php",
        type: "POST",
        data: $("#fmeta").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Meta Funcionário atualizado", data.mensagem, "success");
                procurarMetaFuncionario(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirMetaFuncionario() {
    if (window.confirm("Deseja realmente excluir esse meta?")) {
        if (document.getElementById("codmeta").value !== null && document.getElementById("codmeta").value !== "") {
            $.ajax({
                url: "../control/ExcluirMetaFuncionario.php",
                type: "POST",
                data: {codmeta: document.getElementById("codmeta").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Meta Funcionário excluido", data.mensagem, "success");
                        procurarMetaFuncionario(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o meta para excluir!", "error");
        }
    }
}

function excluir2MetaFuncionario(codmeta) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa meta!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codmeta !== null && codmeta !== "") {
                $.ajax({
                    url: "../control/ExcluirMetaFuncionario.php",
                    type: "POST",
                    data: {codmeta: codmeta},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Meta Funcionário excluido", data.mensagem, "success");
                            procurarMetaFuncionario(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha o meta para excluir!", "error");
            }
        }
    });
}

function setaEditarMetaFuncionario(meta) {
    document.getElementById("codmeta").value = meta[0];
    document.getElementById("codfuncionario").value = meta[1];
    document.getElementById("valorMeta").value = meta[2];
    document.getElementById("dtinicio").value = meta[3];
    document.getElementById("dtfim").value = meta[4];    
    $("#btatualizarMetaFuncionario").css("display", "");
    $("#btexcluirMetaFuncionario").css("display", "");
    $("#btnovoMetaFuncionario").css("display", "");
    $("#btinserirMetaFuncionario").css("display", "none");
    $("#tabs").tabs({
        active: 0
    });   
}

function btNovoMetaFuncionario() {
    document.getElementById("codmeta").value = "";
    document.getElementById("codfuncionario").value = "";
    document.getElementById("valorMeta").value = "";
    document.getElementById("dtinicio").value = "";
    document.getElementById("dtfim").value = "";
    $("#btatualizarMetaFuncionario").css("display", "none");
    $("#btexcluirMetaFuncionario").css("display", "none");
    $("#btnovoMetaFuncionario").css("display", "none");
    $("#btinserirMetaFuncionario").css("display", "");
    $("#tabs").tabs({
        active: 2
    });   
}

function procurarMetaFuncionario(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarMetaFuncionario2.php",
        type: "POST",
        data: $("#fPmeta").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de meta funcionário!", "error");
            }
            document.getElementById("listagemMetaFuncionario").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioMetaFuncionario() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpmeta").submit();
}

function abreRelatorioMetaFuncionario2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpmeta").submit();
}

$(function () {
   
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fmeta').ajaxForm({
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
                procurarMetaFuncionario(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});
