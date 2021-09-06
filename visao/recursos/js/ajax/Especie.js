function inserirEspecie(){
    $.ajax({
        url : "../control/InserirEspecie.php",
        type: "POST",
        data : $("#fespecie").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Especie cadastrado", data.mensagem, "success");
                procurarEspecie(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarEspecie(){
    $.ajax({
        url : "../control/AtualizarEspecie.php",
        type: "POST",
        data : $("#fespecie").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Especie atualizado", data.mensagem, "success");
                procurarEspecie(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirEspecie(){
    if (window.confirm("Deseja realmente excluir esse especie?")) {
        if(document.getElementById("codespecie").value !== null && document.getElementById("codespecie").value !== ""){
            $.ajax({
                url : "../control/ExcluirEspecie.php",
                type: "POST",
                data : {codespecie: document.getElementById("codespecie").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Especie excluido", data.mensagem, "success");
                        procurarEspecie(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor defina especie para poder excluir!", "error");
        }
    }
}

function excluir2Especie(codespecie){
    swal({   
        title: "Confirma exclusão?",   
        text: "Você não poderá mais visualizar as informações desse especie!",   
        type: "warning", showCancelButton: true,   
        confirmButtonColor: "#DD6B55", confirmButtonText: "Sim, exclua ele!",   
        closeOnConfirm: false, closeOnCancel: true
    }, function(isConfirm){   
        if (isConfirm) {     
            if(codespecie !== null && codespecie !== ""){
                $.ajax({
                    url : "../control/ExcluirEspecie.php",
                    type: "POST",
                    data : {codespecie: codespecie},
                    dataType: 'json',
                    success: function(data, textStatus, jqXHR){
                        if(data.situacao === true){
                            swal("Especie excluido", data.mensagem, "success");
                            procurarEspecie(true);
                        }else if(data.situacao === false){
                            swal("Erro ao cadastrar", data.mensagem, "error");
                        }
                    },error: function (jqXHR, textStatus, errorThrown){
                        swal("Erro", "Erro ocasionado por:" + errorThrown, "error");
                    }
                });          
            }else{
                swal("Campo em branco", "Por favor defina especie para poder excluir!", "error");
            }
        }  
    });      
}

function procurarEspecie(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarEspecie2.php",
        type: "POST",
        data : $("#fpespecie").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagem").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar especie", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function abreRelatorioEspecie(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpespecie").submit();
}

function abreRelatorio2Especie(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpespecie").submit();
}

function btNovoEspecie(){
    location.href="Especie.php";
} 