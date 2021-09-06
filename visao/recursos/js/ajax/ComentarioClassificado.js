function inserir(){
    $.ajax({
        url : "../control/InserirComentarioClassificado.php",
        type: "POST",
        data : $("#fcomentario").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Comentário cadastrado", data.mensagem, "success");
                procurarComentarioClassificado(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizar(){
    $.ajax({
        url : "../control/AtualizarComentarioClassificado.php",
        type: "POST",
        data : $("#fcomentario").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Comentário atualizado", data.mensagem, "success");
                procurarComentarioClassificado(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluir(){
    if (window.confirm("Deseja realmente excluir esse comentário?")) {
        if(document.getElementById("codaviso").value !== null && document.getElementById("codaviso").value !== ""){
            $.ajax({
                url : "../control/ExcluirComentarioClassificado.php",
                type: "POST",
                data : {codaviso: document.getElementById("codaviso").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Comentário excluido", data.mensagem, "success");
                        procurarComentarioClassificado(true);
                    }else if(data.situacao === false){
                        swal("Erro ao cadastrar", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha a comentário para excluir!", "error");
        }
    }
}

function excluir2ComentarioClassificado (codaviso){
    if (window.confirm("Deseja realmente excluir esse aviso?")) {
        if(codaviso !== null && codaviso !== ""){
            $.ajax({
                url : "../control/ExcluirComentarioClassificado.php",
                type: "POST",
                data : {codaviso: codaviso},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("ComentarioClassificado excluido", data.mensagem, "success");
                        procurarComentarioClassificado(true);
                    }else if(data.situacao === false){
                        swal("Erro ao cadastrar", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o aviso para excluir!", "error");
        }
    }
}

function procurarComentarioClassificado(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarComentarioClassificado.php",
        type: "POST",
        data :  $("#fpaviso").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado!", "error");             
            }
            document.getElementById("listagem").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function abreRelatorioComentarioClassificado(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpaviso").submit();
}

function abreRelatorio2ComentarioClassificado(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpaviso").submit();
}

function btNovo(){
    location.href="ComentarioClassificado.php";
}

$(function () {
  
    $("#fcomentario").submit(function(){
        $(".progress").css("visibility", "visible");
    });    
  
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fcomentario').ajaxForm({
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
                    $("#imagemCarregada").html("<img width='150' src='../arquivos/"+data.imagem+"'  alt='Imagem classificado'/>");
                }
                if(data.codnivel !== "3"){
                    procurarComentarioClassificado(true);
                }
                location.reload();
            }else if(data.situacao === false){
                swal("Erro", data.mensagem, "error");
            }            
        }
    });    
});
