/**trazendo listagem de rankings cadastrados*/
function procurarRanking(acao){
    $.ajax({
        url : "../control/ProcurarRanking.php",
        type: "POST",
        data : $("#fpranking").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagemRanking").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}


function abreRelatorioRanking(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpranking").submit();
}

function abreRelatorio2Ranking(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpranking").submit();
}

$( document ).ready(function() {
   $("#mes").change(function(){
        procurarRanking(false);
   });
   
   procurarRanking();
});