
/**trazendo listagem de newsletters cadastrados*/
function procurarNewsletter() {
    $.ajax({
        url: "../control/ProcurarNewsletter.php",
        type: "POST",
        data: $("#fpnewsletter").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(data === ""){
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagemNewsletter").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}


function abreRelatorio(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpnewsletter").submit();
}

function abreRelatorio2(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpnewsletter").submit();
}

/**daqui para baixa responsável pelo ajax de inserir ou atualizar newsletter e também pelo upload sem redirecionar página*/
(function () {

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $("#fnewsletter").submit(function(){
        $(".progress").css("visibility", "visible");
    });

    $('#fnewsletter').ajaxForm({
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
            if(data.situacao === true){
                swal("Importação", data.mensagem, "success");
            }else if(data.situacao === false){
                swal("Importação - Erro", data.mensagem, "error");
            }    
        }
    });

})();