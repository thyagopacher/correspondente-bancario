function inserirAcessoOperador(){
    $.ajax({
        url : "../control/InserirAcessoOperador.php",
        type: "POST",
        data : $("#facesso").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Acesso cadastrado", data.mensagem, "success");
                procurarAcessoOperador(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarAcessoOperador(){
    $.ajax({
        url : "../control/AtualizarAcessoOperador.php",
        type: "POST",
        data : $("#facesso").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Acesso atualizado", data.mensagem, "success");
                procurarAcessoOperador(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirAcessoOperador(){
    if (window.confirm("Deseja realmente excluir esse acesso?")) {
        console.log("Acesso para ser excluido:" + document.getElementById("codacesso").value);
        if(document.getElementById("codacesso").value !== null && document.getElementById("codacesso").value !== ""){
            $.ajax({
                url : "../control/ExcluirAcessoOperador.php",
                type: "POST",
                data : {codacesso: document.getElementById("codacesso").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Acesso excluido", data.mensagem, "success");
                        procurarAcessoOperador(true);
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

function excluir2AcessoOperador(codacesso){
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
                    url : "../control/ExcluirAcessoOperador.php",
                    type: "POST",
                    data : {codacesso: codacesso},
                    dataType: 'json',
                    success: function(data, textStatus, jqXHR){
                        if(data.situacao === true){
                            swal("Acesso excluido", data.mensagem, "success");
                            procurarAcessoOperador(true);
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

function procurarAcessoOperador(acao){
    if(document.getElementById("listagemAcessoOperador") !== null){
        $("#carregando").show();
        $.ajax({
            url : "../control/ProcurarAcessoOperador2.php",
            type: "POST",
            data : $("#facesso").serialize(),
            dataType: 'text',
            success: function(data, textStatus, jqXHR){
                if(acao === false && data === ""){
                    swal("Atenção", "Nada encontrado de acesso operador!", "error");
                }
                document.getElementById("listagemAcessoOperador").innerHTML = data;
            },error: function (jqXHR, textStatus, errorThrown){
                swal("Erro ao procurar acesso", "Erro causado por:" + errorThrown, "error");
            }
        });  
        $("#carregando").hide();
    }
}

function abreRelatorio(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("facesso").submit();
}

function abreRelatorio2(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("facesso").submit();
}

function btNovoAcessoOperador(){
    $("#btinserirAcessoOperador").css("display", "");
    $("#btatualizarAcessoOperador").css("display", "none");
    $("#btexcluirAcessoOperador").css("display", "none");
    $("#btnovoAcessoOperador").css("display", "none");
} 

function setaEditarAcessoOperador(acessoOperador){
    $("#codacesso").val(acessoOperador[0]);
    $("#codcarteira").val(acessoOperador[1]);
    $("#codoperador").val(acessoOperador[2]);
    $("#btinserirAcessoOperador").css("display", "none");
    $("#btatualizarAcessoOperador").css("display", "");
    $("#btexcluirAcessoOperador").css("display", "");
    $("#btnovoAcessoOperador").css("display", "");    
    $("#tabs").tabs({active: 2});    
}

procurarAcessoOperador(true);