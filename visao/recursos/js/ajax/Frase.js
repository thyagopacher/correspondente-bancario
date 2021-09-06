function inserirFrase() {
    $.ajax({
        url: "../control/InserirFrase.php",
        type: "POST",
        data: $("#ffrase").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Frase cadastrado", data.mensagem, "success");
                procurarFrase(true);
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarFrase() {
    $.ajax({
        url: "../control/AtualizarFrase.php",
        type: "POST",
        data: $("#ffrase").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Frase atualizado", data.mensagem, "success");
                procurarFrase(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirFrase() {
    if (window.confirm("Deseja realmente excluir esse frase?")) {
        if (document.getElementById("codfrase").value !== null && document.getElementById("codfrase").value !== "") {
            $.ajax({
                url: "../control/ExcluirFrase.php",
                type: "POST",
                data: {codfrase: document.getElementById("codfrase").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Frase excluido", data.mensagem, "success");
                        procurarFrase(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha o frase para excluir!", "error");
        }
    }
}

function excluir2Frase(codfrase) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações dessa frase!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codfrase !== null && codfrase !== "") {
                $.ajax({
                    url: "../control/ExcluirFrase.php",
                    type: "POST",
                    data: {codfrase: codfrase},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Frase excluido", data.mensagem, "success");
                            procurarFrase(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha o frase para excluir!", "error");
            }
        }
    });
}

function setaEditar(frase) {
    document.getElementById("codfrase").value = frase[0];
    document.getElementById("nome").value = frase[1];
    document.getElementById("telefone").value = frase[2];
    document.getElementById("email").value = frase[3];
    document.getElementById("senha").value = frase[4];
    document.getElementById("celular").value = frase[5];
    document.getElementById("imagemCarregada").innerHTML = "<img src='../arquivos/" + frase[6] + "' alt='Imagem da frase' title='Imagem da frase'/>";
    $("#btatualizarFrase").css("display", "");
    $("#btexcluirFrase").css("display", "");
    $("#btnovoFrase").css("display", "");
    $("#btinserirFrase").css("display", "none");
    $("#codnivel option[value='" + frase[7] + "']").attr("selected", true);
}

function btNovoFrase() {
    location.href = "Frase.php";
}

function procurarFrase(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarFrase.php",
        type: "POST",
        data: $("#fPfrase").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data === "") {
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagemFrase").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioFrase() {
    document.getElementById("fPfrase").submit();
}

function abreRelatorioFrase2() {
    document.getElementById("fPfrase2").submit();
}

function visualizaPopup(){
//    var codfrase = "";
//    if(document.getElementById("codfrase") != null){
//        codfrase = document.getElementById("codfrase").value;
//    }
//    TINY.box.show({url: '../control/AvisoMeta.php?codfrase=' + codfrase, width: 500, height: 470, opacity: 20, topsplit: 3});
}

$(function () {
    $("#popup").change(function(){
        if($("#popup option:selected").val() == "s"){
            $(".frase_popup").css("display", "");
        }else{
            $(".frase_popup").css("display", "none");
        }
    });
    $("#chave").change(function(){
        insereTexto($("#chave option:selected").val(), "textoFrase");
    });

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#ffrase').ajaxForm({
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
            if (data.situacao === true) {
                swal("Alteração", data.mensagem, "success");
                if (data.imagem !== null && data.imagem !== "") {
                    $("#imagemCarregada").html("<img width='150' src='../arquivos/" + data.imagem + "'  alt='Imagem usuário'/>");
                }
                procurarFrase(true);
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }
    });
});
