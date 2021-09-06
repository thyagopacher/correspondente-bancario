function AcompanharCarteira(){
    $("#carregando").show();
    $.ajax({
        url : "../control/AcompanharCarteira.php",
        type: "POST",
        data : $("#fprocurarAcompanhamentoCarteira").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            document.getElementById("resultado_acompanhamento_carteira").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar acesso", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}


AcompanharCarteira();

$(function() {
    $("#filial").change(function(){
        AcompanharCarteira();
    })
});