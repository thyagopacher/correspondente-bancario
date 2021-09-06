function inserirStatus() {
    if ($("#nomeStatus").val() !== null && $("#nomeStatus").val() !== "") {
        $.ajax({
            url: "../control/InserirStatusAchado.php",
            type: "POST",
            data: $("#fstatuscorrespondencia").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Status correspondência", data.mensagem, "success");
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
        url: "../control/AtualizarStatusAchado.php",
        type: "POST",
        data: $("#fstatuscorrespondencia").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Status correspondência", data.mensagem, "success");
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
                url: "../control/ExcluirStatusAchado.php",
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

function excluir2(codstatus) {
    if (window.confirm("Deseja realmente excluir esse status?")) {
        if (codstatus !== null && codstatus !== "") {
            $.ajax({
                url: "../control/ExcluirStatusAchado.php",
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

function setaEditar(status) {
    document.getElementById("codigoStatus").value = status[0];
    document.getElementById("nomeStatus").value = status[1];
    document.getElementById("padraoStatus").value = status[2];
    $("#btatualizarStatusAchado").css("display", "");
    $("#btexcluirStatusAchado").css("display", "");
    $("#btinserirStatusAchado").css("display", "none");
}

function btNovoStatus() {
    document.getElementById("codstatus").value = "";
    document.getElementById("nomeStatus").value = "";
    $("#btatualizarStatusAchado").css("display", "none");
    $("#btexcluirStatusAchado").css("display", "none");
    $("#btinserirStatusAchado").css("display", "");
}

function procurarStatus(acao){
    $.ajax({
        url: "../control/ProcurarStatusAchado.php",
        type: "POST",
        data: {nome: $("#nomeStatus").val()},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            document.getElementById("listagemStatusAchado").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function procurarStatus2(acao) {
    $.ajax({
        url: "../control/ProcurarStatusAchado.php",
        type: "POST",
        data: {nome: ""},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(acao === false && data === "0"){
                swal("Atenção", "Nada encontrado!", "error");
                document.getElementById("listagemStatusAchado").innerHTML = "";
            }else if(data !== "0"){
                document.getElementById("listagemStatusAchado").innerHTML = data;
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

procurarStatus2();