function AndamentoEsteira(){
    $("#carregando").show();
    $.ajax({
        url : "../control/AndamentoEsteira.php",
        type: "POST",
        data : $("#fprocurarAndamentoEsteira").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(data == ""){
                swal("Erro ao procurar", "Nada encontrado andamento da esteira!!!", "error");
            }
            document.getElementById("resultado_andamento_esteira").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar acesso", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}


AndamentoEsteira();

$(function() {
    $("#codfuncionario").change(function(){
        AndamentoEsteira();
    })
});