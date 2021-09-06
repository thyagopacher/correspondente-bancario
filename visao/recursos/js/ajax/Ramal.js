function limparTela(){
    $("#telefone").val("");
    $("#nome").val("");
    $("#telefone").val("");
    $("#externo").val("");
    $("#ramal").val("");
    $('#telefone').mask("(99)9999-9999?9");
}

function inserirRamal(){
    $.ajax({
        url : "../control/InserirRamal.php",
        type: "POST",
        data : $("#framal").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Ramal cadastrado", data.mensagem, "success");
                procurarRamal(true);
                limparTela();
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarRamal(){
    $.ajax({
        url : "../control/AtualizarRamal.php",
        type: "POST",
        data : $("#framal").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Ramal atualizado", data.mensagem, "success");
                procurarRamal(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirRamal(){
    if (window.confirm("Deseja realmente excluir esse ramal?")) {
        if(document.getElementById("codramal").value !== null && document.getElementById("codramal").value !== ""){
            $.ajax({
                url : "../control/ExcluirRamal.php",
                type: "POST",
                data : {codramal: document.getElementById("codramal").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Ramal excluido", data.mensagem, "success");
                        procurarRamal(true);
                        limparTela();
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o ramal para excluir!", "error");
        }
    }
}


function excluirRamal2(codramal){
    if (window.confirm("Deseja realmente excluir esse ramal?")) {
        if(codramal !== null && codramal !== ""){
            $.ajax({
                url : "../control/ExcluirRamal.php",
                type: "POST",
                data : {codramal: codramal},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Ramal excluido", data.mensagem, "success");
                        procurarRamal(true);
                        limparTela();
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha o ramal para excluir!", "error");
        }
    }
}

function btNovoRamal(){
    location.href="Ramal.php";
}

/**trazendo listagem de ramals cadastrados*/
function procurarRamal(acao){
    $.ajax({
        url : "../control/ProcurarRamal2.php",
        type: "POST",
        data : $("#fpramal").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagem").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}


function abreRelatorioRamal(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpramal").submit();
}

function abreRelatorio2Ramal(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpramal").submit();
}
