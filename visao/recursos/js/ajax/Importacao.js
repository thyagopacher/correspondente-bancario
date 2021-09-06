function excluirImportacao(){
    $.ajax({
        url: "../control/ExcluirImportacao.php",
        type: "POST",
        data: $("#fpimportacao").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if(data.situacao === true){
                swal("Carteira excluida", data.mensagem, "success");
                procurarImportacao(true);
            }else if(data.situacao === false){
                swal("Erro ao excluir", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });    
}

function excluirImportacao2(codcarteira){
    $.ajax({
        url: "../control/ExcluirImportacao.php",
        type: "POST",
        data: {codcarteira: codcarteira},
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if(data.situacao === true){
                swal("Carteira excluida", data.mensagem, "success");
                procurarImportacao(true);
            }else if(data.situacao === false){
                swal("Erro ao excluir", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });    
}

/**trazendo listagem de importacaos cadastrados*/
function procurarImportacao() {
    $.ajax({
        url: "../control/ProcurarImportacao.php",
        type: "POST",
        data: $("#fpimportacao").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(data === ""){
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagem").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function mostraRelatorio(codcarteira, tipo){
    if(tipo == 1){
        tipo = "xls";
    }else{
        tipo = "pdf";
    }
    window.open("../control/ProcurarPessoaRelatorio.php?tipo=" + tipo + "&codcarteira=" + codcarteira)
}

function abreRelatorio(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpimportacao").submit();
}

function abreRelatorio2(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpimportacao").submit();
}

/**daqui para baixa responsável pelo ajax de inserir ou atualizar importacao e também pelo upload sem redirecionar página*/
(function () {

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $("#fimportacao").submit(function(){
        $(".progress").css("visibility", "visible");
    });

    $('#fimportacao').ajaxForm({
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
            console.log("Upload feito!!!");
        },
        complete: function (xhr) {
            
            var percentVal = '100%';
            bar.width(percentVal);
            percent.html(percentVal);
            
            var data = JSON.parse(xhr.responseText);
            if(data.situacao === true){
                swal("Importação", data.mensagem, "success");
            }else if(data.situacao === false){
                swal("Importação - Erro", data.mensagem, "error");
            }    
        }
    });

})();