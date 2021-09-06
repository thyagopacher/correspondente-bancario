function inserir() {
    $.ajax({
        url: "../control/InserirOrgaoPagador.php",
        type: "POST",
        data: $("#forgao").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Orgao Pagador cadastrado", data.mensagem, "success");
                procurarOrgaoPagador(true);
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
        url: "../control/AtualizarOrgaoPagador.php",
        type: "POST",
        data: $("#forgao").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Orgao Pagador atualizado", data.mensagem, "success");
                procurarOrgaoPagador(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirOrgaoPagador() {
    if (window.confirm("Deseja realmente excluir esse orgao?")) {
        if (document.getElementById("codorgao").value !== null && document.getElementById("codorgao").value !== "") {
            $.ajax({
                url: "../control/ExcluirOrgaoPagador.php",
                type: "POST",
                data: {codorgao: document.getElementById("codorgao").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Orgao Pagador excluido", data.mensagem, "success");
                        procurarOrgaoPagador(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o orgao para excluir!", "error");
        }
    }
}

function excluir2OrgaoPagador(codorgao) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa orgao!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codorgao !== null && codorgao !== "") {
                $.ajax({
                    url: "../control/ExcluirOrgaoPagador.php",
                    type: "POST",
                    data: {codorgao: codorgao},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Orgao Pagador excluido", data.mensagem, "success");
                            procurarOrgaoPagador(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha o orgao para excluir!", "error");
            }
        }
    });
}

function setaEditar(orgao) {
    document.getElementById("codorgao").value = orgao[0];
    document.getElementById("nome").value = orgao[1];
    document.getElementById("telefone").value = orgao[2];
    document.getElementById("email").value = orgao[3];
    document.getElementById("senha").value = orgao[4];
    document.getElementById("celular").value = orgao[5];
    document.getElementById("imagemCarregada").innerHTML = "<img src='../arquivos/" + orgao[6] + "' alt='Imagem da orgao' title='Imagem da orgao'/>";
    $("#btatualizarOrgaoPagador").css("display", "");
    $("#btexcluirOrgaoPagador").css("display", "");
    $("#btnovoOrgaoPagador").css("display", "");
    $("#btinserirOrgaoPagador").css("display", "none");
    $("#codnivel option[value='" + orgao[7] + "']").attr("selected", true);
}

function btNovoOrgaoPagador() {
    location.href = "OrgaoPagador.php";
}

function procurarOrgaoPagador(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarOrgaoPagador2.php",
        type: "POST",
        data: $("#fPorgao").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagemOrgaoPagador").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioOrgaoPagador() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fporgao").submit();
}

function abreRelatorio2OrgaoPagador() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fporgao").submit();
}

$(function () {
   
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#forgao').ajaxForm({
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
                procurarOrgaoPagador(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});
