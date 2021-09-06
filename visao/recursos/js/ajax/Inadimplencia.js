
/**trazendo listagem de inadimplencias cadastrados*/
function procurarInadimplencia(acao) {
    $.ajax({
        url: "../control/ProcurarInadimplencia.php",
        type: "POST",
        data: $("#fpinadimplencia").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if(data == "" && acao == false){
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagem").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function inserirInadimplencia() {
    $.ajax({
        url : "../control/InserirInadimplencia.php",
        type: "POST",
        data : $("#fCadastroinadimplencia").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Inadimplência cadastrado", data.mensagem, "success");
                procurarInadimplencia(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function atualizarInadimplencia() {
    $.ajax({
        url : "../control/AtualizarInadimplencia.php",
        type: "POST",
        data : $("#fCadastroinadimplencia").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Inadimplência atualizado", data.mensagem, "success");
                procurarInadimplencia(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirInadimplencia() {
    $.ajax({
        url : "../control/ExcluirInadimplencia.php",
        type: "POST",
        data : $("#fCadastroinadimplencia").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Inadimplência atualizado", data.mensagem, "success");
                procurarInadimplencia(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}


function abreRelatorio(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fpinadimplencia").submit();
}

function abreRelatorio2(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fpinadimplencia").submit();
}

function btNovoInadimplencia(){
    $("#codinadimplencia").val("");
    $("#apartamento").val("");
    $("#bloco").val("");
    $("#cotacondominio").val("");
    $("#fundoreserva").val("");
    $("#rateioagua").val("");
    $("#juro").val("");
    $("#txextra1").val("");
    $("#txextra2").val("");
    $("#multa").val("");
    $("#dtpagamento").val("");
    $("#periodo").val("");
    $("#dtvencimento").val("");
    $("#valorrecebido").val("");
    $("#valornrecebido").val("");
    $("#btAtualizarInadimplencia").css("display", "none");
    $("#btExcluirInadimplencia").css("display", "none");
    $("#btInserirInadimplencia").css("display", "");
}

/**daqui para baixa responsável pelo ajax de inserir ou atualizar inadimplencia e também pelo upload sem redirecionar página*/
(function () {

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $("#finadimplencia").submit(function(){
        $(".progress").css("visibility", "visible");
    });

    $('#finadimplencia').ajaxForm({
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
                swal("Importação", data.mensagem, "success");
            }else if(data.situacao === false){
                swal("Importação - Erro", data.mensagem, "error");
            }    
        }
    });

})();