function inserir(){
    $.ajax({
        url : "../control/InserirAcesso.php",
        type: "POST",
        data : $("#facesso").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Acesso cadastrado", data.mensagem, "success");
                procurarAcesso(true);
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
        url : "../control/AtualizarAcesso.php",
        type: "POST",
        data : $("#facesso").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Acesso atualizado", data.mensagem, "success");
                procurarAcesso(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluir(){
    if (window.confirm("Deseja realmente excluir esse acesso?")) {
        console.log("Acesso para ser excluido:" + document.getElementById("codacesso").value);
        if(document.getElementById("codacesso").value !== null && document.getElementById("codacesso").value !== ""){
            $.ajax({
                url : "../control/ExcluirAcesso.php",
                type: "POST",
                data : {codacesso: document.getElementById("codacesso").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Acesso excluido", data.mensagem, "success");
                        procurarAcesso(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor defina acesso para poder excluir!", "error");
        }
    }
}

function excluir2(codacesso){
    swal({   
        title: "Confirma exclusão?",   
        text: "Você não poderá mais visualizar as informações desse acesso!",   
        type: "warning", showCancelButton: true,   
        confirmButtonColor: "#DD6B55", confirmButtonText: "Sim, exclua ele!",   
        closeOnConfirm: false, closeOnCancel: true
    }, function(isConfirm){   
        if (isConfirm) {     
            if(codacesso !== null && codacesso !== ""){
                $.ajax({
                    url : "../control/ExcluirAcesso.php",
                    type: "POST",
                    data : {codacesso: codacesso},
                    dataType: 'json',
                    success: function(data, textStatus, jqXHR){
                        if(data.situacao === true){
                            swal("Acesso excluido", data.mensagem, "success");
                            procurarAcesso(true);
                        }else if(data.situacao === false){
                            swal("Erro ao cadastrar", data.mensagem, "error");
                        }
                    },error: function (jqXHR, textStatus, errorThrown){
                        swal("Erro", "Erro ocasionado por:" + errorThrown, "error");
                    }
                });          
            }else{
                swal("Campo em branco", "Por favor defina acesso para poder excluir!", "error");
            }
        }  
    });      
}

function procurarAcesso(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarAcesso2.php",
        type: "POST",
        data : $("#fpacesso").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado de acessos!", "error");
            }
            document.getElementById("listagem").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar acesso", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function abreRelatorio(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("facesso").submit();
}

function abreRelatorio2(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("facesso").submit();
}

function btNovo(){
    location.href="Acesso.php";
} 