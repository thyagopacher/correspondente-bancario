
function procurarFluxo(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarFluxoCaixa.php",
        type: "POST",
        data : $("#ffluxo").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado!", "error");              
            }
            document.getElementById("listagem").innerHTML = data;
        }
    }); 
    $("#carregando").hide();
}

function abreRelatorioFluxo(){
    document.getElementById("ffluxo").submit();
}

function abreRelatorio2Fluxo(){
    document.getElementById("ffluxo2").submit();
}

