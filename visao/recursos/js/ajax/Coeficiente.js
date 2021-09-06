function limparTela(){
    $(":text").each(function () {
        $(this).val("");
    });

    $(":radio").each(function () {
        $(this).prop({checked: false})
    });

    $("select").each(function () {
        $(this).val("");
    });    
}

function inserirCoeficiente(){
    $.ajax({
        url : "../control/InserirCoeficiente.php",
        type: "POST",
        data : $("#fcoeficiente").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Coeficiente cadastrado", data.mensagem, "success");
                procurarCoeficiente(true);
                limparTela();
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarCoeficiente(){
    $.ajax({
        url : "../control/AtualizarCoeficiente.php",
        type: "POST",
        data : $("#fcoeficiente").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Coeficiente atualizado", data.mensagem, "success");
                procurarCoeficiente(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirCoeficiente(){
    if (window.confirm("Deseja realmente excluir esse coeficiente?")) {
        console.log("Coeficiente para ser excluido:" + document.getElementById("codcoeficiente").value);
        if(document.getElementById("codcoeficiente").value !== null && document.getElementById("codcoeficiente").value !== ""){
            $.ajax({
                url : "../control/ExcluirCoeficiente.php",
                type: "POST",
                data : {codcoeficiente: document.getElementById("codcoeficiente").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Coeficiente excluido", data.mensagem, "success");
                        procurarCoeficiente(true);
                        limparTela();
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor defina coeficiente para poder excluir!", "error");
        }
    }
}

function excluir2Coeficiente(codcoeficiente){
    swal({   
        title: "Confirma exclusão?",   
        text: "Você não poderá mais visualizar as informações desse coeficiente!",   
        type: "warning", showCancelButton: true,   
        confirmButtonColor: "#DD6B55", confirmButtonText: "Sim, exclua ele!",   
        closeOnConfirm: false, closeOnCancel: true
    }, function(isConfirm){   
        if (isConfirm) {     
            if(codcoeficiente !== null && codcoeficiente !== ""){
                $.ajax({
                    url : "../control/ExcluirCoeficiente.php",
                    type: "POST",
                    data : {codcoeficiente: codcoeficiente},
                    dataType: 'json',
                    success: function(data, textStatus, jqXHR){
                        if(data.situacao === true){
                            swal("Coeficiente excluido", data.mensagem, "success");
                            procurarCoeficiente(true);
                            limparTela();
                        }else if(data.situacao === false){
                            swal("Erro ao cadastrar", data.mensagem, "error");
                        }
                    },error: function (jqXHR, textStatus, errorThrown){
                        swal("Erro", "Erro ocasionado por:" + errorThrown, "error");
                    }
                });          
            }else{
                swal("Campo em branco", "Por favor defina coeficiente para poder excluir!", "error");
            }
        }  
    });      
}

function procurarCoeficiente(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarCoeficiente2.php",
        type: "POST",
        data : $("#fpcoeficiente").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado de coeficiente!", "error");
            }
            document.getElementById("listagem").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar coeficiente", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function abreRelatorio(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fcoeficiente").submit();
}

function abreRelatorio2(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fcoeficiente").submit();
}

function novoCoeficiente(){
    location.href="Coeficiente.php";
} 