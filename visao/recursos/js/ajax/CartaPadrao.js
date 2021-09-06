function inserirCartaPadrao(){
    $.ajax({
        url : "../control/InserirCartaPadrao.php",
        type: "POST",
        data : $("#fcartapadrao").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Carta Padrao cadastrado", data.mensagem, "success");
                procurarCartaPadrao(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarCartaPadrao(){
    $.ajax({
        url : "../control/AtualizarCartaPadrao.php",
        type: "POST",
        data : $("#fcartapadrao").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Carta Padrao atualizado", data.mensagem, "success");
                procurarCartaPadrao(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirCartaPadrao(){
    if (window.confirm("Deseja realmente excluir esse carta padrão?")) {
        if(document.getElementById("codcartaPadrao").value !== null && document.getElementById("codcartaPadrao").value !== ""){
            $.ajax({
                url : "../control/ExcluirCartaPadrao.php",
                type: "POST",
                data : {codcartaPadrao: document.getElementById("codcartaPadrao").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Carta Padrao excluido", data.mensagem, "success");
                        procurarCartaPadrao(true);
                    }else if(data.situacao === false){
                        swal("Erro ao cadastrar", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha a carta padrão para excluir!", "error");
        }
    }
}

function excluir2CartaPadrao (codcartaPadrao){
    if (window.confirm("Deseja realmente excluir esse carta padrão?")) {
        if(codcartaPadrao !== null && codcartaPadrao !== ""){
            $.ajax({
                url : "../control/ExcluirCartaPadrao.php",
                type: "POST",
                data : {codcartaPadrao: codcartaPadrao},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Carta Padrao excluido", data.mensagem, "success");
                        procurarCartaPadrao(true);
                    }else if(data.situacao === false){
                        swal("Erro ao cadastrar", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o carta padrão para excluir!", "error");
        }
    }
}

function procurarCartaPadrao(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarCartaPadrao.php",
        type: "POST",
        data :  $("#fpcartapadrao").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado de carta padrão!", "error");             
            }
            document.getElementById("listagemCartaPadrao").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function abreRelatorioCartaPadrao(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpcartaPadrao").submit();
}

function abreRelatorio2CartaPadrao(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpcartaPadrao").submit();
}

function btNovoCartaPadrao(){
    location.href="CartaPadrao.php";
}
