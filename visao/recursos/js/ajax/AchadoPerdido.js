function inserirAchado(){
    $.ajax({
        url : "../control/InserirAchado.php",
        type: "POST",
        data : $("#fachado").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Achado cadastrado", data.mensagem, "success");
                procurarAchado(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarAchado(){
    $.ajax({
        url : "../control/AtualizarAchado.php",
        type: "POST",
        data : $("#fachado").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Achado atualizado", data.mensagem, "success");
                procurarAchado(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirAchado(){
    if (window.confirm("Deseja realmente excluir esse achado?")) {
        if(document.getElementById("codachado").value !== null && document.getElementById("codachado").value !== ""){
            $.ajax({
                url : "../control/ExcluirAchado.php",
                type: "POST",
                data : {codachado: document.getElementById("codachado").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Achado excluido", data.mensagem, "success");
                        procurarAchado(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha a achados/perdidos para excluir!", "error");
        }
    }
}

function excluir2Achado(codachado){
    if (window.confirm("Deseja realmente excluir esse achado?")) {
        if(codachado !== null && codachado !== ""){
            $.ajax({
                url : "../control/ExcluirAchado.php",
                type: "POST",
                data : {codachado: codachado},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Achado excluido", data.mensagem, "success");
                        procurarAchado(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha a achados/perdidos para excluir!", "error");
        }
    }
}

function procurarAchado(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarAchado.php",
        type: "POST",
        data : $("#fpachado").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado de achados e perdidos!", "error");
            }
            document.getElementById("listagem").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function abreRelatorioAchado(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpachado").submit();
}

function abreRelatorio2Achado(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpachado").submit();
}

function btNovo(){
    location.href="Achado.php";
}

$(function () {
    $("#codstatus").change(function(){
        if($("#codstatus option:selected").val() === "1" && $("#codstatus option:selected").text() === "entregue"){
            $("#entregue1").css("display", "");
            $("#entreguep").attr("required", true);
        }else{
            $("#entregue1").css("display", "none");
            $("#entreguep").attr("required", false);
        }
    });
    $("#entreguep").change(function(){
       if($("#entreguep option:selected").val() === "m"){
            $("#morador1").css("display", "");
            $("#morador2").css("display", "");
            $("#codmorador").attr("required", true);
           
            $("#funcionario1").css("display", "none");
            $("#funcionario1").attr("required", false);
            $("#visitante1").css("display", "none");
            $("#visitante1").attr("required", false);           
       }else{
           if($("#entreguep option:selected").val() === "f"){
               $("#funcionario1").css("display", "");
               $("#funcionario1").attr("required", true);
               $("#visitante1").css("display", "none");
               $("#visitante1").attr("required", false);
           }else if($("#entreguep option:selected").val() === "v"){
               $("#funcionario1").css("display", "none");
               $("#funcionario1").attr("required", true);
               $("#visitante1").css("display", "");
               $("#visitante1").attr("required", false);
           }
           $("#morador1").css("display", "none");
           $("#morador2").css("display", "none");      
           $("#codmorador").attr("required", false);           
       } 
    });
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fachado').ajaxForm({
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
            bar.width(percentVal)
            percent.html(percentVal);
        },
        complete: function (xhr) {
            var data = JSON.parse(xhr.responseText);
            if(data.situacao === true){
                swal("Alteração", data.mensagem, "success");
                if(data.imagem !== null && data.imagem !== ""){
                    $("#imagemCarregada").html("<img width='150' src='../arquivos/"+data.imagem+"'  alt='Imagem usuário'/>");
                }
                procurarAchado();
            }else if(data.situacao === false){
                swal("Erro", data.mensagem, "error");
            }            
        }
    });    
});
