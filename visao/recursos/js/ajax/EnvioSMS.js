/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function envioDireto(){
    $.ajax({
        url : "http://sms.multibr.com/painel/api.ashx?action=sendsms",
        type: "GET",
        data : $("#fenvioSMS").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.status === 1){
                swal("SMS enviado", data.msg, "success");
            }else{
                swal("Erro ao enviar", data.msg, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao enviar", "Erro causado por:" + errorThrown, "error");
        }        
    });
}

function trocaTexto(){
    document.getElementById("msg").value = $("#modelo option:selected").val();
}

function inserirEnvioSMS(){
    $.ajax({
        url : "../control/InserirEnvioSMS.php",
        type: "POST",
        data : $("#fenvioSMS").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("SMS enviado", data.mensagem, "success");
            }else if(data.situacao === false){
                swal("Erro ao enviar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao enviar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function atualizarEnvioSMS(){
    $.ajax({
        url : "../control/AtualizarEnvioSMS.php",
        type: "POST",
        data : $("#fenvioSMS").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Empresa cadastrada", data.mensagem, "success");
                procurarEmpresa(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirEnvioSMS(){
    $.ajax({
        url : "../control/ExcluirEnvioSMS.php",
        type: "POST",
        data : $("#fenvioSMS").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Empresa cadastrada", data.mensagem, "success");
                procurarEmpresa(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function btNovoEnvioSMS(){
    location.href = "EnvioSMS.php";
}


// Shorthand for $( document ).ready()
$(function() {
    $("#modelo").change(function(){
        $("#msg").val($("#modelo option:selected").val());
    });
});