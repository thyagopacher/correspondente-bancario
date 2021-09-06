function limparTela(){
    $(":text").each(function () {
        $(this).val("");
    });
    $(":input").each(function () {
        $(this).val("");
    });
    $(":hidden").each(function () {
        $(this).val("");
    });
    $(":radio").each(function () {
        $(this).prop({checked: false})
    });
    $("select").each(function () {
        $(this).val("");
    });    
}

function inserirArquivoPessoa(){
    $.ajax({
        url : "../control/InserirArquivoPessoa.php",
        type: "POST",
        data : $("#farquivoPessoa").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Arquivo Pessoa inserido", data.mensagem, "success");
                procurarArquivoPessoa(true);
                btNovoArquivoPessoa();
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarArquivoPessoa(){
    $.ajax({
        url : "../control/AtualizarArquivoPessoa.php",
        type: "POST",
        data : $("#farquivoPessoa").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Arquivo Pessoa atualizado", data.mensagem, "success");
                procurarArquivoPessoa(true);
                btNovoArquivoPessoa();
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirArquivoPessoa(){
    if (window.confirm("Deseja realmente excluir esse arquivo?")) {
        if(document.getElementById("codarquivo").value !== null && document.getElementById("codarquivo").value !== ""){
            $.ajax({
                url : "../control/ExcluirArquivoPessoa.php",
                type: "POST",
                data : {codarquivo: document.getElementById("codarquivo").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Arquivo Pessoa excluido", data.mensagem, "success");
                        procurarArquivoPessoa(true);
                        btNovoArquivoPessoa();
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o arquivo para excluir!", "error");
        }
    }
}

function excluir2ArquivoPessoa(codarquivo){
    if (window.confirm("Deseja realmente excluir esse arquivo?")) {
        if(codarquivo !== null && codarquivo !== ""){
            $.ajax({
                url : "../control/ExcluirArquivoPessoa.php",
                type: "POST",
                data : {codarquivo: codarquivo},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Arquivo Pessoa excluido", data.mensagem, "success");
                        procurarArquivoPessoa(true);
                        btNovoArquivoPessoa();
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                        procurarArquivoPessoa(true);
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o arquivo para excluir!", "error");
        }
    }
}

function procurarArquivoPessoa(){
    if(document.getElementById("listagemArquivoPessoa") != null){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarArquivoPessoa.php",
        type: "POST",
        data:  $("#farquivoPessoa").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            document.getElementById("listagemArquivoPessoa").innerHTML = data; 
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
    }
}

function btNovoArquivoPessoa(){
    document.getElementById("arquivoPessoa").value     = "";
    document.getElementById("nomeArquivoPessoa").value = "";
}

/**daqui para baixa responsável pelo ajax de inserir ou atualizar arquivo e também pelo upload sem redirecionar página*/
(function () {

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $("#farquivoPessoa").submit(function(){
        $(".progress").css("visibility", "visible");
    });

    $('#farquivoPessoa').ajaxForm({
        beforeSend: function () {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal);
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
                swal("Cadastro", data.mensagem, "success");
                procurarArquivoPessoa(true);
                btNovoArquivoPessoa();
            }else if(data.situacao === false){
                swal("Erro", data.mensagem, "error");
            }    
        }
    });

})();

procurarArquivoPessoa();