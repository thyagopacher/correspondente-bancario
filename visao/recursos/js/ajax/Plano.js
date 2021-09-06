function inserir() {
    $.ajax({
        url: "../control/InserirPlano.php",
        type: "POST",
        data: $("#fplano").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Plano cadastrado", data.mensagem, "success");
                procurarPlano(true);
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
        url: "../control/AtualizarPlano.php",
        type: "POST",
        data: $("#fplano").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Plano atualizado", data.mensagem, "success");
                procurarPlano(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirPlano() {
    if (window.confirm("Deseja realmente excluir esse plano?")) {
        if (document.getElementById("codplano").value !== null && document.getElementById("codplano").value !== "") {
            $.ajax({
                url: "../control/ExcluirPlano.php",
                type: "POST",
                data: {codplano: document.getElementById("codplano").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Plano excluido", data.mensagem, "success");
                        procurarPlano(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o plano para excluir!", "error");
        }
    }
}

function excluir2Plano(codplano) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa plano!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codplano !== null && codplano !== "") {
                $.ajax({
                    url: "../control/ExcluirPlano.php",
                    type: "POST",
                    data: {codplano: codplano},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Plano excluido", data.mensagem, "success");
                            procurarPlano(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha o plano para excluir!", "error");
            }
        }
    });
}

function setaEditar(plano) {
    document.getElementById("codplano").value = plano[0];
    document.getElementById("nome").value = plano[1];
    document.getElementById("telefone").value = plano[2];
    document.getElementById("email").value = plano[3];
    document.getElementById("senha").value = plano[4];
    document.getElementById("celular").value = plano[5];
    document.getElementById("imagemCarregada").innerHTML = "<img src='../arquivos/" + plano[6] + "' alt='Imagem da plano' title='Imagem da plano'/>";
    $("#btatualizarPlano").css("display", "");
    $("#btexcluirPlano").css("display", "");
    $("#btnovoPlano").css("display", "");
    $("#btinserirPlano").css("display", "none");
    $("#codnivel option[value='" + plano[7] + "']").attr("selected", true);
}

function btNovoPlano() {
    location.href = "Plano.php";
}

function procurarPlano(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarPlano.php",
        type: "POST",
        data: $("#fPplano").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de planos!", "error");
            }
            document.getElementById("listagemPlano").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioPlano() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fProcurarPlano").submit();
}

function abreRelatorioPlano2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fProcurarPlano").submit();
}

$(function () {
   
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fplano').ajaxForm({
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
                procurarPlano(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});
