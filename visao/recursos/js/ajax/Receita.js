function procurarReceita(){
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarReceita.php",
        type: "POST",
        data: $("#freceita").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(data == ""){
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagem").innerHTML = data;             
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", data.mensagem, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorio(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("freceita").submit();
}

function abreRelatorio2(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("freceita").submit();
}
