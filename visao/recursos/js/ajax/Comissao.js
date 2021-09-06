function salvarComissao(codproposta){
    var valor = $("#valor_contrato" + codproposta).val();
    var valor2 = $("#valor_contrato_comissao" + codproposta).val();
    var valor3 = $("#valor_contrato_comissao_empresa" + codproposta).val();
    $("#carregando").show();
    $("#resultado_salvo").html("salvando");
    
    $.ajax({
        url : "../control/SalvarComissaoNova.php",
        type: "POST",
        data : {codproposta: codproposta, valor_contrato: valor, valor_contrato_comissao: valor2, valor_contrato_comissao_empresa: valor3},
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if (data.situacao === true) {
                $("#resultado_salvo").html("Salvo com sucesso");
                procurarComissao(true);
            } else if (data.situacao === false) {
                $("#resultado_salvo").html("Erro ao salvar");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao salvar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function procurarComissao(acao, situacaoComissao){
    var urlProcura = '';
    if(situacaoComissao == "pagar"){
        urlProcura = '../control/ProcurarComissao2.php';
    }else{
        urlProcura = '../control/ProcurarComissao.php';
    }
    $("#carregando").show();
    $.ajax({
        url : urlProcura,
        type: "POST",
        data : $("#fpcomissao").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado de comissão!", "error");
            }
            if(situacaoComissao == "pagar"){
                document.getElementById("listagemComissao2").innerHTML = data;
            }else{
                document.getElementById("listagemComissao").innerHTML = data;
            }            
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

