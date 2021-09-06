function inserirTipo() {
    if ($("#nomeTipo").val() !== null && $("#nomeTipo").val() !== "") {
        $.ajax({
            url: "../control/InserirTipoConta.php",
            type: "POST",
            data: $("#ftipoconta").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Tipo Conta cadastrado", data.mensagem, "success");
                    procurarTipoConta(true);
                } else if (data.situacao === false) {
                    swal("Erro ao cadastrar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else if ($("#nomeTipo").val() === null || $("#nomeTipo").val() === "") {
        swal("Campo em branco", "Por favor defina um nome par ao tipo!", "error");
    }
}

function atualizarTipo() {
    if ($("#nomeTipo").val() !== null && $("#nomeTipo").val() !== "") {
        $.ajax({
            url: "../control/AtualizarTipoConta.php",
            type: "POST",
            data: $("#ftipoconta").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Tipo Conta atualizado", data.mensagem, "success");
                    procurarTipoConta(true);
                } else if (data.situacao === false) {
                    swal("Erro ao atualizar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else if ($("#nomeTipo").val() === null || $("#nomeTipo").val() === "") {
        swal("Campo em branco", "Por favor defina um nome para o tipo!", "error");
    }
}

function excluirTipo() {
    if (window.confirm("Deseja realmente excluir esse tipo?")) {
        if (document.getElementById("codtipo").value !== null && document.getElementById("codtipo").value !== "") {
            $.ajax({
                url: "../control/ExcluirTipoConta.php",
                type: "POST",
                data: {codtipo: document.getElementById("codtipo").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tipo Conta excluido", data.mensagem, "success");
                        procurarTipoConta(true);
                        $("#nomeTipo").val("");
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o tipo para excluir!", "error");
        }
    }
}

function excluir2Tipo(codtipo) {
    if (window.confirm("Deseja realmente excluir esse tipo?")) {
        if (codtipo !== null && codtipo !== "") {
            $.ajax({
                url: "../control/ExcluirTipoConta.php",
                type: "POST",
                data: {codtipo: codtipo},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tipo Conta excluido", data.mensagem, "success");
                        procurarTipoConta(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o tipo para excluir!", "error");
        }
    }
}

function setaEditarTipo(tipo) {
    document.getElementById("codtipo").value = tipo[0];
    document.getElementById("nomeTipo").value = tipo[1];
    $("#btatualizartipoConta").css("display", "");
    $("#btexcluirtipoConta").css("display", "");
    $("#btinserirtipoConta").css("display", "none");
}

function btNovoTipoConta() {
    document.getElementById("codtipo").value = "";
    document.getElementById("nomeTipo").value = "";
    $("#btatualizartipoConta").css("display", "none");
    $("#btexcluirtipoConta").css("display", "none");
    $("#btinserirtipoConta").css("display", "");
}

function procurarTipoConta(acao) {
    $.ajax({
        url: "../control/ProcurarTipoConta2.php",
        type: "POST",
        data: {nome: ""},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagemTipoConta").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar Tipo Conta", "Erro causado por:" + errorThrown, "error");
        }
    });
}

procurarTipoConta(true);