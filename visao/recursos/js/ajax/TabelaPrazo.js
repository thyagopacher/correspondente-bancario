function inserirTabelaPrazo() {
    $.ajax({
        url: "../control/InserirTabelaPrazo.php",
        type: "POST",
        data: $("#ftabelap").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Tabela X Prazo cadastrada", data.mensagem, "success");
                procurarTabela(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarTabelaPrazo() {
    $.ajax({
        url: "../control/AtualizarTabelaPrazo.php",
        type: "POST",
        data: $("#ftabelap").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Tabela X Prazo atualizada", data.mensagem, "success");
                procurarTabela(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirTabelaPrazo() {
    if (window.confirm("Deseja realmente excluir esse tabela?")) {
        if (document.getElementById("codtabelap").value !== null && document.getElementById("codtabelap").value !== "") {
            $.ajax({
                url: "../control/ExcluirTabelaPrazo.php",
                type: "POST",
                data: {codtabelap: document.getElementById("codtabelap").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tabela X Prazo excluida", data.mensagem, "success");
                        procurarTabela(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o tabela para excluir!", "error");
        }
    }
}

function excluir2TabelaPrazo(codtabelap) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa tabela!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codtabelap !== null && codtabelap !== "") {
                $.ajax({
                    url: "../control/ExcluirTabelaPrazo.php",
                    type: "POST",
                    data: {codtabelap: codtabelap},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Tabela X Prazo excluida", data.mensagem, "success");
                            procurarTabela(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha o tabela para excluir!", "error");
            }
        }
    });
}

function setaEditarTabelap(tabela) {
    document.getElementById("codtabelap").value = tabela[0];
    document.getElementById("prazode").value = tabela[1];
    document.getElementById("prazoate").value = tabela[2];
    document.getElementById("codtabela").value = tabela[3];
    document.getElementById("comissao").value = tabela[4];
    $("#btAtualizarTabelap").css("display", "");
    $("#btExcluirTabelap").css("display", "");
    $("#btnovoTabelap").css("display", "");
    $("#btInserirTabelap").css("display", "none");
    $("#tabs").tabs({
        active: 2
    });     
}

function btNovoTabelaPrazo() {
    document.getElementById("codtabelap").value = "";
    document.getElementById("prazode").value = "";
    document.getElementById("prazoate").value = "";
    document.getElementById("codtabela").value = "";
    document.getElementById("comissao").value = "";
    $("#btAtualizarTabelap").css("display", "none");
    $("#btExcluirTabelap").css("display", "none");
    $("#btnovoTabelap").css("display", "none");
    $("#btInserirTabelap").css("display", "");
    $("#tabs").tabs({
        active: 2
    });    
}

function procurarTabelaPrazo(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarTabelaPrazo.php",
        type: "POST",
        data: $("#fptabelap").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagemTabelaPrazo").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioTabela() {
    document.getElementById("fPtabela").submit();
}

function abreRelatorioTabela2() {
    document.getElementById("fPtabela2").submit();
}
