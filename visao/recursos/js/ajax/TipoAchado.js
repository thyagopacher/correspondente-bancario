function inserirTipo() {
    if ($("#nomeTipo").val() !== null && $("#nomeTipo").val() !== "") {
        $.ajax({
            url: "../control/InserirTipoAchado.php",
            type: "POST",
            data: $("#ftipoachado").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Tipo Achado cadastrado", data.mensagem, "success");
                    procurarTipoAchado(true);
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
            url: "../control/AtualizarTipoAchado.php",
            type: "POST",
            data: $("#ftipoachado").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Tipo Achado atualizado", data.mensagem, "success");
                    procurarTipoAchado(true);
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
                url: "../control/ExcluirTipoAchado.php",
                type: "POST",
                data: {codtipo: document.getElementById("codtipo").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tipo Achado excluido", data.mensagem, "success");
                        procurarTipoAchado(true);
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
                url: "../control/ExcluirTipoAchado.php",
                type: "POST",
                data: {codtipo: codtipo},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tipo Achado excluido", data.mensagem, "success");
                        procurarTipoAchado(true);
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
    $("#btatualizartipoAchado").css("display", "");
    $("#btexcluirtipoAchado").css("display", "");
    $("#btinserirtipoAchado").css("display", "none");
}

function btNovoTipoAchado() {
    document.getElementById("codtipo").value = "";
    document.getElementById("nomeTipo").value = "";
    $("#btatualizartipoAchado").css("display", "none");
    $("#btexcluirtipoAchado").css("display", "none");
    $("#btinserirtipoAchado").css("display", "");
}

function procurarTipoAchado(acao) {
    $.ajax({
        url: "../control/ProcurarTipoAchado.php",
        type: "POST",
        data: $("#fachado").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(acao === false && data === "0"){
                swal("Atenção", "Nada encontrado!", "error");
                document.getElementById("listagemTipoAchado").innerHTML = "";
            }else if(data !== "0"){
                document.getElementById("listagemTipoAchado").innerHTML = data;
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar Tipo Achado", "Erro causado por:" + errorThrown, "error");
        }
    });
}

procurarTipoAchado();