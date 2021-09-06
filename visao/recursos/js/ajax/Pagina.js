function inserirPagina(){
    if($("#nomePagina").val() !== null && $("#nomePagina").val() !== ""
            && $('#codmodulo option:selected').val() !== null
            && $('#codmodulo option:selected').val() !== ""){
        $.ajax({
            url : "../control/InserirPagina.php",
            type: "POST",
            data : $("#fpagina").serialize(),
            dataType: 'json',
            success: function(data, textStatus, jqXHR){
                if(data.situacao === true){
                    swal("Página cadastrada", data.mensagem, "success");
                    procurarPagina(true);
                }else if(data.situacao === false){
                    swal("Erro ao cadastrar", data.mensagem, "error");
                }
            },error: function (jqXHR, textStatus, errorThrown){
                swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
            }
        });   
    }else if($("#nomePagina").val() === null || $("#nomePagina").val() === ""){
        swal("Campo em branco", "Por favor escolha um nome!", "error");
    }else if($("#titulo").val() === null || $("#titulo").val() === ""){
        swal("Campo em branco", "Por favor escolha um titulo para página!", "error");
    }else if($("#link").val() === null || $("#link").val() === ""){
        swal("Campo em branco", "Por favor escolha um link para página!", "error");
    }else if($('#codmodulo option:selected').val() === null || $('#codmodulo option:selected').val() === ""){
        swal("Campo em branco", "Por favor escolha um módulo para página!", "error");
    }
}

function atualizarPagina(){
    $.ajax({
        url : "../control/AtualizarPagina.php",
        type: "POST",
        data : $("#fpagina").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Página atualizada", data.mensagem, "success");
                procurarPagina(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirPagina(){
    if (window.confirm("Deseja realmente excluir essa pagina?")) {
        if(document.getElementById("codpagina").value !== null && document.getElementById("codpagina").value !== ""){
            $.ajax({
                url : "../control/ExcluirPagina.php",
                type: "POST",
                data : {codpagina: document.getElementById("codpagina").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Página excluida", data.mensagem, "success");
                        procurarPagina(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha a página para excluir!", "error");
        }
    }
}

function excluir2(codpagina){
    if (window.confirm("Deseja realmente excluir essa pagina?")) {
        if(codpagina !== null && codpagina !== ""){
            $.ajax({
                url : "../control/ExcluirPagina.php",
                type: "POST",
                data : {codpagina: codpagina},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Página excluida", data.mensagem, "success");
                        procurarPagina(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor escolha a página para excluir!", "error");
        }
    }
}

function setaEditarPagina(pagina){
    document.getElementById("codpaginaPagina").value = pagina[0];
    document.getElementById("nomePagina").value = pagina[1];
    document.getElementById("titulo").value = pagina[2];
    document.getElementById("link").value = pagina[3];
    document.getElementById("abreaolado").value = pagina[5];
    $("#codmodulo option[value='"+pagina[4]+"']").attr("selected", true);
    $("#btatualizarPagamento").css("display", "");
    $("#btexcluirPagamento").css("display", "");
    $("#btinserirPagamento").css("display", "none");
    $("#tabs").tabs({
        active: 2
    });    
}

function procurarPagina(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarPagina2.php",
        type: "POST",
        data : $("#fppagina").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === "0"){
                swal("Atenção", "Nada encontrado!", "error");
                document.getElementById("listagemPagina").innerHTML = "";
            }else if(data !== "0"){
                document.getElementById("listagemPagina").innerHTML = data;
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

