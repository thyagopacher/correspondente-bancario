function inserirInformativo() {
    if ($("#assunto").val() !== null && $("#assunto").val() !== "") {
        $.ajax({
            url: "../control/InserirInformativo.php",
            type: "POST",
            data: $("#finformativo").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Informativo cadastrado", data.mensagem, "success");
                    procurarInformativo(true);
                } else if (data.situacao === false) {
                    swal("Erro ao cadastrar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else {
        swal("Campo em branco", "Por favor defina um assunto para o informativo!", "error");
    }
}

function atualizarInformativo() {
    if ($("#assunto").val() !== null && $("#assunto").val() !== "") {
        $.ajax({
            url: "../control/AtualizarInformativo.php",
            type: "POST",
            data: $("#finformativo").serialize(),
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Informativo atualizado", data.mensagem, "success");
                    procurarInformativo(true);
                } else if (data.situacao === false) {
                    swal("Erro ao atualizar", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
            }
        });
    } else {
        swal("Campo em branco", "Por favor defina um assunto para o informativo!", "error");
    }
}

function excluirInformativo() {
    if (window.confirm("Deseja realmente excluir esse informativo?")) {
        if (document.getElementById("codinformativo").value !== null && document.getElementById("codinformativo").value !== "") {
            $.ajax({
                url: "../control/ExcluirInformativo.php",
                type: "POST",
                data: {codinformativo: document.getElementById("codinformativo").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Informativo excluido", data.mensagem, "success");
                        procurarInformativo(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor escolha um informativo para excluir!", "error");
        }
    }
}


function excluir2Informativo(codinformativo){
    swal({   
        title: "Confirma exclusão?",   
        text: "Você não poderá mais visualizar as informações desse informativo!",   
        type: "warning", showCancelButton: true,   
        confirmButtonColor: "#DD6B55", confirmButtonText: "Sim, exclua ele!",   
        closeOnConfirm: false, closeOnCancel: true
    }, function(isConfirm){   
        if (isConfirm) {     
            if (codinformativo !== null && codinformativo !== "") {
                $.ajax({
                    url: "../control/ExcluirInformativo.php",
                    type: "POST",
                    data: {codinformativo: codinformativo},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Informativo excluido", data.mensagem, "success");
                            procurarInformativo(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao excluir", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor escolha um informativo para excluir!", "error");
            }
        }  
    });      
}

function btNovoInformativo() {
    location.href = "Informativo.php";
}

/**trazendo listagem de informativos cadastrados*/
function procurarInformativo(acao) {
    $.ajax({
        url: "../control/ProcurarInformativo.php",
        type: "POST",
        data: $("#fpinformativo").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(acao === false && data === "0"){
                swal("Atenção", "Nada encontrado!", "error");
                document.getElementById("listagem").innerHTML = "";            
            }else if(data !== "0"){
                document.getElementById("listagem").innerHTML = data;
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function abreRelatorioInformativo(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpinformativo").submit();
}

function abreRelatorioInformativo2(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpinformativo").submit();
}

/**daqui para baixa responsável pelo ajax de inserir ou atualizar importacao e também pelo upload sem redirecionar página*/
(function () {

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $("#finformativo").submit(function(){
        $(".progress").css("visibility", "visible");
    });

    $("#paraquem1").change(function(){
        $(".morador").css("display", "none");
        $(".grupo").css("display", "");
        $("#codmorador").attr("required", false);
        $("#grupo").attr("required", true);
    });
    
    $("#paraquem2").change(function(){
        $(".morador").css("display", "");
        $(".grupo").css("display", "none");
        $("#codmorador").attr("required", true);
        $("#grupo").attr("required", false);        
    });

    $('#finformativo').ajaxForm({
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
            bar.width(percentVal);
            percent.html(percentVal);
        },
        complete: function (xhr) {
            var data = JSON.parse(xhr.responseText);
            if(data.situacao === true){
                swal("Informativo", data.mensagem, "success");
            }else if(data.situacao === false){
                swal("Informativo - Erro", data.mensagem, "error");
            }    
        }
    });

})();