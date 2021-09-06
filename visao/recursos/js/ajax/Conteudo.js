function inserir() {
    if ($("#nome").val() !== null && $("#nome").val() !== "") {
        $.ajax({
            url: "../control/InserirConteudo.php",
            type: "POST",
            data: $("#fconteudo").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Conteudo cadastrado", data.mensagem, "success");
                    procurarConteudo(true);
                } else if (data.situacao === false) {
                    swal("Erro ao cadastrar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else if ($("#nome").val() === null || $("#nome").val() === "") {
        swal("Campo em branco", "Por favor defina um nome para o conteudo!", "error");
    }
}

function atualizar() {
    if ($("#nome").val() !== null && $("#nome").val() !== "") {
        $.ajax({
            url: "../control/AtualizarConteudo.php",
            type: "POST",
            data: $("#fconteudo").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Conteudo atualizado", data.mensagem, "success");
                    procurarConteudo(true);
                } else if (data.situacao === false) {
                    swal("Erro ao atualizar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else if ($("#nome").val() === null || $("#nome").val() === "") {
        swal("Campo em branco", "Por favor defina um nome para o conteudo!", "error");
    }
}

function excluir() {
    if (window.confirm("Deseja realmente excluir esse conteudo?")) {
        if (document.getElementById("codconteudo").value !== null && document.getElementById("codconteudo").value !== "") {
            $.ajax({
                url: "../control/ExcluirConteudo.php",
                type: "POST",
                data: {codconteudo: document.getElementById("codconteudo").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Conteudo excluido", data.mensagem, "success");
                        procurarConteudo(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o conteudo para excluir!", "error");
        }
    }
}

function excluir2(codconteudo) {
    if (window.confirm("Deseja realmente excluir esse conteudo?")) {
        if (codconteudo !== null && codconteudo !== "") {
            $.ajax({
                url: "../control/ExcluirConteudo.php",
                type: "POST",
                data: {codconteudo: codconteudo},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Conteudo excluido", data.mensagem, "success");
                        procurarConteudo(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o conteudo para excluir!", "error");
        }
    }
}

function procurarConteudo(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarConteudo.php",
        type: "POST",
        data: $("#fpconteudo").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(data === ""){
                swal("Atenção", "Nada encontrado de conteudo!", "error");
            }
            document.getElementById("listagem").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function btNovo() {
    location.href = "Conteudo.php";
}

function abreRelatorio() {
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpconteudo").submit();
}

function abreRelatorio2() {
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpconteudo").submit();
}

$(function () {
  
    $("#fconteudo").submit(function(){
        $(".progress").css("visibility", "visible");
    });    
  
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#fconteudo').ajaxForm({
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
                if($("#codconteudo").val() != null && $("#codconteudo").val() != ""){
                    swal("Alteração", data.mensagem, "success");
                }else{
                    swal("Cadastro", data.mensagem, "success");
                }
                if(data.imagem !== null && data.imagem !== ""){
                    $("#imagemCarregada").html("<img width='150' src='../arquivos/"+data.imagem+"'  alt='Imagem conteudo'/>");
                }
                if(data.codnivel !== "3"){
                    procurarConteudo(true);
                }
            }else if(data.situacao === false){
                swal("Erro", data.mensagem, "error");
            }            
        }
    });    
});
