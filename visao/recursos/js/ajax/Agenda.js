function inserirAgenda(){
    $.ajax({
        url : "../control/InserirAgenda.php",
        type: "POST",
        data : {dtagenda: $("#dtagenda").val(), codpessoa: $("#codpessoa").val(), horaagenda: $("#horaagenda").val(), observacao: $("#observacaoLigacao").val(), codstatus: $("#codstatus").val()},
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Agenda cadastrado", data.mensagem, "success");
                procurarAgenda(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarAgenda(){
    $.ajax({
        url : "../control/AtualizarAgenda.php",
        type: "POST",
        data : $("#fagenda").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Agenda atualizado", data.mensagem, "success");
                procurarAgenda(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirAgenda(){
    if (window.confirm("Deseja realmente excluir esse agenda?")) {
        console.log("Agenda para ser excluido:" + document.getElementById("codagenda").value);
        if(document.getElementById("codagenda").value !== null && document.getElementById("codagenda").value !== ""){
            $.ajax({
                url : "../control/ExcluirAgenda.php",
                type: "POST",
                data : {codagenda: document.getElementById("codagenda").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Agenda excluido", data.mensagem, "success");
                        procurarAgenda(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor defina agenda para poder excluir!", "error");
        }
    }
}

function excluir2Agenda(codagenda){
    swal({   
        title: "Confirma exclusão?",   
        text: "Você não poderá mais visualizar as informações desse agenda!",   
        type: "warning", showCancelButton: true,   
        confirmButtonColor: "#DD6B55", confirmButtonText: "Sim, exclua ele!",   
        closeOnConfirm: false, closeOnCancel: true
    }, function(isConfirm){   
        if (isConfirm) {     
            if(codagenda !== null && codagenda !== ""){
                $.ajax({
                    url : "../control/ExcluirAgenda.php",
                    type: "POST",
                    data : {codagenda: codagenda},
                    dataType: 'json',
                    success: function(data, textStatus, jqXHR){
                        if(data.situacao === true){
                            swal("Agenda excluido", data.mensagem, "success");
                            procurarAgenda(true);
                        }else if(data.situacao === false){
                            swal("Erro ao cadastrar", data.mensagem, "error");
                        }
                    },error: function (jqXHR, textStatus, errorThrown){
                        swal("Erro", "Erro ocasionado por:" + errorThrown, "error");
                    }
                });          
            }else{
                swal("Campo em branco", "Por favor defina agenda para poder excluir!", "error");
            }
        }  
    });      
}

function remanejaAgenda(){
    $.ajax({
        url : "../control/RemanejaAgenda.php",
        type: "POST",
        data : $("#fremaneja").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Agenda atualizada", data.mensagem, "success");
            }else if(data.situacao === false){
                swal("Erro ao remanejar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao remanejar", "Erro causado por:" + errorThrown, "error");
        }
    });    
}

function procurarAgenda(acao){
    $("#carregando").show(); 
    $.ajax({
        url : "../control/ProcurarAgenda2.php",
        type: "POST",
        data : $("#fpagenda").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado de agendas!", "error");
            }
            if(document.getElementById("listagemAgenda") != null){
                document.getElementById("listagemAgenda").innerHTML = data;
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar agenda", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function procurarAgenda2(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarAgenda2.php",
        type: "POST",
        data : $("#fpagenda").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado de agendas!", "error");
            }
            document.getElementById("listagemAgenda").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar agenda", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function abreRelatorioAgenda(){
    document.getElementById("tipoAgenda").value = "pdf";
    document.getElementById("fpagenda").submit();
}

function abreRelatorio2Agenda(){
    document.getElementById("tipoAgenda").value = "xls";
    document.getElementById("fpagenda").submit();
}

function btNovo(){
    location.href="Agenda.php";
} 

procurarAgenda(true);