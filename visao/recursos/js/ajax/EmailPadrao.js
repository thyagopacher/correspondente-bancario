function inserirEmailPadrao(){
    $.ajax({
        url : "../control/InserirEmailPadrao.php",
        type: "POST",
        data : $("#femailpadrao").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Email Padrao cadastrado", data.mensagem, "success");
                procurarEmailPadrao(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarEmailPadrao(){
    $.ajax({
        url : "../control/AtualizarEmailPadrao.php",
        type: "POST",
        data : $("#femailpadrao").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Email Padrao atualizado", data.mensagem, "success");
                procurarEmailPadrao(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirEmailPadrao(){
    if (window.confirm("Deseja realmente excluir esse email padrão?")) {
        if(document.getElementById("codemailPadrao").value !== null && document.getElementById("codemailPadrao").value !== ""){
            $.ajax({
                url : "../control/ExcluirEmailPadrao.php",
                type: "POST",
                data : {codemailPadrao: document.getElementById("codemailPadrao").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Email Padrao excluido", data.mensagem, "success");
                        procurarEmailPadrao(true);
                    }else if(data.situacao === false){
                        swal("Erro ao cadastrar", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha a email padrão para excluir!", "error");
        }
    }
}

function excluir2EmailPadrao (codemailPadrao){
    if (window.confirm("Deseja realmente excluir esse email padrão?")) {
        if(codemailPadrao !== null && codemailPadrao !== ""){
            $.ajax({
                url : "../control/ExcluirEmailPadrao.php",
                type: "POST",
                data : {codemailPadrao: codemailPadrao},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Email Padrao excluido", data.mensagem, "success");
                        procurarEmailPadrao(true);
                    }else if(data.situacao === false){
                        swal("Erro ao cadastrar", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o email padrão para excluir!", "error");
        }
    }
}

function procurarEmailPadrao(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarEmailPadrao.php",
        type: "POST",
        data :  $("#fpemailpadrao").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado!", "error");             
            }
            document.getElementById("listagemEmailPadrao").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function abreRelatorioEmailPadrao(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpemailPadrao").submit();
}

function abreRelatorio2EmailPadrao(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpemailPadrao").submit();
}

function btNovoEmailPadrao(){
    location.href="EmailPadrao.php";
}
