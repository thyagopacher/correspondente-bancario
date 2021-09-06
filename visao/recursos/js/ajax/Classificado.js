function inserir() {
    if ($("#titulo").val() !== null && $("#titulo").val() !== "") {
        $.ajax({
            url: "../control/InserirClassificado.php",
            type: "POST",
            data: $("#fclassificado").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Classificado cadastrado", data.mensagem, "success");
                    procurarClassificado(true);
                } else if (data.situacao === false) {
                    swal("Erro ao cadastrar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else if ($("#titulo").val() === null || $("#titulo").val() === "") {
        swal("Campo em branco", "Por favor defina um titulo para o classificado!", "error");
    }
}

function atualizar() {
    if ($("#titulo").val() !== null && $("#titulo").val() !== "") {
        $.ajax({
            url: "../control/AtualizarClassificado.php",
            type: "POST",
            data: $("#fclassificado").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Classificado atualizado", data.mensagem, "success");
                    procurarClassificado(true);
                } else if (data.situacao === false) {
                    swal("Erro ao atualizar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else if ($("#titulo").val() === null || $("#titulo").val() === "") {
        swal("Campo em branco", "Por favor defina um titulo para o classificado!", "error");
    }
}

function excluir() {
    if (window.confirm("Deseja realmente excluir esse classificado?")) {
        if (document.getElementById("codclassificado").value !== null && document.getElementById("codclassificado").value !== "") {
            $.ajax({
                url: "../control/ExcluirClassificado.php",
                type: "POST",
                data: {codclassificado: document.getElementById("codclassificado").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Classificado excluido", data.mensagem, "success");
                        procurarClassificado(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o classificado para excluir!", "error");
        }
    }
}

function excluir2(codclassificado) {
    if (window.confirm("Deseja realmente excluir esse classificado?")) {
        if (codclassificado !== null && codclassificado !== "") {
            $.ajax({
                url: "../control/ExcluirClassificado.php",
                type: "POST",
                data: {codclassificado: codclassificado},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Classificado excluido", data.mensagem, "success");
                        procurarClassificado(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o classificado para excluir!", "error");
        }
    }
}

function procurarClassificado(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarClassificado.php",
        type: "POST",
        data: $("#fpclassificado").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(data !== "0"){
                document.getElementById("listagem").innerHTML = data;
            }else if(acao === false && data === "0"){
                swal("Atenção", "Nada encontrado!", "error");
                document.getElementById("listagem").innerHTML = "";          
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function btNovo() {
    location.href = "Classificado.php";
}

function abreRelatorio() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpclassificado").submit();
}

function abreRelatorio2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpclassificado").submit();
}

$(function () {
    $("#bt_marcar_todos").click(function(){
        if($("#bt_marcar_todos").is(':checked')){
            $(".ch_condominio").prop('checked', true);
        }else{
            $(".ch_condominio").prop('checked', false);
        }
    });
    
    $("#fclassificado").submit(function(){
        $(".progress").css("visibility", "visible");
    });    
  
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fclassificado').ajaxForm({
        beforeSend: function () {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        uploadProgress: function (event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        success: function () {
            var percentVal = '100%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        complete: function (xhr) {
            var data = JSON.parse(xhr.responseText);
            if(data.situacao === true){
                swal("Alteração", data.mensagem, "success");
                if(data.imagem !== null && data.imagem !== ""){
                    $("#imagemCarregada").html("<img width='150' src='../arquivos/"+data.imagem+"'  alt='Imagem classificado'/>");
                }
                if(data.codnivel !== "3"){
                    procurarClassificado(true);
                }
            }else if(data.situacao === false){
                swal("Erro", data.mensagem, "error");
            }            
        }
    });    
});
