function inserirDocumento() {
    $.ajax({
        url: "../control/InserirDocumento.php",
        type: "POST",
        data: $("#fdocumento").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Documento cadastrado", data.mensagem, "success");
                procurarDocumento(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarDocumento() {
    $.ajax({
        url: "../control/AtualizarDocumento.php",
        type: "POST",
        data: $("#fdocumento").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Documento atualizado", data.mensagem, "success");
                procurarDocumento(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirDocumento() {
    if (window.confirm("Deseja realmente excluir esse documento?")) {
        if (document.getElementById("coddocumento").value !== null && document.getElementById("coddocumento").value !== "") {
            $.ajax({
                url: "../control/ExcluirDocumento.php",
                type: "POST",
                data: {coddocumento: document.getElementById("coddocumento").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Documento excluido", data.mensagem, "success");
                        procurarDocumento(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o documento para excluir!", "error");
        }
    }
}

function excluir2Documento(coddocumento) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa documento!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (coddocumento !== null && coddocumento !== "") {
                $.ajax({
                    url: "../control/ExcluirDocumento.php",
                    type: "POST",
                    data: {coddocumento: coddocumento},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Documento excluido", data.mensagem, "success");
                            procurarDocumento(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha o documento para excluir!", "error");
            }
        }
    });
}

function setaEditar(documento) {
    document.getElementById("coddocumento").value = documento[0];
    document.getElementById("nome").value = documento[1];
    document.getElementById("telefone").value = documento[2];
    document.getElementById("email").value = documento[3];
    document.getElementById("senha").value = documento[4];
    document.getElementById("celular").value = documento[5];
    document.getElementById("imagemCarregada").innerHTML = "<img src='../arquivos/" + documento[6] + "' alt='Imagem da documento' title='Imagem da documento'/>";
    $("#btatualizarDocumento").css("display", "");
    $("#btexcluirDocumento").css("display", "");
    $("#btnovoDocumento").css("display", "");
    $("#btinserirDocumento").css("display", "none");
    $("#codnivel option[value='" + documento[7] + "']").attr("selected", true);
}

function btNovoDocumento() {
    location.href = "Documento.php";
}

function procurarDocumento(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarDocumento.php",
        type: "POST",
        data: $("#fpdocumento").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de documentos!", "error");
            }
            document.getElementById("listagemDocumento").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioDocumento() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpdocumento").submit();
}

function abreRelatorioDocumento2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpdocumento").submit();
}

$(function () {
   
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fdocumento').ajaxForm({
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
                procurarDocumento(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});
