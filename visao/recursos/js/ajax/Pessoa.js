var tempoOcioso = 5 * 60;
if (document.getElementById("tempoocioso") != null) {
    tempoOcioso = parseInt(document.getElementById("tempoocioso").value) * 60;
}
tempoOcioso = 500000000;
var c = 0;
var t;
var timer_is_on = 0;

function timedCount() {
    c = c + 1;
    if (c >= tempoOcioso) {
        c = 0;
        var tituloMsg = "Campanha Pausada por tempo ocioso do Sistema";
        var textoMsg = "Para voltar a campanha selecione uma das opções abaixo";
        TINY.box.show({url: 'TempoOcioso.php', post: 'titulo=' + tituloMsg + '&texto=' + textoMsg, width: 430, height: 300, opacity: 20, topsplit: 3});
    } else {
        t = setTimeout(function () {
            timedCount();
        }, 1000);
    }
}

function startCount() {
    if (!timer_is_on) {
        timer_is_on = 1;
        timedCount();
    }
}

function stopCount() {
    clearTimeout(t);
    timer_is_on = 0;
}

if (document.getElementById("tempoocioso") != null) {
    startCount();
}

function reenviarLogin(codpessoa) {
    $.ajax({
        url: "../control/ReenviarLogin.php",
        type: "POST",
        data: {codpessoa: codpessoa},
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Login enviado", data.mensagem, "success");
                procurarPessoa2(true);
            } else if (data.situacao === false) {
                swal("Erro ao enviar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao enviar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirPessoa(codpessoa) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa pessoa!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codpessoa !== null && codpessoa !== "") {
                $.ajax({
                    url: "../control/ExcluirPessoa.php",
                    type: "POST",
                    data: {codpessoa: codpessoa},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Pessoa excluida", data.mensagem, "success");
                            procurarPessoa2(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha a pessoa para excluir!", "error");
            }
        }
    });
}

function excluirFoto(codpessoa) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar a imagem dessa pessoa!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codpessoa !== null && codpessoa !== "") {
                $.ajax({
                    url: "../control/ExcluirImgPessoa.php",
                    type: "POST",
                    data: {codpessoa: codpessoa},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Imagem Pessoa excluida", data.mensagem, "success");

                            $("#imagemCarregada").html("");
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha a imagem pessoa para excluir!", "error");
            }
        }
    });
}


function abreTiraFoto(codpessoa) {
    var oWin = window.open("TirarFoto.php?codpessoa=" + codpessoa, "Tirar Foto", "width=1250, height=550");
    if (oWin === null || typeof (oWin) === "undefined") {
        swal("Erro ao visualizar", "O Bloqueador de Pop-up esta ativado, desbloquei-o por favor!", "error");
    } else {
        window.close();
    }
}

function setaEditar(pessoa) {
    document.getElementById("codpessoa").value = pessoa[0];
    document.getElementById("nome").value = pessoa[1];
    document.getElementById("telefone").value = pessoa[2];
    document.getElementById("email").value = pessoa[3];
    document.getElementById("senha").value = pessoa[4];
    document.getElementById("celular").value = pessoa[5];
    document.getElementById("imagemCarregada").innerHTML = "<img src='../arquivos/" + pessoa[6] + "' alt='Imagem da pessoa' title='Imagem da pessoa'/>";
    $("#btatualizarPessoa").css("display", "");
    $("#btexcluirPessoa").css("display", "");
    $("#btnovoPessoa").css("display", "");
    $("#btinserirPessoa").css("display", "none");
    $("#codnivel option[value='" + pessoa[7] + "']").attr("selected", true);
}

function btNovoPessoa() {
    if ($("#codcategoria").val() == "6") {
        location.href = "Cliente.php?callcenter=true&novo=s";
    } else if ($("#codcategoria").val() == "1") {
        location.href = "Cliente.php?novo=s";
    } else if ($("#codcategoria").val() != "1" && $("#codcategoria").val() != "6") {
        location.href = "Pessoa.php";
    }
}

function proximoFila() {
    if ($("#codcategoria").val() == "6") {
        location.href = "Cliente.php?callcenter=true&proximo=s";
    } else if ($("#codcategoria").val() == "1") {
        location.href = "Cliente.php?proximo=s&tipo=1";
    } else if ($("#codcategoria").val() != "1" && $("#codcategoria").val() != "6") {
        location.href = "Pessoa.php?proximo=s";
    }
}

function proximoCliente(codpessoa) {
    location.href = "Cliente.php?codpessoa=" + codpessoa + "&proximo=s";
}

function procurarPessoa2(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarPessoa2.php",
        type: "POST",
        data: $("#fPpessoa").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado de pessoas!", "error");
            }
            document.getElementById("listagem").innerHTML = data;
            dataTablePadrao('tabela_procurar_pessoa');
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioPessoa() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fPpessoa").submit();
}

function abreRelatorioPessoa2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fPpessoa").submit();
}

function inserirTelefone(linha) {
    var proxTelefone = parseInt(linha) + 1;
    var inputTelefone = '<input style="width: 80%" required type="text" name="telefone[]" id="telefone' + proxTelefone + '" class="telefone form-control" value="" title="Digite aqui telefone" placeholder="(00)0000-0000">';
    var btAdicionar = '<a style="float: right;margin-top: -35px;margin-right: 110px;" class="btn btn-primary" href="#"  onclick="inserirTelefone(' + proxTelefone + ');">+</a>';
    var btRemover = '<a style="float: right;margin-top: -35px;margin-right: 65px;" class="btn btn-primary" href="#"  onclick="removeTelefone(' + proxTelefone + ');">-</a>';
    var optionTel = document.getElementById("tipotelefone" + linha).innerHTML;
    var novoTelefone = '<div class="col-md-12" id="linhaTelefone' + proxTelefone + '">'
            + '<div class="col-md-6" style="padding-left: 0px">'
            + '<div class="form-group">'
            + '<label for="exampleInputFile">Tipo Telefone</label>'
            + '<select name="tipotelefone[]" id="tipotelefone' + proxTelefone + '" class="form-control">'
            + optionTel
            + '</select>'
            + '</div>'
            + '</div>'
            + '<div class="col-md-6">'
            + '<div class="form-group">'
            + '<label for="exampleInputFile">Telefone</label>'
            + inputTelefone
            + btAdicionar + ' ' + btRemover
            + '</div>';
    +'</div>';

    var telefone = document.getElementsByName('telefone[]');
    var i = 1;
    var aux = telefone.length;
    var telefone_antigo = new Array();
    var tipo_telefone_antigo = new Array();
    for (i = 0; i < aux; i++) {
        var linhaInteracao = i + 1;
        telefone_antigo[i] = $("#telefone" + linhaInteracao).val();
        tipo_telefone_antigo[i] = $("#tipotelefone" + linhaInteracao).val();
    }
    document.getElementById("telefones").innerHTML = document.getElementById("telefones").innerHTML + novoTelefone;
    for (i = 0; i < aux; i++) {
        var linhaInteracao = i + 1;
        $("#tipotelefone" + linhaInteracao).val(tipo_telefone_antigo[i]);
        $("#telefone" + linhaInteracao).val(telefone_antigo[i]);
    }
}

function removeTelefone(linha) {
    $("#tipotelefone" + linha).val("");
    $("#telefone" + linha).val("");
    $("#linhaTelefone" + linha).css("display", "none");
}

function inserirBeneficio(linha2) {
    var linha = linha2 + 1;
    var beneficio_aposentado = $("#beneficio_aposentado").html();
    var orgao_pagador = '<div id="orgao_pagador' + linha + '" style="width: 195px; float: left;" title="Órgão pagador de aposentadoria" onclick="orgaoPagadorChange(' + linha + ')">' + "ÓRGÃO PAGADOR <select name='orgaopagador[]' id='orgaopagador" + linha + "'>" + $("#optionorgaopagador").val() + "</select>" + "</div>";
    var beneficio_inss = '<div id="beneficio_inss' + linha + '" style="width: 380px;float: left;display:none"> Espécie <select style="width: 100px;" name="especie[]" id="especie' + linha + '"></select> NUM.BENEFICIO <input type="text" name="numbeneficio[]" id="numbeneficio' + linha + '" value=""/></div>';
    var beneficio_outros = '<div id="beneficio_outro' + linha + '" style="width: 190px;float: left; display: none"> MATRICULA <input type="text" name="matricula[]" id="matricula' + linha + '"/></div>';
    var salario_base = '<div id="salario_base' + linha + '" style="display: none"> SALÁRIO BASE <input type="text" name="salariobase[]" id="salariobase' + linha + '" class="real" value=""/> <button onclick="inserirBeneficio(' + linha + ');" title="Adiciona novo beneficio">+</button><button onclick="removeBeneficio(' + linha + ');" title="Remove beneficio da linha">-</button></div>';
    document.getElementById("beneficio_aposentado").innerHTML = beneficio_aposentado + '<div id="beneficio_aposentado' + linha + '">' + orgao_pagador + beneficio_inss + beneficio_outros + salario_base + '</div>';
}

function removeBeneficio(linha) {
    $("#beneficio_aposentado" + linha).css("display", "none");
}

function inserirEmpregoServidor(linha2) {
    var linha = linha2 + 1;
    var emprego_servidor = $("#nao_aposentado").html();
}

function removerEmpregoServidor(linha) {
    $("#nao_aposentado" + linha).css("display", "none");
}

function orgaoPagadorChange(linha) {
    if ($("#orgaopagador" + linha + " option:selected").text() == "INSS") {
        $("#beneficio_inss" + linha).css("display", "");
        $("#beneficio_outro" + linha).css("display", "none");
        $("#salario_base" + linha).css("display", "");
    } else if ($("#orgaopagador" + linha + " option:selected").text() !== "INSS" && $("#orgaopagador" + linha + " option:selected").text() == "Outros") {
        $("#beneficio_inss" + linha).css("display", "none");
        $("#beneficio_outro" + linha).css("display", "");
        $("#salario_base" + linha).css("display", "");
    } else if ($("#orgaopagador" + linha + " option:selected").val() == "") {
        $("#beneficio_inss" + linha).css("display", "none");
        $("#beneficio_outro" + linha).css("display", "none");
        $("#salario_base" + linha).css("display", "none");
    }
}

function abreHistoricoConsignacoes() {
    if ($("#historico_consignacoes").css("display") == "") {
        $("#historico_consignacoes").css("display", "none");
    } else {
        $("#historico_consignacoes").css("display", "");
    }
}

function abrirObservacaoLigacao() {
    if ($("#div_observacao_ligacao").css("display") == "none") {
        $("#div_observacao_ligacao").css("display", "");
    } else {
        $("#div_observacao_ligacao").css("display", "none");
    }
}

function rodaCoeficienteCalculo() {
    if (document.getElementById("qtd_beneficio") != null) {
        var qtdbeneficio = document.getElementById("qtd_beneficio").value;
        if (document.getElementsByName('coeficiente[]') != null) {
            var coeficiente = document.getElementsByName('coeficiente[]');

            var aux = coeficiente.length;
            var i = 0;
            var j = 0;
            for (j = 0; j < qtdbeneficio; j++) {
                var valorFinal = 0;
                for (i = 0; i < aux; i++) {
                    if ($("#coeficiente_" + j + "_" + i).val() != null && $("#coeficiente_" + j + "_" + i).val() != "") {
                        var vlcoeficiente = parseFloat($("#coeficiente_" + j + "_" + i).val().replace(",", "."));
                        var vlparcela = parseFloat($("#vl_parcela_" + j + "_" + i).html().replace(",", "."));
                        var vlquitacao = parseFloat($("#vl_quitacao_" + j + "_" + i).html().replace(",", "."));
                        var vlfinal = (vlparcela / vlcoeficiente) - vlquitacao;
                        if (vlfinal > 0) {
                            valorFinal += vlfinal;
                        }
                        $("#troco_" + j + "_" + i).val(vlfinal.toFixed(2).replace(".", ","));
                    }
                }
                $("#totalTroco" + j).html("R$ " + valorFinal.toFixed(2).replace(".", ","));
            }
        }
    }
}

function rodaCoeficienteMargem() {
    if (document.getElementsByName('coeficiente_margem[]') != null) {
        var coeficiente = document.getElementsByName('coeficiente_margem[]');
        var aux = coeficiente.length;
        var i = 0;
        var valorFinal = 0;
        for (i = 0; i < aux; i++) {
            if ($("#coeficiente_margem" + i).val() != null && $("#coeficiente_margem" + i).val() != "") {
                var vlcoeficiente = parseFloat($("#coeficiente_margem" + i).val().replace(",", "."));
                var vlmargem = parseFloat($("#margem" + i).html().replace("R$ ", "").replace(",", "."));
                var vlfinal = (vlmargem / vlcoeficiente);
                valorFinal += vlfinal;
                $("#total_margem" + i).html("R$ " + vlfinal.toFixed(2).replace(".", ","));
                var totalTroco = 0;
                if (document.getElementById("totalTroco" + i) != null) {
                    totalTroco = parseFloat($("#totalTroco" + i).html().replace("R$ ", "").replace(".", "").replace(".", ","));
                }
                var total_liberado = valorFinal + totalTroco;
                $("#total_liberado" + i).html("R$ " + total_liberado.toFixed(2).replace(".", ","));
            }
        }
    }
}

function calculaCoeficiente(codigo) {
    if ($("#coeficiente").val() != null && $("#coeficiente").val() != "") {
        var vlmargem = parseFloat($("#vlparcela_" + codigo).html().replace(',', '.'));
        var vlcoeficiente = parseFloat($("#coeficiente").val().replace(',', '.'));
        var vlsaldo = parseFloat($("#saldo_aproximado_" + codigo).html().replace(',', '.'));
        var vlFinal = (vlmargem / vlcoeficiente) - vlsaldo;
        $("#vl_liberado_" + codigo).html(vlFinal.toFixed(2).replace(".", ","));
    } else {
        swal("Atenção", "Deve digitar primeiro coeficiente!!!", "error");
    }
}

function calculaMargem(codigo) {
    if ($("#coeficiente").val() != null && $("#coeficiente").val() != "") {
        var vlparcela = parseFloat($("#margem_disponivel_" + codigo).html().replace(',', '.'));
        var vlcoeficiente = parseFloat($("#coeficiente").val().replace(',', '.'));
        $("#margem_liberado_" + codigo).html((vlparcela / vlcoeficiente).toFixed(2).replace(".", ","));
    } else {
        swal("Atenção", "Deve digitar primeiro coeficiente!!!", "error");
    }
}

function buscarTabelas(codbanco) {
    $.ajax({
        url: "../control/ProcuraTabelaBanco.php",
        type: "POST",
        data: {codbanco: codbanco, codconvenio: $("#codconvenio option:selected").val()},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            document.getElementById("codtabela").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function ligaTelefone(telefone) {
    $("#carregando").show();
    $.ajax({
        url: "../control/LigaTelefone.php",
        type: "POST",
        data: {telefone: telefone},
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao != true) {
                swal("Atenção", data.mensagem, "error");
            } else {
                swal("Atenção", data.mensagem, "success");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao fazer ligacao", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function procurarTabelaMinMax() {
    $.ajax({
        url: "../control/ProcurarTabelaMinMax.php",
        type: "POST",
        data: {codtabela: $("#codtabela option:selected").val(), prazo: $("#prazo").val()},
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if ((data.de != null && data.ate != null) || data.multiplosResultados != "") {
                if (data.multiplosResultados != "") {
                    document.getElementById("prazo").title = data.multiplosResultados;
                } else {
                    document.getElementById("prazo").title = "Prazo de: " + data.de + " - Prazo até: " + data.ate;
                }
                if (data.de != null && data.de != "") {
                    document.getElementById("prazo").min = data.de;
                }
                if (data.ate != null && data.ate != "") {
                    document.getElementById("prazo").max = data.ate;
                }
                if (data.codtabelap != null && data.codtabelap != "") {
                    document.getElementById("codtabelap").value = data.codtabelap;
                }
                if ($("#prazo").val() != null && $("#prazo").val() != "") {
                    if (parseInt($("#prazo").val()) > parseInt(document.getElementById("prazo").max)) {
                        swal("Atenção", "Prazo máximo é de: " + document.getElementById("prazo").max + " por favor o diminua", "error");
                    } else if (parseInt($("#prazo").val()) < parseInt(document.getElementById("prazo").min)) {
                        swal("Atenção", "Prazo minimo é de: " + document.getElementById("prazo").min + " por favor o aumente", "error");
                    }
                }
            } else if (data.de == null && data.ate == null) {
                swal("Atenção", "Prazo inexistente na tabela, por favor conferir!!!", "error");
            } else {
                swal("Atenção", "Tabela sem prazo de vigência", "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function importaTelefones() {
    if ($("#cpf").val() == null || $("#cpf").val() == "") {
        swal("Atenção", "Não pode importar sem CPF!!!", "error");
    } else {
        $.ajax({
            url: "../control/ConsultaInfoPesqCPF.php",
            type: "POST",
            data: {cpf: $("#cpf").val()},
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao == true) {
                    swal("Atenção", data.mensagem, "success");
                    procurarTelefones();
                } else if (data.situacao == false) {
                    swal("Atenção", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro", "Erro causado por:" + errorThrown, "error");
            }
        });
    }
}


function procurarTelefones() {
    if (document.getElementById("telefones") != null) {
        $.ajax({
            url: "../control/TelefonesCliente.php",
            type: "POST",
            data: {codpessoa: $("#codpessoa").val()},
            dataType: 'text',
            success: function (data, textStatus, jqXHR) {
                document.getElementById("telefones").innerHTML = data;
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro", "Erro causado por:" + errorThrown, "error");
            }
        });
    }
}

function verificaAtendimento() {
    $("#opcoes_atendimento").css('display', '');
}

function verificaIntervalo() {
    $("#opcoes_atendimento").css('display', 'none');
    $("#venda_nova").css('display', 'none');
}

$(window).load(function () {
    if (document.getElementById("listagem") != null) {
        $("body").append('<link rel="stylesheet" href="/visao/recursos/css/jquery.dataTables.min.css"/>')
                .append('<script type="text/javascript" src="/visao/recursos/js/jquery.dataTables.min.js"></script>');
    }
});

$(function () {
    $("body").append('<script type="text/javascript" src="/visao/recursos/js/jquery.form.min.js"></script>')
            .append('<script type="text/javascript" src="/visao/recursos/js/ajax/EnvioSMS.js"></script>')
            .append('<script type="text/javascript" src="/visao/recursos/js/ajax/BeneficioCliente.js"></script>')
            .append('<script type="text/javascript" src="/visao/recursos/js/ajax/Proposta.js"></script>')
            .append('<script type="text/javascript" src="/visao/recursos/js/ajax/Agenda.js"></script>')
            .append('<link rel="stylesheet" href="/visao/recursos/css/popup.min.css">')
            .append('<script type="text/javascript" src="/visao/recursos/js/tinybox.js"></script>');

    $("#poupanca").blur(function () {
        if ($("#poupanca").is(":checked")) {
            $("#corrente").prop("checked", false);
        }
    });
    $("#corrente").blur(function () {
        if ($("#corrente").is(":checked")) {
            $("#poupanca").prop("checked", false);
        }
    });
    $("#codbanco1").blur(function () {
        $.ajax({
            url: "../control/ProcurarCodigoBanco.php",
            type: "POST",
            data: {numbanco: $("#codbanco1").val()},
            dataType: 'text',
            success: function (data, textStatus, jqXHR) {
                if (data == "") {
                    swal("Erro", "Nada encontrado", "error");
                } else {
                    $("#codbanco").val(data);
                    buscarTabelas(data);
                    procurarDocumentosBanco(data);
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao procurar combo - pessoa", "Erro causado por:" + errorThrown, "error");
            }
        });
    });
    $("#num_codbanco2").blur(function () {
        $.ajax({
            url: "../control/ProcurarCodigoBanco.php",
            type: "POST",
            data: {numbanco: $("#num_codbanco2").val()},
            dataType: 'text',
            success: function (data, textStatus, jqXHR) {
                if (data == "") {
                    swal("Erro", "Nada encontrado", "error");
                } else {
                    $("#codbanco2").val(data);
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao procurar combo - pessoa", "Erro causado por:" + errorThrown, "error");
            }
        });
    });
    $("#codbanco").change(function () {
        buscarTabelas($("#codbanco option:selected").val());
    });
    $("#codtabela").change(function () {
        procurarTabelaMinMax();
    });
    $("#codconvenio").change(function () {
        $.ajax({
            url: "../control/ProcurarTabelaMinMax.php",
            type: "POST",
            data: {codtabela: $("#codtabela option:selected").val(), codconvenio: $("#codconvenio option:selected").val()},
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if ($("#codtabela option:selected").val() != "" && $("#codconvenio option:selected").val() != "") {
                    document.getElementById("prazo").title = "Prazo de: " + data.de + " - Prazo até: " + data.ate;
                    document.getElementById("prazo").min = data.de;
                    document.getElementById("prazo").max = data.ate;
                    if (data.de == data.ate) {
                        document.getElementById("prazo").value = data.de;
                    }
                } else {
                    document.getElementById("codtabela").value = data.codtabela;
                    document.getElementById("prazo").title = "Prazo de: " + data.de + " - Prazo até: " + data.ate;
                    document.getElementById("prazo").min = data.de;
                    document.getElementById("prazo").max = data.ate;
                    if (data.de == data.ate) {
                        document.getElementById("prazo").value = data.de;
                    }
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
            }
        });
    });
    $("#prazo").blur(function () {
        if (document.getElementById("prazo").value != "") {
            procurarTabelaMinMax();

        } else {
            swal("Atenção", "Prazo não pode ficar em branco", "error");
        }
    });

    $("#cpf").blur(function () {
        if (($("#codpessoa").val() == null || $("#codpessoa").val() == "") && ($("#cpf").val() != null && $("#cpf").val() != "")) {
            $.ajax({
                url: "../control/ProcurarPessoaCPF.php",
                type: "POST",
                data: {cpf: $("#cpf").val()},
                dataType: 'text',
                success: function (data, textStatus, jqXHR) {
                    if (data !== "") {
                        location.href = "Cliente.php?codpessoa=" + data;
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        }
    });
    $("#aposentado1").change(function () {
        if ($("#aposentado1").is(":checked")) {
            $("#orgao_pagador1").css("display", "");
            $("#beneficio_aposentado").css("display", "");
        } else {
            $("#orgao_pagador1").css("display", "none");
            $("#beneficio_aposentado").css("display", "none");
        }
        if ($("#aposentado2").is(":checked") == false) {
            $("#nao_aposentado").css("display", "none");
        } else {
            $("#nao_aposentado").css("display", "");
        }
        $("#beneficio_inss1").css("display", "none");
        $("#beneficio_outro1").css("display", "none");
        $("#salario_base1").css("display", "none");
    });
    $("#aposentado2").change(function () {
        if ($("#aposentado1").is(":checked") == false) {
            if ($("#orgaopagador1").val() != null && $("#orgaopagador1").val() != "") {
                $("#orgao_pagador1").css("display", "");
            } else {
                $("#orgao_pagador1").css("display", "none");
            }
            $("#beneficio_aposentado").css("display", "none");
        } else {
            $("#orgao_pagador1").css("display", "");
            $("#beneficio_aposentado").css("display", "");
        }
        if ($("#aposentado2").is(":checked")) {
            $("#nao_aposentado").css("display", "");
        } else {
            $("#nao_aposentado").css("display", "none");
        }
        if ($("#numbeneficio1").val() != null && $("#numbeneficio1").val() != "") {
            $("#beneficio_inss1").css("display", "");
            $("#beneficio_aposentado").css("display", "");
        } else {
            $("#beneficio_inss1").css("display", "none");
            $("#beneficio_aposentado").css("display", "none");
        }
        $("#beneficio_outro1").css("display", "none");
        if ($("#salariobase1").val() != null && $("#salariobase1").val() != "") {
            $("#salario_base1").css("display", "");
        } else {
            $("#salario_base1").css("display", "none");
        }
    });
    $("#aposentado3").change(function () {
        if ($("#aposentado1").is(":checked") == false) {
            if ($("#orgaopagador1").val() != null && $("#orgaopagador1").val() != "") {
                $("#orgao_pagador1").css("display", "");
                $("#beneficio_aposentado").css("display", "");
            } else {
                $("#orgao_pagador1").css("display", "none");
                $("#beneficio_aposentado").css("display", "none");
            }
        } else {
            $("#orgao_pagador1").css("display", "");
            $("#beneficio_aposentado").css("display", "");
        }
        if ($("#aposentado2").is(":checked")) {
            $("#nao_aposentado").css("display", "");
        } else {
            $("#nao_aposentado").css("display", "none");
        }
        if ($("#aposentado3").is(":checked")) {
            $("#assalariado").css("display", "");
        } else {
            $("#assalariado").css("display", "none");
        }
        if ($("#numbeneficio1").val() != null && $("#numbeneficio1").val() != "") {
            $("#beneficio_inss1").css("display", "");
            $("#beneficio_aposentado").css("display", "");
        } else {
            $("#beneficio_inss1").css("display", "none");
            $("#beneficio_aposentado").css("display", "none");
        }
        $("#beneficio_outro1").css("display", "none");
        if ($("#salariobase1").val() != null && $("#salariobase1").val() != "") {
            $("#salario_base1").css("display", "");
        } else {
            $("#salario_base1").css("display", "none");
        }
    });

    $("#nome_procurar").keyup(function () {
        if ($("#nome_procurar").val().length > 2) {
            procurarPessoa(true);
        }
    });

    $(".parcela_incluir").change(function () {
        var codigo = this.id.toString().replace("parcela_incluir_", '');
        calculaCoeficiente(codigo);
    });

    $(".parcela_incluir_emprestimo").change(function () {
        var codigo = this.id.toString().replace("parcela_incluir_", '');
        calculaMargem(codigo);
    });

    $(".coeficiente").blur(function () {
        rodaCoeficienteCalculo();
    });

    //calculo para a margem junto ao coeficiente
    $(".coeficiente_margem").blur(function () {
        rodaCoeficienteMargem();
    });
    $("#codstatus").change(function () {
        if ($("#codstatus option:selected").val() == "3" || $("#codstatus option:selected").val() == "5" || $("#codstatus option:selected").val() == "7" || $("#codstatus option:selected").val() == "9"
                || $("#codstatus option:selected").val() == "10" || $("#codstatus option:selected").val() == "12" || $("#codstatus option:selected").val() == "14") {
            $("#dtagenda").attr("required", true);
        } else {
            $("#dtagenda").attr("required", false);
        }
    })

    $("#submit").click(function () {
        $("#fpessoa").submit();
    });

    $("#fpessoa").submit(function () {
        $(".progress").css("visibility", "visible");
    });

    $("#acessapainel").change(function () {
        if ($("#acessapainel option:selected").val() === "s" && $("#morador option:selected").val() === "n") {
            $("#codcategoria").val("5");
        }
    });

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fpessoa').ajaxForm({
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
                if ($("#codpessoa").val() !== null && $("#codpessoa").val() !== "") {
                    swal("Alteração", data.mensagem, "success");
                    if (data.imagem !== null && data.imagem !== "") {
                        $("#imagemCarregada").html("<img width='150' src='../arquivos/" + data.imagem + "'  alt='Imagem usuário'/>");
                    }

                } else {
                    swal("Cadastro", data.mensagem, "success");
                }
                procurarPessoa(true);
                if (document.getElementById("btProximoCliente") != null) {
                    $("#btProximoCliente").css("display", "");
                }
//                setTimeout('location.reload();', 1500);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});


rodaCoeficienteCalculo();
rodaCoeficienteMargem();