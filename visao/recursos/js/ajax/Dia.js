function inserirDia() {
    $.ajax({
        url: "../control/InserirDia.php",
        type: "POST",
        data: $("#fdia").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Dia cadastrado", data.mensagem, "success");
                procurarDia(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarDia() {
    $.ajax({
        url: "../control/AtualizarDia.php",
        type: "POST",
        data: $("#fdia").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Dia atualizado", data.mensagem, "success");
                procurarDia(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirDia() {
    if (window.confirm("Deseja realmente excluir esse dia?")) {
        if (document.getElementById("coddia").value !== null && document.getElementById("coddia").value !== "") {
            $.ajax({
                url: "../control/ExcluirDia.php",
                type: "POST",
                data: {coddia: document.getElementById("coddia").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Dia excluido", data.mensagem, "success");
                        procurarDia(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o dia para excluir!", "error");
        }
    }
}

function excluir2Dia(coddia) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa dia!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (coddia !== null && coddia !== "") {
                $.ajax({
                    url: "../control/ExcluirDia.php",
                    type: "POST",
                    data: {coddia: coddia},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Dia excluido", data.mensagem, "success");
                            procurarDia(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha o dia para excluir!", "error");
            }
        }
    });
}

function setaEditar(dia) {
    document.getElementById("coddia").value = dia[0];
    document.getElementById("nome").value = dia[1];
    document.getElementById("telefone").value = dia[2];
    document.getElementById("email").value = dia[3];
    document.getElementById("senha").value = dia[4];
    document.getElementById("celular").value = dia[5];
    document.getElementById("imagemCarregada").innerHTML = "<img src='../arquivos/" + dia[6] + "' alt='Imagem da dia' title='Imagem da dia'/>";
    $("#btatualizarDia").css("display", "");
    $("#btexcluirDia").css("display", "");
    $("#btnovoDia").css("display", "");
    $("#btinserirDia").css("display", "none");
    $("#codnivel option[value='" + dia[7] + "']").attr("selected", true);
}

function btNovoDia() {
    location.href = "Dia.php";
}

function procurarDia(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarDia2.php",
        type: "POST",
        data: $("#fPdia").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagemDia").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioDia() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpdia").submit();
}

function abreRelatorioDia2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpdia").submit();
}

$(function () {
   
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fdia').ajaxForm({
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
                procurarDia(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});
