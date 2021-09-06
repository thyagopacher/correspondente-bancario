function inserir(){
    $.ajax({
        url : "../control/InserirConsumoAgua.php",
        type: "POST",
        data : $("#fconsumo").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Consumo cadastrado", data.mensagem, "success");
                location.reload();
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizar(){
    $.ajax({
        url : "../control/AtualizarConsumoAgua.php",
        type: "POST",
        data : $("#fconsumo").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Consumo atualizado", data.mensagem, "success");
                procurarConsumo(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluir(){
    if (window.confirm("Deseja realmente excluir essa consumo de água?")) {
        if(document.getElementById("codconsumo").value !== null && document.getElementById("codconsumo").value !== ""){
            $.ajax({
                url : "../control/ExcluirConsumoAgua.php",
                type: "POST",
                data : {codconsumo: document.getElementById("codconsumo").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Consumo excluida", data.mensagem, "success");
                        procurarConsumo(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha uma consumo de água para excluir!", "error");
        }
    }
}

function excluir2(codconsumo){
    if (window.confirm("Deseja realmente excluir essa consumo de água?")) {
        if(codconsumo !== null && codconsumo !== ""){
            $.ajax({
                url : "../control/ExcluirConsumo.php",
                type: "POST",
                data : {codconsumo: codconsumo},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Consumo excluida", data.mensagem, "success");
                        procurarConsumo(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha uma consumo de água para excluir!", "error");
        }
    }
}

function procurarConsumo(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarConsumoAgua.php",
        type: "POST",
        data : $("#fpconsumo").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagemConsumo").innerHTML = data;      
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function procuraMorador() {
    $.ajax({
        url: "../control/ProcurarMorador.php",
        type: "POST",
        data: {nome: document.getElementById("morador").value},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            document.getElementById("listagemMorador").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function confirmaMorador(pessoa){
    alert("Morador confirmado, falta cadastrar para efetivar consumo de água!");
    document.getElementById("codmorador").value = pessoa[0];
    document.getElementById("morador").value = pessoa[1];
}


$(function () {
    $("#morador").keyup(function () {
        if($("#morador").val().length > 2){
            procuraMorador();
        }
    });
});


function btNovo(){
    location.href="ConsumoAgua.php";
}

