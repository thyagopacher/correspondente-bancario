function pulaPagina(numero) {
    TINY.box.show({url: '../visao/instalador/instalador' + numero + '.php', width: 800, height: 400, opacity: 20, topsplit: 3});

}
/*
 * FECHAR POPUP
 */
function fecharJanela() {
    TINY.box.hide();
}

function atualizarPlano() {
    $.ajax({
        url: "../../control/AtualizarPlanoEmpresa.php",
        type: "POST",
        data: $("#fplano").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Plano salvo", data.mensagem, "success");
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXRH, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}


//TINY.box.hide(); fecha popup....



function atualizarEmpresa() {
    $.ajax({
        url: "../../control/AtualizarEmpresa.php",
        type: "POST",
        data: $("#fempresa").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Empresa salva", data.mensagem, "success");
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}
/*
 * VALIDAR TELEFONE FIXO
 */
function validarInteiro(id) {
    if (isNaN(document.getElementById(id).value)) {
        alert("Digite apenas números!");
        document.getElementById(id).select();
        document.getElementById(id).value = '';
        return false;
    } else {
        return false;
    }
}

/**
 * inserir SMS padrão texto
 * */
function inserirSmsPadrao() {
    if ($('#nome').val() == "") {
        swal("Erro ao cadastrar", "Nome não pode ser vazio!");
    } else {
        $.ajax({
            url: "../../control/InserirSmsPadrao.php",
            type: "POST",
            data: $("#fsmspadrao").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Sms Padrão cadastrado", data.mensagem, "success");
                    $("#texto").val("");
                    procurarSmsPadrao(true);
                } else if (data.situacao === false) {
                    swal("Erro ao cadastrar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });
    }
}

function excluirSmsPadrao2(codsmsPadrao) {
    if (window.confirm("Deseja realmente excluir esse sms padrão?")) {
        if (codsmsPadrao !== null && codsmsPadrao !== "") {
            $.ajax({
                url: "../control/ExcluirSmsPadrao.php",
                type: "POST",
                data: {codsmspadrao: codsmsPadrao},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Sms Padrão excluido", data.mensagem, "success");
                        procurarSmsPadrao(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao cadastrar", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o sms padrão para excluir!", "error");
        }
    }
}

function procurarSmsPadrao(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../../control/ProcurarSmsPadrao2.php",
        type: "POST",
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagemSmsPadrao").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}
/*
 * INSERIR LINK
 */
function inserirLink() {
    if ($('#nome').val() == "") {
        swal("Erro ao cadastrar", "Nome não pode ser vazio!");
    }
    else {
        $.ajax({
            url: "../../control/InserirLink.php",
            type: "POST",
            data: $("#flink").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Link cadastrado", data.mensagem, "success");
                    procurarLink(true);
                } else if (data.situacao === false) {
                    swal("Erro ao cadastrar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });
    }
}

function excluir2Link(codlink) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa link!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codlink !== null && codlink !== "") {
                $.ajax({
                    url: "../control/ExcluirLink.php",
                    type: "POST",
                    data: {codlink: codlink},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Link excluido", data.mensagem, "success");
                            procurarLink(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha a link para excluir!", "error");
            }
        }
    });
}

function procurarLink(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../../control/ProcurarLink.php",
        type: "POST",
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de links!", "error");
            }
            document.getElementById("listagemLink").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function salvarConfiguracao() {
    var urlConfiguracao = "../../control/InserirConfiguracao.php";
    if ($("#codconfiguracao").val() != "") {
        urlConfiguracao = "../../control/AtualizarConfiguracao.php";
    }
    if ($("#consultade").val() == "0" || $("#consultade").val() == "1") {
        if ($("#consultade").val() == "0" && $("#loginViper").val() == "") {
            swal("Atenção", "Escolhendo consulta de viper deve preencher o login do mesmo!!!", "error");
        } else if ($("#consultade").val() == "1" && ($("#usuarioMultiBR").val() == "" || $("#senhaMultiBR").val() == "")) {
            swal("Atenção", "Escolhendo consulta de multibr deve preencher o login do mesmo!!!", "error");
        } else if(($("#consultade").val() == "0" && $("#loginViper").val() != "")  || ($("#consultade").val() == "1" && $("#usuarioMultiBR").val() != "" && $("#senhaMultiBR").val() != "")){
            $.ajax({
                url: urlConfiguracao,
                type: "POST",
                data: $("#fconfiguracao").serialize(),
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Configuracao cadastrada", data.mensagem, "success");
                        $("#codconfiguracao").val(data.codconfiguracao);
                    } else if (data.situacao === false) {
                        swal("Erro ao cadastrar", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
                }
            });
        }
    } else {
        swal("Atenção", "Deve escolher algum tipo de consulta!!!", "error");
    }
}

function procurarConfiguracao(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarConfiguracao2.php",
        type: "POST",
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



function excluir2Manual(codmanual) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa manual!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codmanual !== null && codmanual !== "") {
                $.ajax({
                    url: "../control/ExcluirManual.php",
                    type: "POST",
                    data: {codmanual: codmanual},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Manual excluida", data.mensagem, "success");
                            procurarManual(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha a manual para excluir!", "error");
            }
        }
    });
}

function procurarManual(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../../control/ProcurarManual2.php",
        type: "POST",
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de manuals!", "error");
            }
            document.getElementById("listagemManual").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function importarClientes() {
    document.getElementById("fimportacaoClienteInstalador").submit();
}

$(document).ready(function () {
    $("#fimportacaoClienteInstalador").submit(function () {
        $(".progress").css("visibility", "visible");
    });

    $('#fimportacaoClienteInstalador').ajaxForm({
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
                swal("Importação", data.mensagem, "success");
            } else if (data.situacao === false) {
                swal("Importação - Erro", data.mensagem, "error");
            }
        }
    });

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $("#fimportacaoClienteInstalador").submit(function () {
        $(".progress").css("visibility", "visible");
    });

    $('#fimportacaoClienteInstalador').ajaxForm({
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
                swal("Importação", data.mensagem, "success");
            } else if (data.situacao === false) {
                swal("Importação - Erro", data.mensagem, "error");
            }
        }
    });

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $("#fManual").submit(function () {
        $(".progress").css("visibility", "visible");
    });

    $('#fManual').ajaxForm({
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
                swal("Importação", data.mensagem, "success");
            } else if (data.situacao === false) {
                swal("Importação - Erro", data.mensagem, "error");
            }
        }
    });

    $("#codbanco1").blur(function () {
        $.ajax({
            url: "../../control/ProcurarCodigoBanco.php",
            type: "POST",
            data: {numbanco: $("#codbanco1").val()},
            dataType: 'text',
            success: function (data, textStatus, jqXHR) {
                if (data == "") {
                    swal("Erro", "Nada encontrado", "error");
                } else {
                    $("#codbanco").val(data);
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao procurar combo - pessoa", "Erro causado por:" + errorThrown, "error");
            }
        });
    });
});


