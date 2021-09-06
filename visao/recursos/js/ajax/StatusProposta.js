function inserirStatus() {
    if ($("#nomeStatus").val() !== null && $("#nomeStatus").val() !== "") {
        $.ajax({
            url: "../control/InserirStatusProposta.php",
            type: "POST",
            data: $("#fstatusproposta").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Status proposta", data.mensagem, "success");
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
        url: "../control/AtualizarStatusProposta.php",
        type: "POST",
        data: $("#fstatusproposta").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Status proposta", data.mensagem, "success");
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
        if (document.getElementById("codigoStatus").value !== null && document.getElementById("codigoStatus").value !== "") {
            $.ajax({
                url: "../control/ExcluirStatusProposta.php",
                type: "POST",
                data: {codstatus: document.getElementById("codigoStatus").value},
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

function excluir2Status(codigoStatus) {
    if (window.confirm("Deseja realmente excluir esse status?")) {
        if (codigoStatus !== null && codigoStatus !== "") {
            $.ajax({
                url: "../control/ExcluirStatusProposta.php",
                type: "POST",
                data: {codstatus: codigoStatus},
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
    $("#btatualizarStatusProposta").css("display", "");
    $("#btexcluirStatusProposta").css("display", "");
    $("#btinserirStatusProposta").css("display", "none");
}

function btNovoStatus() {
    location.href="StatusProposta.php";
}

function procurarStatus(acao){
    $.ajax({
        url: "../control/ProcurarStatusProposta2.php",
        type: "POST",
        data: $("#formProcurarStatusProposta").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado!", "error");
            }
            if(document.getElementById("listagemStatusProposta") != null){
                document.getElementById("listagemStatusProposta").innerHTML = data;
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function procurarStatus2(acao) {
    $.ajax({
        url: "../control/ProcurarStatusProposta2.php",
        type: "POST",
        data: {nome: ""},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado!", "error");
            }
            if(document.getElementById("listagemStatusProposta") != null){
                document.getElementById("listagemStatusProposta").innerHTML = data;
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

procurarStatus2();