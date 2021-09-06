

function procurarLigacao(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarLigacao2.php",
        type: "POST",
        data : $("#fpligacao").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado de ligações!", "error");
            }
            document.getElementById("listagemLigacao").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar ligacao", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}


function abreRelatorio(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpligacao").submit();
}

function abreRelatorio2(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpligacao").submit();
}
