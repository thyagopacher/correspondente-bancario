function inserirVoto() {
    $.ajax({
        url: "../control/InserirVotoEnquete.php",
        type: "POST",
        data: $("#fvoto").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Voto cadastrado", data.mensagem, "success");
                procurar(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirVoto() {
    if (window.confirm("Deseja realmente excluir esse voto?")) {
        if (document.getElementById("codvoto").value !== null && document.getElementById("codvoto").value !== "") {
            $.ajax({
                url: "../control/ExcluirVotoEnquete.php",
                type: "POST",
                data: {codvoto: document.getElementById("codvoto").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Voto excluido", data.mensagem, "success");
                        procurar(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluido", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o voto para excluir!", "error");
        }
    }
}

function excluirVoto2(codvoto) {
    if (window.confirm("Deseja realmente excluir esse voto?")) {
        if (codvoto !== null && codvoto !== "") {
            $.ajax({
                url: "../control/ExcluirVotoEnquete.php",
                type: "POST",
                data: {codvoto: codvoto},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Voto excluido", data.mensagem, "success");
                        procurar(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluido", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o voto para excluir!", "error");
        }
    }
}

function setaEditar(voto) {
    document.getElementById("codvoto").value = voto[0];
    document.getElementById("voto").value = voto[1];
    document.getElementById("morador").value = voto[2];
    document.getElementById("pulseira").value = voto[3];
    document.getElementById("codmorador").value = voto[5];
    document.getElementById("numero").value = voto[6];
    document.getElementById("data").value = voto[7];
    document.getElementById("hora").value = voto[8];
    $("#btatualizarVoto").css("display", "");
    $("#btexcluirVoto").css("display", "");
    $("#btnovoVoto").css("display", "");
    $("#btinserirVoto").css("display", "none");
}

function procurarVoto() {
    $.ajax({
        url: "../control/ProcurarVotoEnquete.php",
        type: "POST",
        data: $("#fpvoto").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            document.getElementById("listagem").innerHTML = data;
            document.getElementById("html").value = data;
            document.getElementById("html2").value = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function btNovo() {
    location.href = "Piscina.php";
}
