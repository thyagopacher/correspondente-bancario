function inserirSmsPadrao(){
    $.ajax({
        url : "../control/InserirSmsPadrao.php",
        type: "POST",
        data : $("#fsmspadrao").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Sms Padrão cadastrado", data.mensagem, "success");
                $("#texto").val("");
                procurarSmsPadrao(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarSmsPadrao(){
    $.ajax({
        url : "../control/AtualizarSmsPadrao.php",
        type: "POST",
        data : $("#fsmspadrao").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Sms Padrão atualizado", data.mensagem, "success");
                procurarSmsPadrao(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirSmsPadrao(){
    if (window.confirm("Deseja realmente excluir esse sms padrão???")) {
        if(document.getElementById("codsmspadrao").value !== null && document.getElementById("codsmspadrao").value !== ""){
            $.ajax({
                url : "../control/ExcluirSmsPadrao.php",
                type: "POST",
                data : {codsmspadrao: document.getElementById("codsmspadrao").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Sms Padrão excluido", data.mensagem, "success");
                        procurarSmsPadrao(true);
                    }else if(data.situacao === false){
                        swal("Erro ao cadastrar", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha a sms padrão para excluir!!", "error");
        }
    }
}

function excluirSmsPadrao2 (codsmsPadrao){
    if (window.confirm("Deseja realmente excluir esse sms padrão?")) {
        if(codsmsPadrao !== null && codsmsPadrao !== ""){
            $.ajax({
                url : "../control/ExcluirSmsPadrao.php",
                type: "POST",
                data : {codsmspadrao: codsmsPadrao},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Sms Padrão excluido", data.mensagem, "success");
                        procurarSmsPadrao(true);
                    }else if(data.situacao === false){
                        swal("Erro ao cadastrar", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o sms padrão para excluir!", "error");
        }
    }
}

function procurarSmsPadrao(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarSmsPadrao2.php",
        type: "POST",
        data :  $("#fpsmspadrao").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado!", "error");             
            }
            document.getElementById("listagemSmsPadrao").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function abreRelatorioSmsPadrao(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpsmsPadrao").submit();
}

function abreRelatorio2SmsPadrao(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpsmsPadrao").submit();
}

function btNovoSmsPadrao(){
    location.href="SmsPadrao.php";
}
