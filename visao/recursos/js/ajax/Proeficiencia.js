function inserirProeficiencia(){
    $.ajax({
        url : "../control/InserirProeficiencia.php",
        type: "POST",
        data : $("#fproeficiencia").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Proeficiencia cadastrado", data.mensagem, "success");
                procurarProeficiencia(true);
            }else if(data.situacao === false){
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });  
}

function atualizarProeficiencia(){
    $.ajax({
        url : "../control/AtualizarProeficiencia.php",
        type: "POST",
        data : $("#fproeficiencia").serialize(),
        dataType: 'json',
        success: function(data, textStatus, jqXHR){
            if(data.situacao === true){
                swal("Proeficiencia atualizado", data.mensagem, "success");
                procurarProeficiencia(true);
            }else if(data.situacao === false){
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });      
}

function excluirProeficiencia(){
    if (window.confirm("Deseja realmente excluir essa proeficiencia?")) {
        console.log("Proeficiencia para ser excluido:" + document.getElementById("codproeficiencia").value);
        if(document.getElementById("codproeficiencia").value !== null && document.getElementById("codproeficiencia").value !== ""){
            $.ajax({
                url : "../control/ExcluirProeficiencia.php",
                type: "POST",
                data : {codproeficiencia: document.getElementById("codproeficiencia").value},
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    if(data.situacao === true){
                        swal("Proeficiencia excluido", data.mensagem, "success");
                        procurarProeficiencia(true);
                    }else if(data.situacao === false){
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                },error: function (jqXHR, textStatus, errorThrown){
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });          
        }else{
            swal("Campo em branco", "Por favor defina proeficiencia para poder excluir!", "error");
        }
    }
}

function excluir2Proeficiencia(codproeficiencia){
    swal({   
        title: "Confirma exclusão?",   
        text: "Você não poderá mais visualizar as informações dessa proeficiencia!",   
        type: "warning", showCancelButton: true,   
        confirmButtonColor: "#DD6B55", confirmButtonText: "Sim, exclua ele!",   
        closeOnConfirm: false, closeOnCancel: true
    }, function(isConfirm){   
        if (isConfirm) {     
            if(codproeficiencia !== null && codproeficiencia !== ""){
                $.ajax({
                    url : "../control/ExcluirProeficiencia.php",
                    type: "POST",
                    data : {codproeficiencia: codproeficiencia},
                    dataType: 'json',
                    success: function(data, textStatus, jqXHR){
                        if(data.situacao === true){
                            swal("Proeficiencia excluido", data.mensagem, "success");
                            procurarProeficiencia(true);
                        }else if(data.situacao === false){
                            swal("Erro ao cadastrar", data.mensagem, "error");
                        }
                    },error: function (jqXHR, textStatus, errorThrown){
                        swal("Erro", "Erro ocasionado por:" + errorThrown, "error");
                    }
                });          
            }else{
                swal("Campo em branco", "Por favor defina proeficiencia para poder excluir!", "error");
            }
        }  
    });      
}

function procurarProeficiencia(acao){
    $("#carregando").show();
    $.ajax({
        url : "../control/ProcurarProeficiencia.php",
        type: "POST",
        data : $("#fpproeficiencia").serialize(),
        dataType: 'text',
        success: function(data, textStatus, jqXHR){
            if(acao === false && data === ""){
                swal("Atenção", "Nada encontrado de proeficiencias!", "error");
            }
            document.getElementById("listagemProeficiencia").innerHTML = data;
        },error: function (jqXHR, textStatus, errorThrown){
            swal("Erro ao procurar proeficiencia", "Erro causado por:" + errorThrown, "error");
        }
    });  
    $("#carregando").hide();
}

function abreRelatorioProeficiencia(){
    document.getElementById("tipo").value = "pdf";
    document.getElementById("fproeficiencia").submit();
}

function abreRelatorio2Proeficiencia(){
    document.getElementById("tipo").value = "xls";
    document.getElementById("fproeficiencia").submit();
}

function btNovoProeficiencia(){
    location.href="Proeficiencia.php";
} 


function linhasAntigasProeficiencia(){
    var remuneracao     = document.getElementsByName('remuneracao[]');
    var i           = 1;
    var aux         = remuneracao.length;
    var conc        = "";
    for(i = 0; i < aux; i++){
        var linhaIteracao = i;
        if(remuneracao[i].value !== null && remuneracao[i].value !== ""){
            var linhaNova  = "<div style='margin-top: 10px;' id='tabela_proeficiencia"+linhaIteracao+"'>";
            linhaNova     += '<table class="tabela_formulario">';
            linhaNova     += '<tr>';
            linhaNova     += '<td>META BASE <input  type="text" name="valor[]" id="valor'+linhaIteracao+'" value="'+document.getElementById("valor" + linhaIteracao).value+'"/></td>';
            linhaNova     += '<td>INDICE REFERENCIA <input  type="text" name="margem[]" id="margem'+linhaIteracao+'" value="'+document.getElementById("margem" + linhaIteracao).value+'"/></td>';                
            linhaNova     += '<td>BONIFICAÇÃO <input  type="text" name="remuneracao[]" id="remuneracao'+linhaIteracao+'" value="'+document.getElementById("remuneracao" + linhaIteracao).value+'"></td>';
            linhaNova     += '<td>DT. VIGÊNCIA INI. <input  type="date" name="dtvigenciaIni[]" id="dtvigenciaIni'+linhaIteracao+'" value="'+document.getElementById("dtvigenciaIni" + linhaIteracao).value+'"></td>';
            linhaNova     += '<td>DT. VIGÊNCIA <input  type="date" name="dtvigencia[]" id="dtvigencia'+linhaIteracao+'" value="'+document.getElementById("dtvigencia" + linhaIteracao).value+'"></td>';
            linhaNova     += '<td><a href="javascript: adicionarLinhaProeficiencia('+linhaIteracao+');" id="botao_adicionar_tabela_proeficiencia'+linhaIteracao+'" class="botao" title="Adicionar nova linha">+</a></td>';
            linhaNova     += '<td><a href="javascript: removerLinhaProeficiencia('+linhaIteracao+')" class="botao" title="Retirar linha">-</a></td>';
            linhaNova     += '</tr>';          
            linhaNova     += '</table>';
            linhaNova     += '</div>';
            conc          += linhaNova;
        }
    }
    return conc;
}

function adicionarLinhaProeficiencia(linha2){
    var linha                 = linha2 + 1;
    var outrasLinhas          = '';
    outrasLinhas     += '<table class="tabela_formulario">';
    outrasLinhas     += '<tr>';
    outrasLinhas     += '<td>META BASE <input class="real" type="text" name="valor[]" id="valor'+linha+'" value=""/></td>';
    outrasLinhas     += '<td>INDICE REFERENCIA <input type="text" name="margem[]" id="margem'+linha+'" value=""/></td>';     
    outrasLinhas     += '<td>BONIFICAÇÃO <input class="real" type="text" name="remuneracao[]" id="remuneracao'+linha+'" value=""></td>';
    outrasLinhas     += '<td>DT. VIGÊNCIA INI. <input type="date" name="dtvigenciaIni[]" id="dtvigenciaIni'+linha+'" value=""></td>';
    outrasLinhas     += '<td>DT. VIGÊNCIA <input type="date" name="dtvigencia[]" id="dtvigencia'+linha+'" value=""></td>';
    outrasLinhas     += '<td><a href="javascript: adicionarLinhaProeficiencia('+linha+');" id="botao_adicionar_tabela_proeficiencia'+linha+'" class="botao" title="Adicionar nova linha">+</a></td>';
    outrasLinhas     += '<td><a href="javascript: removerLinhaProeficiencia('+linha+')" class="botao" title="Retirar linha">-</a></td>';
    outrasLinhas     += '</table>';
    var linhasAntigasProeficiencia1 = linhasAntigasProeficiencia();
    document.getElementById("tabela_proeficiencia").innerHTML = linhasAntigasProeficiencia1 + "<div style='margin-top: 10px;' id='tabela_proeficiencia"+linha+"'>" + outrasLinhas + '</div>'; 
    $("#botao_adicionar_tabela_proeficiencia" + linha2).css("display", "none");
}

function removerLinhaProeficiencia(linha2){
    $("#tabela_proeficiencia" + linha2).css("display", "none");
}