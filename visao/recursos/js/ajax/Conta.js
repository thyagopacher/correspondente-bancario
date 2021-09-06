function inserirConta() {
    if ($("#nome").val() !== "" && $("#valor").val() !== "") {
        $.ajax({
            url: "../control/InserirConta.php",
            type: "POST",
            data: $("#fconta").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Conta cadastrada", data.mensagem, "success");
                    procurarConta(true);
                } else if (data.situacao === false) {
                    swal("Erro ao cadastrar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else if ($("#nome").val() === "") {
        swal("Campo em branco", "Por favor defina um nome!", "error");
    } else if ($("#valor").val() === "") {
        swal("Campo em branco", "Por favor defina um valor!", "error");
    }
}

function pagaConta(codconta) {
    $.ajax({
        url: "../control/PagaConta.php",
        type: "POST",
        data: {codconta: codconta},
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Conta paga", data.mensagem, "success");
                procurarConta(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarConta() {
    $.ajax({
        url: "../control/AtualizarConta.php",
        type: "POST",
        data: $("#fconta").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Conta atualizada", data.mensagem, "success");
                procurarConta(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirConta() {
    if (window.confirm("Deseja realmente excluir essa conta?")) {
        if (document.getElementById("codconta").value !== null && document.getElementById("codconta").value !== "") {
            $.ajax({
                url: "../control/ExcluirConta.php",
                type: "POST",
                data: {codconta: document.getElementById("codconta").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Conta excluida", data.mensagem, "success");
                        procurarConta(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha uma conta para excluir!", "error");
        }
    }
}

function excluir2Conta(codigo) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa conta!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ela!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codigo !== null && codigo !== "") {
                $.ajax({
                    url: "../control/ExcluirConta.php",
                    type: "POST",
                    data: {codconta: codigo},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Conta excluida", data.mensagem, "success");
                            procurarConta(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha uma conta para excluir!", "error");
            }
        }
    });
}

function excluir2ArquivoConta(codigo) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações desse arquivo conta!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codigo !== null && codigo !== "") {
                $.ajax({
                    url: "../control/ExcluirArquivoConta.php",
                    type: "POST",
                    data: {codarquivo: codigo},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Arquivo Conta excluido", data.mensagem, "success");
                            procurarConta(true);
                            setTimeout('location.reload();', 1500);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha um arquivo conta para excluir!", "error");
            }
        }
    });
}

function procurarConta(acao) {
    if (document.getElementById("listagem") != null) {
        $("#carregando").show();
        $.ajax({
            url: "../control/ProcurarConta2.php",
            type: "POST",
            data: $("#fpconta").serialize(),
            dataType: 'text',
            success: function (data, textStatus, jqXHR) {
                if (acao === false && data === "") {
                    swal("Atenção", "Nada encontrado de contas!", "error");
                }
                document.getElementById("listagem").innerHTML = data;
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
            }
        });
        $("#carregando").hide();
    }
}

function btNovoConta() {
    var master = "?master=" + $("#master").val();
    location.href = "Conta.php" + master;
}

function abreRelatorioConta() {
    document.getElementById("fpconta").action = "../control/ProcurarContaRelatorio.php";
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpconta").submit();
}

function abreRelatorio2Conta() {
    document.getElementById("fpconta").action = "../control/ProcurarContaRelatorio.php";
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpconta").submit();
}

function abreRelatorio3Conta() {
    document.getElementById("fpconta").action = "../control/ProcurarRateioRelatorio.php"
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpconta").submit();
}

function abreTiraFotoConta(codconta) {
    var oWin = window.open("TirarFotoConta.php?codconta=" + codconta, "Tirar Foto", "width=1250, height=550");
    if (oWin === null || typeof (oWin) === "undefined") {
        swal("Erro ao visualizar", "O Bloqueador de Pop-up esta ativado, desbloquei-o por favor!", "error");
    } else {
        window.close();
    }
}

function comboTipo(codempresa) {
    $.ajax({
        url: "../control/ComboTipo.php",
        type: "POST",
        data: {codempresa: codempresa},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            document.getElementById("codtipo").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function comboTipo2(codempresa) {
    $.ajax({
        url: "../control/ComboTipo.php",
        type: "POST",
        data: {codempresa: codempresa},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            document.getElementById("codtipo2").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

/**daqui para baixa responsável pelo ajax de inserir ou atualizar ambiente e também pelo upload sem redirecionar página*/
(function () {

    $("#filial").change(function () {
        comboTipo($("#filial option:selected").val());
    });

    $("#filial2").change(function () {
        console.log("Filial:" + $("#filial2 option:selected").val());
        comboTipo2($("#filial2 option:selected").val());
    });

    $("#dtpagamento").change(function () {
        $("#codstatus").val("1");
    });

    $("#fconta").submit(function () {
        $(".progress").css("visibility", "visible");
    });

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fconta').ajaxForm({
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
            bar.width(percentVal);
            percent.html(percentVal);
        },
        complete: function (xhr) {
            var data = JSON.parse(xhr.responseText);
            if (data.situacao === true) {
                swal("Alteração", data.mensagem, "success");
                if (data.imagem !== null && data.imagem !== "") {
                    $("#imagemCarregada").html("<img width='150' src='../arquivos/" + data.imagem + "'  alt='Imagem'/>");
                }
                procurarConta(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });

})();