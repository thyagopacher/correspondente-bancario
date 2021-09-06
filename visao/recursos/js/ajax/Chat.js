function iniciarChat(){
    if($("#logado").val() !== null && $("#logado").val() !== ""){
        TINY.box.show({url: '../control/iniciarChat.php', post: 'logado=' + $("#logado").val(), width: 430, height: 155, opacity: 20, topsplit: 3});
        setTimeout('verificaEnviados()', 2000);
    }else{
        swal("Erro ao abrir chat", "Não pode abrir o chat sem escolher o funcionário logado!!!", "error");
    }
}

function iniciarChat2(codlogado){
    if(codlogado !== null && codlogado !== ""){
        TINY.box.show({url: '../control/iniciarChat.php', post: 'logado=' + codlogado, width: 430, height: 155, opacity: 20, topsplit: 3});
        setTimeout('verificaEnviados()', 2000);
    }else{
        swal("Erro ao abrir chat", "Não pode abrir o chat sem escolher o funcionário logado!!!", "error");
    }
}



function finalizaChat(){
    $.ajax({
        url : "../control/FinalizaChat.php",
        type: "POST",
        dataType: 'json', 
        success: function(data, textStatus, jqXHR){
            if(data.situacao != null && data.situacao === false){
                swal("Erro", data.mensagem, "error");
            }else{
                window.parent.TINY.box.hide();
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });     
}

function enviarConversa(){
    $.ajax({
        url : "../control/EnviarConversa.php",
        type: "POST",
        data : $("#finiciarChat").serialize(),
        dataType: 'json', 
        success: function(data, textStatus, jqXHR){
            if(data.situacao != null && data.situacao === false){
                swal("Erro", data.mensagem, "error");
            }else{
                $("#texto").val("");
                
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });     
}

/**verifica se chamaram a pessoa logada para o chat*/
function verificaSeMeChamaram(){
    $.ajax({
        url : "../control/VerificaSeMeChamaram.php",
        type: "POST",
        dataType: 'json', 
        success: function(data, textStatus, jqXHR){
            if(data != null && data.situacao != null && data.situacao === true){
                iniciarChat2(data.logado);
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao verificar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function verificaEnviados(){
    if(document.getElementById("chat_enviados") !== null){
        $.ajax({
            url : "../control/ChatEnviados.php",
            type: "POST",
            data : {logado: document.getElementById("logadoChat").value},
            dataType: 'text', 
            success: function(data, textStatus, jqXHR){
                if(data != "" && document.getElementById("chat_enviados") != null){
                    document.getElementById("chat_enviados").innerHTML = data;
                }
            },error: function (jqXHR, textStatus, errorThrown){
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });
        
    }
    setTimeout('verificaEnviados()', 1000);
}

function logadosEmpresa(codcondominio){
    if(document.getElementById("logado") !== null){
        $.ajax({
            url : "../control/LogadosEmpresa.php",
            type: "POST",
            data : {condominio: codcondominio},
            dataType: 'text', 
            success: function(data, textStatus, jqXHR){
                if(data != ""){
                    document.getElementById("logado").innerHTML = data;
                }
            },error: function (jqXHR, textStatus, errorThrown){
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });     
    }
}



$(function(){
    verificaSeMeChamaram();
    $("#condominio").change(function(){
        logadosEmpresa($("#condominio option:selected").val());
    });
});