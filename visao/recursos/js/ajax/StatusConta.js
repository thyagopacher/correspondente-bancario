function inserirStatus() {
    if ($("#nomeStatus").val() !== null && $("#nomeStatus").val() !== "") {
        $.ajax({
            url: "../control/InserirStatusConta.php",
            type: "POST",
            data: $("#fstatusconta").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Status conta", data.mensagem, "success");
                    procurarStatus2();
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
        url: "../control/AtualizarStatusConta.php",
        type: "POST",
        data: $("#fstatusconta").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Status conta", data.mensagem, "success");
                procurarStatus2();
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirStatus() {
    if (window.confirm("Deseja realmente excluir esse status?")) {
        if (document.getElementById("codstatus").value !== null && document.getElementById("codstatus").value !== "") {
            $.ajax({
                url: "../control/ExcluirStatusConta.php",
                type: "POST",
                data: {codstatus: document.getElementById("codstatus").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Status excluido", data.mensagem, "success");
                        $("#nomeStatus").val("");
                        procurarStatus2();
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

function excluir2Status(codstatus) {
    if (window.confirm("Deseja realmente excluir esse status?")) {
        if (codstatus !== null && codstatus !== "") {
            $.ajax({
                url: "../control/ExcluirStatusConta.php",
                type: "POST",
                data: {codstatus: codstatus},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Status excluido", data.mensagem, "success");
                        procurarStatus2();
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

function setaEditarStatus(status) {
    document.getElementById("codigoStatus").value = status[0];
    document.getElementById("nomeStatus").value = status[1];
    $("#btatualizarStatusConta").css("display", "");
    $("#btexcluirStatusConta").css("display", "");
    $("#btinserirStatusConta").css("display", "none");
}

function btNovoStatus() {
    document.getElementById("codigoStatus").value = "";
    document.getElementById("nomeStatus").value = "";
    $("#btatualizarStatusConta").css("display", "none");
    $("#btexcluirStatusConta").css("display", "none");
    $("#btinserirStatusConta").css("display", "");
}

function procurarStatus(acao){
    $.ajax({
        url: "../control/ProcurarStatusConta.php",
        type: "POST",
        data: {nome: $("#nomeStatus").val()},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(document.getElementById("listagemStatusConta") != null){
                document.getElementById("listagemStatusConta").innerHTML = data;
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function procurarStatus2(acao) {
    $.ajax({
        url: "../control/ProcurarStatusConta.php",
        type: "POST",
        data: {nome: ""},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado!", "error");
            }
            if(document.getElementById("listagemStatusConta") != null){
                document.getElementById("listagemStatusConta").innerHTML = data;
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

procurarStatus2();