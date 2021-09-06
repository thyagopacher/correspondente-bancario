function inserirLog(){
    if($("#assunto").val() !== ""){
        $.ajax({
            url : "../control/InserirLog.php",
            type: "POST",
            data : $("#rLogSistema").serialize(),
            dataType: 'json',
            success: function(data, textStatus, jqXHR){
                if(data.situacao === true){
                    swal("Log cadastrada", data.mensagem, "success");
                    procurarLog(true);
                }else if(data.situacao === false){
                    swal("Erro ao cadastrar", data.mensagem, "error");
                }
            },error: function (jqXHR, textStatus, errorThrown){
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });  
    }else if($("#assunto").val() === ""){
        swal("Campo em branco", "Por favor defina assunto!", "error");
    }
}

function atualizarLog(){
    $.ajax({
        url : "../control/AtualizarLog.php",
        type: "POST",
        data : $("#rLogSistema").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Log atualizado", data.mensagem, "success");
                procurarLog(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirLog(){
    if (window.confirm("Deseja realmente excluir essa mensagem?")) {
        if(document.getElementById("codmensagem").value !== null && document.getElementById("codmensagem").value !== ""){
            $.ajax({
                url : "../control/ExcluirLog.php",
                type: "POST",
                data : {codmensagem: document.getElementById("codmensagem").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Log excluida", data.mensagem, "success");
                        procurarLog(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha a mensagem para excluir!", "error");
        }
    }
}

function excluir2Log(codigo){
    if (window.confirm("Deseja realmente excluir esse log?")) {
        if(codigo !== null && codigo !== ""){
            $.ajax({
                url : "../control/ExcluirLog.php",
                type: "POST",
                data : {codigo: codigo},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Log excluido", data.mensagem, "success");
                        procurarLog(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o log para excluir!", "error");
        }
    }
}

function procurarLog(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarLog2.php",
        type: "POST",
        data : $("#rLogSistema").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado de Log!", "error");            
            }
            document.getElementById("listagem").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function btNovoLog(){
    location.href="Log.php";
}

function abreRelatorio(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("rLogSistema").submit();
}

function abreRelatorio2(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("rLogSistema").submit();
}