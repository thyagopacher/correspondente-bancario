function inserirSite(){
    $.ajax({
        url : "../control/InserirSite.php",
        type: "POST",
        data : $("#fsite").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Site cadastrado", data.mensagem, "success");
                procurarSite(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarSite(){
    $.ajax({
        url : "../control/AtualizarSite.php",
        type: "POST",
        data : $("#fsite").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Site atualizado", data.mensagem, "success");
                procurarSite(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirSite(){
    if (window.confirm("Deseja realmente excluir esse site?")) {
        if(document.getElementById("codsite").value !== null && document.getElementById("codsite").value !== ""){
            $.ajax({
                url : "../control/ExcluirSite.php",
                type: "POST",
                data : {codsite: document.getElementById("codsite").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Site excluido", data.mensagem, "success");
                        procurarSite(true);
                    }else if(data.situacao === false){
                        swal("Erro ao cadastrar", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha a site para excluir!", "error");
        }
    }
}

function excluir2Site (codsite){
    if (window.confirm("Deseja realmente excluir esse site?")) {
        if(codsite !== null && codsite !== ""){
            $.ajax({
                url : "../control/ExcluirSite.php",
                type: "POST",
                data : {codsite: codsite},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Site excluido", data.mensagem, "success");
                        procurarSite(true);
                    }else if(data.situacao === false){
                        swal("Erro ao cadastrar", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o site para excluir!", "error");
        }
    }
}

function procurarSite(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarSite.php",
        type: "POST",
        data :  $("#fpsite").serialize(),
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

function abreRelatorioSite(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpsite").submit();
}

function abreRelatorio2Site(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpsite").submit();
}

function btNovoSite(){
    location.href="Site.php";
}

$(function () {
  
    $("#fsite").submit(function(){
        $(".progress").css("visibility", "visible");
    });    
  
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fsite').ajaxForm({
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
            }else if(data.situacao === false){
                swal("Erro", data.mensagem, "error");
            }            
        }
    });    
});
