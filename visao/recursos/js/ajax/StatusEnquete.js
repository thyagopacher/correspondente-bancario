function inserirStatus() {
    if ($("#nomeStatus").val() !== null && $("#nomeStatus").val() !== "") {
        $.ajax({
            url: "../control/InserirStatusEnquete.php",
            type: "POST",
            data: $("#fstatusenquete").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Status enquete cadastrado", data.mensagem, "success");
                    procurarStatusEnquete(true);
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
        url: "../control/AtualizarStatusEnquete.php",
        type: "POST",
        data: $("#fstatusenquete").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Status enquete atualizado", data.mensagem, "success");
                procurarStatusEnquete(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirStatus() {
    if (window.confirm("Deseja realmente excluir esse status de enquete?")) {
        if (document.getElementById("codstatus").value !== null && document.getElementById("codstatus").value !== "") {
            $.ajax({
                url: "../control/ExcluirStatusEnquete.php",
                type: "POST",
                data: {codstatus: document.getElementById("codstatus").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Status enquete excluido", data.mensagem, "success");
                        $("#nomeStatus").val("");
                        procurarStatusEnquete(true);
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
    if (window.confirm("Deseja realmente excluir esse status de enquete?")) {
        if (codstatus !== null && codstatus !== "") {
            $.ajax({
                url: "../control/ExcluirStatusEnquete.php",
                type: "POST",
                data: {codstatus: codstatus},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Status enquete excluido", data.mensagem, "success");
                        procurarStatusEnquete(true);
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
    $("#btatualizarStatusEnquete").css("display", "");
    $("#btexcluirStatusEnquete").css("display", "");
    $("#btinserirStatusEnquete").css("display", "none");
}

function btNovo() {
    document.getElementById("codstatus").value = "";
    document.getElementById("nomeStatus").value = "";
    $("#btatualizarStatusEnquete").css("display", "none");
    $("#btexcluirStatusEnquete").css("display", "none");
    $("#btinserirStatusEnquete").css("display", "");
}

function procurarStatusEnquete(acao){
    $.ajax({
        url: "../control/ProcurarStatusEnquete.php",
        type: "POST",
        data: {nome: ""},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(acao === false){
                swal("Atenção", "Nada encontrado!", "error");
                
            }
            document.getElementById("listagemStatusEnquete").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

procurarStatusEnquete(true);