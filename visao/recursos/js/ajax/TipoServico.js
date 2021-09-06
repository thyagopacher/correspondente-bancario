function inserirTipo() {
    if ($("#nomeTipo").val() !== null && $("#nomeTipo").val() !== "") {
        $.ajax({
            url: "../control/InserirTipoServico.php",
            type: "POST",
            data: $("#ftiposervico").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Tipo Servico cadastrado", data.mensagem, "success");
                    procurarTipoServico(true);
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
            url: "../control/AtualizarTipoServico.php",
            type: "POST",
            data: $("#ftiposervico").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Tipo Servico atualizado", data.mensagem, "success");
                    procurarTipoServico(true);
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
                url: "../control/ExcluirTipoServico.php",
                type: "POST",
                data: {codtipo: document.getElementById("codtipo").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tipo Servico excluido", data.mensagem, "success");
                        procurarTipoServico(true);
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
                url: "../control/ExcluirTipoServico.php",
                type: "POST",
                data: {codtipo: codtipo},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tipo Servico excluido", data.mensagem, "success");
                        procurarTipoServico(true);
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
    $("#btatualizartipoServico").css("display", "");
    $("#btexcluirtipoServico").css("display", "");
    $("#btinserirtipoServico").css("display", "none");
}

function btNovoTipoServico() {
    document.getElementById("codtipo").value = "";
    document.getElementById("nomeTipo").value = "";
    $("#btatualizartipoServico").css("display", "none");
    $("#btexcluirtipoServico").css("display", "none");
    $("#btinserirtipoServico").css("display", "");
}

function procurarTipoServico(acao) {
    $.ajax({
        url: "../control/ProcurarTipoServico.php",
        type: "POST",
        data: $("#fservico").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(acao === false && data === "0"){
                swal("Atenção", "Nada encontrado!", "error");
                document.getElementById("listagemTipoServico").innerHTML = "";
            }else if(data !== "0"){
                document.getElementById("listagemTipoServico").innerHTML = data;
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar Tipo Servico", "Erro causado por:" + errorThrown, "error");
        }
    });
}

procurarTipoServico();