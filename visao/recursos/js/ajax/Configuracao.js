function inserirConfiguracao() {
    $.ajax({
        url: "../control/InserirConfiguracao.php",
        type: "POST",
        data: $("#fconfiguracao").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Configuracao cadastrada", data.mensagem, "success");
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarConfiguracao() {
    $.ajax({
        url: "../control/AtualizarConfiguracao.php",
        type: "POST",
        data: $("#fconfiguracao").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Configuracao atualizada", data.mensagem, "success");
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirConfiguracao() {
    if (window.confirm("Deseja realmente excluir essa configuração?")) {
        if (document.getElementById("codconfiguracao").value !== null && document.getElementById("codconfiguracao").value !== "") {
            $.ajax({
                url: "../control/ExcluirConfiguracao.php",
                type: "POST",
                data: {codconfiguracao: document.getElementById("codconfiguracao").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Configuracao excluida", data.mensagem, "success");
                        procurarConfiguracao(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha uma configuracao para excluir!", "error");
        }
    }
}

function excluir2Configuracao(codigo) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa configuração!",
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
                    url: "../control/ExcluirConfiguracao.php",
                    type: "POST",
                    data: {codconfiguracao: codigo},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Configuracao excluida", data.mensagem, "success");
                            procurarConfiguracao(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha uma configuracao para excluir!", "error");
            }
        }
    });
}

function procurarConfiguracao(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarConfiguracao2.php",
        type: "POST",
        data: $("#fpconfiguracao").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de configuracaos!", "error");
            }
            document.getElementById("lisfiguracaogem").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function btNovoConfiguracao() {
    var master = "?master=" + $("#master").val();
    location.href = "Configuracao.php" + master;
}

function abreRelatorioConfiguracao() {
    document.getElementById("fpconfiguracao").action = "../control/ProcurarConfiguracaoRelatorio.php";
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpconfiguracao").submit();
}

function abreRelatorio2Configuracao() {
    document.getElementById("fpconfiguracao").action = "../control/ProcurarConfiguracaoRelatorio.php";
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpconfiguracao").submit();
}
