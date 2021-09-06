function ConsultaCpfInss() {
    $("#carregando").show();
    $.ajax({
        url: "../control/ConsultaCpfInss.php",
        type: "POST",
        data: {cpf: $("#cpf").val()},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            document.getElementById("listagemBeneficio").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function BeneficiosCliente(codcliente) {
    $("#carregando").show();
    $.ajax({
        url: "../control/BeneficiosCliente.php",
        type: "POST",
        data: {codpessoa: codcliente},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            document.getElementById("beneficio_aposentado").innerHTML = data;
//            setTimeout('location.reload();', 500);
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function ConsignacoesCliente(codcliente) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ConsignacoesClienteLista.php",
        type: "POST",
        data: {codpessoa: codcliente},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            document.getElementById("historico_consignacoes").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function ConsultaCpfInss2() {
    var data = new Date();
    var dia = data.getDate();
    var mes = data.getMonth();
    var ano = data.getFullYear();

    var meses = new Array(12);

    meses[0] = "Janeiro";
    meses[1] = "Fevereiro";
    meses[2] = "Março";
    meses[3] = "Abril";
    meses[4] = "Maio";
    meses[5] = "Junho";
    meses[6] = "Julho";
    meses[7] = "Agosto";
    meses[8] = "Setembro";
    meses[9] = "Outubro";
    meses[10] = "Novembro";
    meses[11] = "Dezembro";
    $("#carregando").show();
    $.ajax({
        url: "../control/ConsultaCpfInss2.php",
        type: "POST",
        data: {cpf: $("#cpf").val()},
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                consultaBeneficioInssFinal($("#codpessoa").val());
                document.getElementById("data_atualizacao").innerHTML = +dia + " de " + meses[mes] + " de " + ano;
            } else {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function consultaCPFMultiBR() {

    $.ajax({
        url: "../control/ImportarAtualizar.php",
        type: "POST",
        data: {codpessoa: $("#codpessoa").val()},
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao != true) {
                swal("Erro ao atualizar", data.mensagem, "error");
            } else {
                BeneficiosCliente($("#codpessoa").val());
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function consultaBeneficioInssFinal(codpessoa) {
    if (codpessoa != null && codpessoa != "") {
        $.ajax({
            url: "../control/ConsultaBeneficioInss2.php",
            type: "POST",
            data: {codpessoa: codpessoa},
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao != true) {
                    swal("Erro ao atualizar", data.mensagem, "error");
                } else {
                    BeneficiosCliente($("#codpessoa").val());
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
            }
        });
    }
}

function consultaBeneficioInss(numero) {
    var numero2 = "";
    if (numero.length > 1) {
        var qtdNumero = numero.length;
        for (var i = 0; i < qtdNumero; i++) {
            numero2 += numero[i] + ";";
        }
    } else {
        numero2 = numero;
    }

    window.open('../control/ConsultaBeneficioInss.php?numero=' + numero2, "Consulta Beneficio INSS", "width=1196, height=500");
}

function consultaBeneficioInss2(numero) {
    if (numero != null && numero != "") {
        $.ajax({
            url: "../control/ConsultaBeneficioInss2.php",
            type: "POST",
            data: {numero: numero},
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    ConsignacoesCliente($("#codpessoa").val());
                } else {
                    swal("Erro ao atualizar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
            }
        });
    }
}

function consultaBeneficioInss3(codpessoa) {
    if (codpessoa != null && codpessoa != "") {
        $.ajax({
            url: "../control/ConsultaBeneficioInss2.php",
            type: "POST",
            data: {codpessoa: codpessoa},
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    ConsignacoesCliente($("#codpessoa").val());
                } else {
                    swal("Erro ao atualizar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
            }
        });
    }
}

function consultaCpfBeneficio() {
    if ($("#cpf").val() != "") {
        TINY.box.show({url: '../control/ConsultaCpfInss.php', post: 'cpf=' + $("#cpf").val(), width: 430, height: 300, opacity: 20, topsplit: 3});
    } else if ($("#beneficio").val() != "") {
        window.open('../control/ConsultaBeneficioInss.php?numero=' + $("#beneficio").val(), 'Cons. Beneficio');
    } else {
        swal("Atenção", "Deve preencher algum campo, cpf ou num de beneficio!", "error");
    }
}

function consultaCpf() {
    TINY.box.show({url: '../control/ConsultaCpfInss.php', post: 'cpf=' + $("#cpf").val(), width: 430, height: 300, opacity: 20, topsplit: 3});
}