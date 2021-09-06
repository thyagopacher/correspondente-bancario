function inserirTabela() {
    $.ajax({
        url: "../control/InserirTabela.php",
        type: "POST",
        data: $("#ftabela").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Tabela cadastrada", data.mensagem, "success");
                procurarTabela(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarTabela() {
    $.ajax({
        url: "../control/AtualizarTabela.php",
        type: "POST",
        data: $("#ftabela").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Tabela atualizada", data.mensagem, "success");
                procurarTabela(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirTabela() {
    if (window.confirm("Deseja realmente excluir esse tabela?")) {
        if (document.getElementById("codtabela").value !== null && document.getElementById("codtabela").value !== "") {
            $.ajax({
                url: "../control/ExcluirTabela.php",
                type: "POST",
                data: {codtabela: document.getElementById("codtabela").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tabela excluida", data.mensagem, "success");
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

function excluir2Tabela(codtabela) {
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
            if (codtabela !== null && codtabela !== "") {
                $.ajax({
                    url: "../control/ExcluirTabela.php",
                    type: "POST",
                    data: {codtabela: codtabela},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Tabela excluida", data.mensagem, "success");
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

function setaEditar(tabela) {
    document.getElementById("codtabela").value = tabela[0];
    document.getElementById("nome").value = tabela[1];
    document.getElementById("telefone").value = tabela[2];
    document.getElementById("email").value = tabela[3];
    document.getElementById("senha").value = tabela[4];
    document.getElementById("celular").value = tabela[5];
    document.getElementById("imagemCarregada").innerHTML = "<img src='../arquivos/" + tabela[6] + "' alt='Imagem da tabela' title='Imagem da tabela'/>";
    $("#btatualizarTabela").css("display", "");
    $("#btexcluirTabela").css("display", "");
    $("#btnovoTabela").css("display", "");
    $("#btinserirTabela").css("display", "none");
    $("#codnivel option[value='" + tabela[7] + "']").attr("selected", true);

}

function linhasAntigasTabelaPrazo() {
    var prazode = document.getElementsByName('prazode[]');
    var i = 1;
    var aux = prazode.length;
    var conc = "";
    for (i = 0; i < aux; i++) {
        var linhaIteracao = i;
        if (prazode[i].value !== null && prazode[i].value !== "") {
            var linhaNova = "<div style='margin-top: 10px;' id='tabela_prazo_comissao" + linhaIteracao + "'>";
            linhaNova += '<table>';
            linhaNova += '<tr>';
            linhaNova += '<td style="width: 100px;">DT. INICIO <input style="width: 80px;" type="date" name="dtinicio[]" id="dtinicio' + linhaIteracao + '" value="' + document.getElementById("dtinicio" + linhaIteracao).value + '"/></td>';
            linhaNova += '<td style="width: 100px;">DT. FIM <input style="width: 80px;" type="date" name="dtfim[]" id="dtfim' + linhaIteracao + '" value="' + document.getElementById("dtfim" + linhaIteracao).value + '"/></td>';
            linhaNova += '<td style="width: 100px;">DE <input style="width: 80px;" type="number" min="0" max="999" name="prazode[]" id="prazode' + linhaIteracao + '" value="' + document.getElementById("prazode" + linhaIteracao).value + '"></td>';
            linhaNova += '<td style="width: 100px;">ATÉ <input style="width: 80px;" type="number" min="0" max="999" name="prazoate[]" id="prazoate' + linhaIteracao + '" value="' + document.getElementById("prazoate" + linhaIteracao).value + '"></td>';
            linhaNova += '<td style="width: 100px;">COMISSÃO <input style="width: 80px;" type="text" class="real" name="comissao[]" id="comissao' + linhaIteracao + '" value="' + document.getElementById("comissao" + linhaIteracao).value + '"></td>';
            linhaNova += '<td style="width: 25px;"><a href="javascript: adicionarLinhaTabelaPrazo(' + linhaIteracao + ');" id="botao_adicionar_tabela_prazo' + linhaIteracao + '" class="botao" title="Adicionar nova linha">+</a></td>';
            linhaNova += '<td style="width: 25px;"><a href="javascript: removerLinhaTabelaPrazo(' + linhaIteracao + ')" class="botao" title="Retirar linha">-</a></td>';
            linhaNova += '</tr>';
            linhaNova += '<tr>';
            linhaNova += '<td style="width: 100px;">PARCEIRO<input style="width: 80px" type="text" name="parceiro[]" id="parceiro' + linhaIteracao + '" class="real" value="' + document.getElementById("parceiro" + linhaIteracao).value + '"/></td>';
            linhaNova += '<td style="width: 100px;">BONUS<input style="width: 80px" type="text" name="bonus[]" id="bonus' + linhaIteracao + '" class="real" value="' + document.getElementById("bonus" + linhaIteracao).value + '"/></td>';
            linhaNova += '<td style="width: 100px;">SUPERVISOR<input style="width: 80px" type="text" name="supervisor[]" id="supervisor' + linhaIteracao + '" class="real" value="' + document.getElementById("supervisor" + linhaIteracao).value + '"/></td>';
            linhaNova += '<td style="width: 100px;">VENDEDOR<input style="width: 80px" type="text" name="vendedor[]" id="vendedor' + linhaIteracao + '" class="real" value="' + document.getElementById("vendedor" + linhaIteracao).value + '"/></td>';
            linhaNova += '<td style="width: 100px;">PESO<input style="width: 80px" type="text" name="peso[]" id="peso' + linhaIteracao + '" class="real" value="' + document.getElementById("peso" + linhaIteracao).value + '"/></td>';
            linhaNova += '</tr>';
            linhaNova += '</table>';
            linhaNova += '</div>';
            conc += linhaNova;
        }
    }
    return conc;
}

function btNovoTabela() {
    location.href = "Tabela.php";
}

function procurarTabela(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarTabela2.php",
        type: "POST",
        data: $("#fptabela").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado!", "error");
            }
            if (data != "") {
                $("#formAcaoMassa").css("display", "");
            } else {
                $("#formAcaoMassa").css("display", "none");
            }
            document.getElementById("listagemTabela").innerHTML = data;
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

function replaceAll(str, find, replace) {
    return str.replace(new RegExp(find, 'g'), replace);
}

function adicionarLinhaTabelaPrazo(linha2) {
    var linhaNova = linha2 + 1;
    var linhaExemplo = replaceAll($("#linha_exemplo").html(), 'INDICE_LINHA_TABELA', linhaNova);
    $("#linhas_tabela_prazo").append('<div class="row" id="tabela_linha' + linhaNova + '">' + linhaExemplo + '</div>');
}

function removerLinhaTabelaPrazo(linha2) {
    $("#tabela_linha" + linha2).remove();
}

function maskIt(w, e, m, r, a) {
// Cancela se o evento for Backspace
    if (!e)
        var e = window.event
    if (e.keyCode)
        code = e.keyCode;
    else if (e.which)
        code = e.which;
// Variáveis da função
    var txt = (!r) ? w.value.replace(/[^\d]+/gi, '') : w.value.replace(/[^\d]+/gi, '').reverse();
    var mask = (!r) ? m : m.reverse();
    var pre = (a) ? a.pre : "";
    var pos = (a) ? a.pos : "";
    var ret = "";
    if (code == 9 || code == 8 || txt.length == mask.replace(/[^#]+/g, '').length)
        return false;
// Loop na máscara para aplicar os caracteres
    for (var x = 0, y = 0, z = mask.length; x < z && y < txt.length; ) {
        if (mask.charAt(x) != '#') {
            ret += mask.charAt(x);
            x++;
        } else {
            ret += txt.charAt(y);
            y++;
            x++;
        }
    }
// Retorno da função
    ret = (!r) ? ret : ret.reverse()
    w.value = pre + ret + pos;
}
// Novo método para o objeto 'String'
String.prototype.reverse = function () {
    return this.split('').reverse().join('');
};

function number_format(number, decimals, dec_point, thousands_sep) {
    var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
    var d = dec_point == undefined ? "," : dec_point;
    var t = thousands_sep == undefined ? "." : thousands_sep, s = n < 0 ? "-" : "";
    var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}

/**
 * calcula niveis de comissão ao evento change da comissão
 * @param {input} valor vem o componente de comissão
 * */
function calculaNiveisComissao(valor) {
    var ID = valor.id.replace('comissao', '');
    var comissao = parseFloat(valor.value.toString().replace(',', '.'));
    var niveis = document.getElementById("niveis").value.toString().split(',');
    var tamNiveis = niveis.length;
    if (comissao > 0) {
        for (var i = 0; i < tamNiveis; i++) {
            var comissao_nivel = parseFloat($("#pctnivel_" + ID + '_' + niveis[i]).attr('comissao_nivel'));
            var rescomissao = comissao * (comissao_nivel / 100);
            $("#pctnivel_" + ID + '_' + niveis[i]).val(rescomissao.toFixed(2).toString().replace('.', ','));
        }
    }
}

$(function () {

    if (document.getElementById("listagemTabela") != null && (document.getElementById("qtdtabelaprazo") == null || document.getElementById("qtdtabelaprazo").value == "0")) {
        adicionarLinhaTabelaPrazo(-1);
    }
    $("#btDeixarVisivelTabela").click(function () {
        if ($("#codnivelTabela").val() == "") {
            swal("Atenção", "Sem nivel você não pode salvar", "error");
        } else if (parseInt($("#codnivelTabela").val()) > 0) {
            $.ajax({
                url: "../control/InserirTabelaNivel.php",
                type: "POST",
                data: $("#ftabelaNivel").serialize(),
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tabela salva", data.mensagem, "success");
                        procurarTabela(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao salva", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao salva", "Erro causado por:" + errorThrown, "error");
                }
            });
        }
    });

    $("#perfilTabela").change(function () {
        document.getElementById("codnivelTabela").value = $("#perfilTabela option:selected").val();
    });

    $("#tabela_selecao_tudo").click(function () {
        if ($("#tabela_selecao_tudo").is(":checked")) {
            $(".tabela_selecao").prop("checked", true);
        } else {
            $(".tabela_selecao").prop("checked", false);
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
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao procurar combo - tabela", "Erro causado por:" + errorThrown, "error");
            }
        });
    });

    $("#numbanco").blur(function () {
        $("#codbancoProcurar").val($("#numbanco").val());
    });

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#ftabela').ajaxForm({
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
                procurarTabela(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});
