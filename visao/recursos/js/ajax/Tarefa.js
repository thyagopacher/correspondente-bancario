function inserirTarefa(){
    $.ajax({
        url : "../control/InserirTarefa.php",
        type: "POST",
        data : $("#ftarefa").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Tarefa atualizada", data.mensagem, "success");
                procurarTarefa(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarTarefa(){
    $.ajax({
        url : "../control/AtualizarTarefa.php",
        type: "POST",
        data : $("#ftarefa").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Tarefa atualizada", data.mensagem, "success");
                procurarTarefa(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirTarefa(){
    if (window.confirm("Deseja realmente excluir essa tarefa?")) {
        if(document.getElementById("codtarefa").value !== null && document.getElementById("codtarefa").value !== ""){
            $.ajax({
                url : "../control/ExcluirTarefa.php",
                type: "POST",
                data : {codtarefa: document.getElementById("codtarefa").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Tarefa excluido", data.mensagem, "success");
                        procurarTarefa(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o tarefa para excluir!", "error");
        }
    }
}

function excluir2Tarefa(codtarefa){
    if (window.confirm("Deseja realmente excluir esse tarefa?")) {
        if(codtarefa !== null && codtarefa !== ""){
            $.ajax({
                url : "../control/ExcluirTarefa.php",
                type: "POST",
                data : {codtarefa: codtarefa},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Tarefa excluida", data.mensagem, "success");
                        procurarTarefa(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                        procurarTarefa(true);
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o tarefa para excluir!", "error");
        }
    }
}

function procurarTarefa(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarTarefa.php",
        type: "POST",
        data : $("#fptarefa").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data == ""){
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagemTarefa").innerHTML = data;             
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function abreRelatorioTarefa(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fptarefa").submit();
}

function abreRelatorio2Tarefa(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fptarefa").submit();
}

function btNovoTarefa(){
    location.href="Tarefa.php";
}


/**daqui para baixa responsável pelo ajax de inserir ou atualizar tarefa e também pelo upload sem redirecionar página*/
(function () {
 
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $("#ftarefa").submit(function(){
        $(".progress").css("visibility", "visible");
    });

    $('#ftarefa').ajaxForm({
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
                    procurarTarefa();
                }
            }else if(data.situacao === false){
                swal("Erro", data.mensagem, "error");
            }    
        }
    });

})();