$(function() {
    $("#tabela").change(function(){
        $.ajax({
            url : "../control/CamposTabela.php",
            type: "POST",
            data : {tabela: $("#tabela option:selected").val()},
            dataType: 'text',
            success: function(data, textStatus, jqXHR){
                document.getElementById("listagemCamposTabela").innerHTML = data;
            },error: function (jqXHR, textStatus, errorThrown){
                swal("Erro ao procurar academia", "Erro causado por:" + errorThrown, "error");
            }
        });          
    });
    $("#btGerarRelatorio").click(function(){
        document.getElementById("formExtrator").submit();
    })
});