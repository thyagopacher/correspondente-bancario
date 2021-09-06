function inserir() {
    $.ajax({
        url: "../control/InserirConvenio.php",
        type: "POST",
        data: $("#fconvenio").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Convenio cadastrado", data.mensagem, "success");
                procurarConvenio(true);
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
        url: "../control/AtualizarConvenio.php",
        type: "POST",
        data: $("#fconvenio").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Convenio atualizado", data.mensagem, "success");
                procurarConvenio(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirConvenio() {
    if (window.confirm("Deseja realmente excluir esse convenio?")) {
        if (document.getElementById("codconvenio").value !== null && document.getElementById("codconvenio").value !== "") {
            $.ajax({
                url: "../control/ExcluirConvenio.php",
                type: "POST",
                data: {codconvenio: document.getElementById("codconvenio").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Convenio excluido", data.mensagem, "success");
                        procurarConvenio(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o convenio para excluir!", "error");
        }
    }
}

function excluir2Convenio(codconvenio) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa convenio!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codconvenio !== null && codconvenio !== "") {
                $.ajax({
                    url: "../control/ExcluirConvenio.php",
                    type: "POST",
                    data: {codconvenio: codconvenio},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Convenio excluido", data.mensagem, "success");
                            procurarConvenio(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha o convenio para excluir!", "error");
            }
        }
    });
}

function setaEditar(convenio) {
    document.getElementById("codconvenio").value = convenio[0];
    document.getElementById("nome").value = convenio[1];
    document.getElementById("telefone").value = convenio[2];
    document.getElementById("email").value = convenio[3];
    document.getElementById("senha").value = convenio[4];
    document.getElementById("celular").value = convenio[5];
    document.getElementById("imagemCarregada").innerHTML = "<img src='../arquivos/" + convenio[6] + "' alt='Imagem da convenio' title='Imagem da convenio'/>";
    $("#btatualizarConvenio").css("display", "");
    $("#btexcluirConvenio").css("display", "");
    $("#btnovoConvenio").css("display", "");
    $("#btinserirConvenio").css("display", "none");
    $("#codnivel option[value='" + convenio[7] + "']").attr("selected", true);
}

function btNovoConvenio() {
    location.href = "Convenio.php";
}

function procurarConvenio(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarConvenio2.php",
        type: "POST",
        data: $("#fpconvenio").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado convenio!", "error");
            }
            document.getElementById("listagemConvenio").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioConvenio() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpconvenio").submit();
}

function abreRelatorioConvenio2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpconvenio").submit();
}

$(function () {
   
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fconvenio').ajaxForm({
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
                procurarConvenio(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});
