function editarCliente(codpessoa, codigo) {
    location.href = "Cliente.php?codpessoa=" + codpessoa+ '&codproposta=' + codigo;
}

function inserirProposta() {
    $.ajax({
        url: "../control/InserirProposta.php",
        type: "POST",
        data: $("#fproposta").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Proposta cadastrada", data.mensagem, "success");
                procurarProposta(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarProposta() {
    $.ajax({
        url: "../control/AtualizarProposta.php",
        type: "POST",
        data: $("#fproposta").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Proposta atualizada", data.mensagem, "success");
                procurarProposta(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirProposta() {
    if (window.confirm("Deseja realmente excluir esse proposta?")) {
        if (document.getElementById("codproposta").value !== null && document.getElementById("codproposta").value !== "") {
            $.ajax({
                url: "../control/ExcluirProposta.php",
                type: "POST",
                data: {codproposta: document.getElementById("codproposta").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Proposta excluida", data.mensagem, "success");
                        procurarProposta(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha a proposta para excluir!", "error");
        }
    }
}

function excluir2Proposta(codproposta) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa proposta!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codproposta !== null && codproposta !== "") {
                $.ajax({
                    url: "../control/ExcluirProposta.php",
                    type: "POST",
                    data: {codproposta: codproposta},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Proposta excluida", data.mensagem, "success");
                            procurarProposta(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha a proposta para excluir!", "error");
            }
        }
    });
}

function mostraLinhaProposta(codproposta) {
    if ($("#proposta_oculta" + codproposta).css("display") == "none") {
        $("#proposta_oculta" + codproposta).css("display", "");
        $("#icone_botao_proposta" + codproposta).removeClass("fa fa-plus");
        $("#icone_botao_proposta" + codproposta).addClass("fa fa-minus");
    } else {
        $("#proposta_oculta" + codproposta).css("display", "none");
        $("#icone_botao_proposta" + codproposta).removeClass("fa fa-minus");
        $("#icone_botao_proposta" + codproposta).addClass("fa fa-plus");
    }
}

function setaProposta(proposta) {
    $("#codproposta").val(proposta[0]);
    $("#codbanco").val(proposta[1]);
    $("#codconvenio").val(proposta[2]);
    $("#codtabela").val(proposta[3]);
    $("#prazo").val(proposta[4]);
    $("#vlsolicitado").val(proposta[5]);
    $("#vlparcela").val(proposta[6]);
    $("#vlliberado").val(proposta[7]);
    $("#codbeneficio").val(proposta[8]);
    $("#peso").val(proposta[9]);
    $("#codbanco2").val(proposta[10]);
    $("#agencia").val(proposta[11]);
    $("#conta").val(proposta[12]);
    $("#operacao").val(proposta[13]);
    if (proposta[14] == "s") {
        $("#poupanca").attr("checked", true);
        $("#corrente").attr("checked", false);
    }

    $("#dtvenda").val(proposta[15]);
    $("#codvendedor").val(proposta[16]);
    $("#observacaoProposta").val(proposta[17]);
    $("#codstatusProposta").val(proposta[18]);
    $("#pendente").val(proposta[19]);
    $("#dtpago").val(proposta[20]);
    $("#beneficio").val(proposta[21]);
    $("#codespecie").val(proposta[22]);
    if (proposta[23] == "s") {
        $("#poupanca").attr("checked", false);
        $("#corrente").attr("checked", true);
    }
    if ($("#codstatusProposta").val() == "3") {
        $("#div_dtpago").css("display", "");
    } else {
        $("#div_dtpago").css("display", "none");
    }
    $("#dtpago").val(proposta[20]);
    $("#beneficio").val(proposta[21]);
    $("#codespecie").val(proposta[22]);
    $("#codstatusProposta").css("display", "");
    $("#btInserirProposta").css("display", "none");
    $("#btAtualizarProposta").css("display", "");
    $("#btExcluirProposta").css("display", "");
    $("#status_proposta").css("display", "");
    document.getElementById("fproposta").action = "../control/AtualizarProposta.php";
}

function btNovoProposta() {
    $("#codbanco1").val("");
    $("#codbanco").val("");
    $("#codconvenio").val("");
    $("#codtabela").val("");
    $("#prazo").val("");
    $("#vlsolicitado").val("");
    $("#codstatusProposta").css("display", "none");
    $("#codstatusProposta").val("");
    $("#btInserirProposta").css("display", "");
    $("#btAtualizarProposta").css("display", "none");
    $("#btExcluirProposta").css("display", "none");
    document.getElementById("fproposta").action = "../control/InserirProposta.php";
}

function procurarProposta(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarProposta.php",
        type: "POST",
        data: {codcliente: $("#codcliente").val()},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de propostas!", "error");
            }
            if (document.getElementById("listagemProposta") != null) {
                document.getElementById("listagemProposta").innerHTML = data;
                if (document.getElementById("listagemProposta2") != null) {
                    document.getElementById("listagemProposta2").innerHTML = data;
                }
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioProposta() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fproposta").submit();
}

function abreRelatorioProposta2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fproposta").submit();
}

function procurarDocumentosBanco(codbanco) {
    if (document.getElementById("documentos_banco") != null) {
        $.ajax({
            url: "../control/DocumentosBanco.php",
            type: "POST",
            data: {codbanco: codbanco},
            dataType: 'text',
            success: function (data, textStatus, jqXHR) {
                document.getElementById("documentos_banco").innerHTML = data;
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
            }
        });
    }
}

function procurarObservacaoProposta(codproposta, codcliente) {
    TINY.box.show({url: '../control/ObservacaoProposta.php', post: 'codcliente=' + codcliente + '&codproposta=' + codproposta, width: 700, height: 300, opacity: 20, topsplit: 3});
}

function procurarEsteiraInicial() {
    if (document.getElementById("esteira_inicial") != null) {
        $.ajax({
            url: "../control/EsteiraInicial.php",
            type: "POST",
            dataType: 'text',
            success: function (data, textStatus, jqXHR) {
                document.getElementById("esteira_inicial").innerHTML = data;
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro", "Erro causado por:" + errorThrown, "error");
            }
        });
    }
}

function clienteAvisado(codproposta) {
    $.ajax({
        url: "../control/ClienteAvisado.php",
        type: "POST",
        data: {codproposta: codproposta},
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Proposta atualizada", data.mensagem, "success");
                procurarEsteiraInicial();
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro", "Erro causado por:" + errorThrown, "error");
        }
    });
}

$(window).load(function () {
    procurarEsteiraInicial();
    
});

/**daqui para baixa responsável pelo ajax de inserir ou atualizar arquivo e também pelo upload sem redirecionar página*/
(function () {
procurarProposta(true);
    $("#codstatusProposta").val($("#statuspadraoproposta").val());

    $("#codconvenio").change(function () {
        if ($("#codconvenio option:selected").text() != "INSS") {
            $("#div_especie_proposta").css("visibility", "hidden");
            $("#codespecie").val("");
        } else {
            $("#div_especie_proposta").css("visibility", "");
        }
    });

    $("#codstatusProposta").change(function () {
        if (document.fproposta.codproposta.value == "" && $("#codstatusProposta").val() != $("#statuspadraoproposta").val()) {
            $("#codstatusProposta").val($("#statuspadraoproposta").val());
            swal("Atenção", "Status padrão em aguardando digitação não pode alterar no cadastramento!!!", "error");
        } else {
            if ($("#codstatusProposta").val() == "3") {
                $("#div_dtpago").css("display", "");
            } else {
                $("#div_dtpago").css("display", "none");
            }
        }
    });

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $("#fproposta").submit(function () {
        $(".progress").css("visibility", "visible");
    });

    $('#fproposta').ajaxForm({
        beforeSend: function () {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal);
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
                swal("Cadastro", data.mensagem, "success");
                procurarProposta(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
})();
