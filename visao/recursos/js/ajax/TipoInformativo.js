function inserirTipo() {
    if ($("#nomeTipo").val() !== null && $("#nomeTipo").val() !== "") {
        $.ajax({
            url: "../control/InserirTipoInformativo.php",
            type: "POST",
            data: $("#ftipoinformativo").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Tipo informativo cadastrado", data.mensagem, "success");
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
            url: "../control/AtualizarTipoInformativo.php",
            type: "POST",
            data: $("#ftipoinformativo").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Tipo informativo atualizado", data.mensagem, "success");
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
    if (window.confirm("Deseja realmente excluir esse tipo de informativo?")) {
        if (document.getElementById("codtipo").value !== null && document.getElementById("codtipo").value !== "") {
            $.ajax({
                url: "../control/ExcluirTipoInformativo.php",
                type: "POST",
                data: {codtipo: document.getElementById("codtipo").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tipo informativo excluido", data.mensagem, "success");
                        procurarTipo(true);
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

function excluir2(codtipo) {
    if (window.confirm("Deseja realmente excluir esse tipo de informativo?")) {
        if (codtipo !== null && codtipo !== "") {
            $.ajax({
                url: "../control/ExcluirTipoInformativo.php",
                type: "POST",
                data: {codtipo: codtipo},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Tipo informativo excluido", data.mensagem, "success");
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
    document.getElementById("codtipo2").value = tipo[0];
    document.getElementById("nomeTipo").value = tipo[1];
    $("#btatualizarTipoInformativo").css("display", "");
    $("#btexcluirTipoInformativo").css("display", "");
    $("#btinserirTipoInformativo").css("display", "none");
}

function btNovoTipo() {
    document.getElementById("codtipo").value = "";
    document.getElementById("nomeTipo").value = "";
    document.getElementById("corTipo").value = "";
    $("#btatualizarTipoInformativo").css("display", "none");
    $("#btexcluirTipoInformativo").css("display", "none");
    $("#btinserirTipoInformativo").css("display", "");
}

function procurarTipo(acao) {
    $.ajax({
        url: "../control/ProcurarTipoInformativo.php",
        type: "POST",
        data: {nome: ""},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(acao === false && data === ""){
                swal("Erro", "Nada encontrado!!!", "error");
            }
            document.getElementById("listagemTipoInformativo").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

procurarTipo(true);