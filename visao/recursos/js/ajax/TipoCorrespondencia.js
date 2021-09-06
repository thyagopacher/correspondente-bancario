function inserirTipo() {
    if ($("#nomeTipo").val() !== null && $("#nomeTipo").val() !== "") {
        $.ajax({
            url: "../control/InserirTipoCorrespondencia.php",
            type: "POST",
            data: $("#ftipocorrespondencia").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Tipo cadastrado", data.mensagem, "success");
                    procurarTipo(true);
                } else if (data.situacao === false) {
                    swal("Erro ao cadastrar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else if ($("#nomeTipo").val() === null || $("#nomeTipo").val() === "") {
        swal("Campo em branco", "Por favor defina um nome para o tipo!", "error");
    }
}

function atualizarTipo() {
    if ($("#nomeTipo").val() !== null && $("#nomeTipo").val() !== "") {
        $.ajax({
            url: "../control/AtualizarTipoCorrespondencia.php",
            type: "POST",
            data: $("#ftipocorrespondencia").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Tipo atualizado", data.mensagem, "success");
                    procurarTipo(true);
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
        console.log("Tipo para ser excluido:" + document.getElementById("codtipo").value);
        if (document.getElementById("codtipo").value !== null && document.getElementById("codtipo").value !== "") {
            $.ajax({
                url: "../control/ExcluirTipoCorrespondencia.php",
                type: "POST",
                data: {codtipo: document.getElementById("codtipo").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tipo excluido", data.mensagem, "success");
                        $("#nomeTipo").val("");
                        procurarTipo(true);
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

function excluirTipo2(codtipo) {
    if (window.confirm("Deseja realmente excluir esse tipo?")) {
        if (codtipo !== null && codtipo !== "") {
            $.ajax({
                url: "../control/ExcluirTipoCorrespondencia.php",
                type: "POST",
                data: {codtipo: codtipo},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tipo excluido", data.mensagem, "success");
                        procurarTipo(true);
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
    $("#btatualizarTipoCorrespondencia").css("display", "");
    $("#btexcluirTipoCorrespondencia").css("display", "");
    $("#btinserirTipoCorrespondencia").css("display", "none");
}

function btNovoTipo() {
    document.getElementById("codtipo").value = "";
    document.getElementById("nomeTipo").value = "";
    $("#btatualizarTipoCorrespondencia").css("display", "none");
    $("#btexcluirTipoCorrespondencia").css("display", "none");
    $("#btinserirTipoCorrespondencia").css("display", "");
}

function procurarTipo(acao) {
    $.ajax({
        url: "../control/ProcurarTipoCorrespondencia.php",
        type: "POST",
        data: {nome: ""},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(acao === false && data === "0"){
                swal("Atenção", "Nada encontrado!", "error");
                document.getElementById("listagemTipoCorrespondencia").innerHTML = "";
            }else if(data !== "0"){
                document.getElementById("listagemTipoCorrespondencia").innerHTML = data;
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

procurarTipo(true);