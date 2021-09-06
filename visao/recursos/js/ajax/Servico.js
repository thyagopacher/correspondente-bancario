function inserir(){
    $.ajax({
        url : "../control/InserirServico.php",
        type: "POST",
        data : $("#fservico").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Serviço cadastrado", data.mensagem, "success");
                procurarServico(true);
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
        url : "../control/AtualizarServico.php",
        type: "POST",
        data : $("#fservico").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Serviço atualizado", data.mensagem, "success");
                procurarServico(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirServico(){
    if (window.confirm("Deseja realmente excluir esse servico?")) {
        if(document.getElementById("codservico").value !== null && document.getElementById("codservico").value !== ""){
            $.ajax({
                url : "../control/ExcluirServico.php",
                type: "POST",
                data : {codservico: document.getElementById("codservico").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Serviço excluido", data.mensagem, "success");
                        procurarServico(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o serviço para excluir!", "error");
        }
    }
}

function excluirServico2(codservico){
    if (window.confirm("Deseja realmente excluir esse servico?")) {
        if(codservico !== null && codservico !== ""){
            $.ajax({
                url : "../control/ExcluirServico.php",
                type: "POST",
                data : {codservico: codservico},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Serviço excluido", data.mensagem, "success");
                        procurarServico(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o serviço para excluir!", "error");
        }
    }
}

function procurarServico(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarServico.php",
        type: "POST",
        data : $("#fpservico").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(data !== "0"){
                document.getElementById("listagem").innerHTML = data; 
            }else if(acao === false && data === "0"){
                swal("Atenção", "Nada encontrado de serviços!", "error");
                document.getElementById("listagem").innerHTML = "";             
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function abreRelatorioServico(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpservico").submit();
}

function abreRelatorio2Servico(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpservico").submit();
}

function btNovoServico(){
    location.href="Servico.php";
}

/**daqui para baixa responsável pelo ajax de inserir ou atualizar arquivo e também pelo upload sem redirecionar página*/
(function () {
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $("#fservico").submit(function(){
        $(".progress").css("visibility", "visible");
    });

    $('#fservico').ajaxForm({
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
            bar.width(percentVal);
            percent.html(percentVal);
        },
        complete: function (xhr) {
            var data = JSON.parse(xhr.responseText);
            if(data.situacao === true){
                swal("Alteração", data.mensagem, "success");
                if(data.codnivel !== "3"){
                    procurarServico(true);
                }
            }else if(data.situacao === false){
                swal("Erro", data.mensagem, "error");
            }    
        }
    });

})();