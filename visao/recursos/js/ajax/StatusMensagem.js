function inserirStatus() {
    if ($("#nomeStatus").val() !== null && $("#nomeStatus").val() !== "") {
        $.ajax({
            url: "../control/InserirStatusMensagem.php",
            type: "POST",
            data: $("#fstatusmensagem").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Status mensagem cadastrado", data.mensagem, "success");
                    procurar(true);
                } else if (data.situacao === false) {
                    swal("Erro ao cadastrar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else if ($("#nomeStatus").val() === null || $("#nomeStatus").val() === "") {
        swal("Campo em branco", "Por favor defina um nome para o status!", "error");
    }
}

function atualizarStatus() {
    $.ajax({
        url: "../control/AtualizarStatusMensagem.php",
        type: "POST",
        data: $("#fstatusmensagem").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Status mensagem atualizado", data.mensagem, "success");
                procurar(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirStatus() {
    if (window.confirm("Deseja realmente excluir esse status de mensagem?")) {
        if (document.getElementById("codstatus").value !== null && document.getElementById("codstatus").value !== "") {
            $.ajax({
                url: "../control/ExcluirStatusMensagem.php",
                type: "POST",
                data: {codstatus: document.getElementById("codstatus").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Status mensagem excluido", data.mensagem, "success");
                        $("#nomeStatus").val("");
                        procurar(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o status para excluir!", "error");
        }
    }
}

function excluir2(codstatus) {
    if (window.confirm("Deseja realmente excluir esse status de mensagem?")) {
        if (codstatus !== null && codstatus !== "") {
            $.ajax({
                url: "../control/ExcluirStatusMensagem.php",
                type: "POST",
                data: {codstatus: codstatus},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Status mensagem excluido", data.mensagem, "success");
                        procurar(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o status para excluir!", "error");
        }
    }
}

function setaEditar(status) {
    document.getElementById("codstatus").value = status[0];
    document.getElementById("nomeStatus").value = status[1];
    $("#btatualizarStatusMensagem").css("display", "");
    $("#btexcluirStatusMensagem").css("display", "");
    $("#btinserirStatusMensagem").css("display", "none");
}

function btNovo() {
    document.getElementById("codstatus").value = "";
    document.getElementById("nomeStatus").value = "";
    $("#btatualizarStatusMensagem").css("display", "none");
    $("#btexcluirStatusMensagem").css("display", "none");
    $("#btinserirStatusMensagem").css("display", "");
}

function procurar(acao){
    $.ajax({
        url: "../control/ProcurarStatusMensagem.php",
        type: "POST",
        data: {nome: ""},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(acao === false && data === "0"){
                swal("Atenção", "Nada encontrado!", "error");
                document.getElementById("listagemStatusMensagem").innerHTML = "";
            }
            if(document.getElementById("listagemStatusMensagem") != null){
                document.getElementById("listagemStatusMensagem").innerHTML = data;
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

procurar(true);