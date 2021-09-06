function inserirRegistro() {
    $.ajax({
        url: "../control/InserirRegistro.php",
        type: "POST",
        data: $("#fregistro").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Registro cadastrado", data.mensagem, "success");
                location.reload();
            } else if (data.situacao === false) {
                swal("Erro ao cadastrar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao cadastrar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function limparRegistro(){
    location.reload();
}

function atualizarRegistro() {
    $.ajax({
        url: "../control/AtualizarRegistro.php",
        type: "POST",
        data: $("#fregistro").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Registro atualizado", data.mensagem, "success");
                procurarRegistro(true);
            } else if (data.situacao === false) {
                swal("Erro ao atualizar", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao atualizar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function excluirRegistro() {
    if (window.confirm("Deseja realmente excluir essa registro?")) {
        if (document.getElementById("codregistro").value !== null && document.getElementById("codregistro").value !== "") {
            $.ajax({
                url: "../control/ExcluirRegistro.php",
                type: "POST",
                data: {codregistro: document.getElementById("codregistro").value},
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.situacao === true) {
                        swal("Registro excluido", data.mensagem, "success");
                        procurarRegistro(true);
                    } else if (data.situacao === false) {
                        swal("Erro ao excluir", data.mensagem, "error");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
                }
            });
        } else {
            swal("Campo em branco", "Por favor defina registro para poder excluir!", "error");
        }
    }
}

function excluir2Registro(codregistro) {
    swal({
        title: "Confirma exclusão?",
        text: "Você não poderá mais visualizar as informações desse registro!",
        type: "warning", showCancelButton: true,
        confirmButtonColor: "#DD6B55", confirmButtonText: "Sim, exclua ele!",
        closeOnConfirm: false, closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (codregistro !== null && codregistro !== "") {
                $.ajax({
                    url: "../control/ExcluirRegistro.php",
                    type: "POST",
                    data: {codregistro: codregistro},
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao === true) {
                            swal("Registro excluido", data.mensagem, "success");
                            procurarRegistro(true);
                        } else if (data.situacao === false) {
                            swal("Erro ao cadastrar", data.mensagem, "error");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Erro", "Erro ocasionado por:" + errorThrown, "error");
                    }
                });
            } else {
                swal("Campo em branco", "Por favor defina registro para poder excluir!", "error");
            }
        }
    });
}

function procurarRegistro(acao) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarRegistro.php",
        type: "POST",
        data: $("#fregistro").serialize(),
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            if (acao === false && data == "") {
                swal("Atenção", "Nada encontrado!", "error");
            }
            document.getElementById("listagem").innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar registro", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function procurarProcedimento() {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarProcedimento.php",
        type: "POST",
        data: $("#fregistro").serialize(),
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            document.getElementById("procedimentos").innerHTML = data.procedimentos1;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar registro", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function procurarProcedimento3(linha) {
    if($("#procedimento" + linha).val().length > 2){
        $("#carregando").show();
        $.ajax({
            url: "../control/ProcurarProcedimento.php",
            type: "POST",
            data: {nome: document.getElementById("procedimento" + linha).value},
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                document.getElementById("procedimentos").innerHTML = data.procedimentos1;
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao procurar registro", "Erro causado por:" + errorThrown, "error");
            }
        });
        $("#carregando").hide();
    }
}
        
function procurarProcedimentoLetra(letra) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ProcurarProcedimento.php",
        type: "POST",
        data: {letra: letra},
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            document.getElementById("procedimentos").innerHTML = data.procedimentos1;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao procurar registro", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function abreRelatorioRegistro() {
    document.getElementById("fpregistro").action = "../control/ProcurarRegistroRelatorio.php";
    document.getElementById("tipoRegistro").value = "pdf";
    document.getElementById("fpregistro").submit();
}

function abreRelatorio2Registro() {
    document.getElementById("fpregistro").action = "../control/ProcuraRegistroRelatorio2.php";
    document.getElementById("tipoRegistro").value = "xls";
    document.getElementById("fpregistro").submit();
}

function btNovoRegistro() {
    location.href = "Registro.php";
}

function defineRegistro(procedimento) {
    var qtdProcedimento   = parseInt($("#qtdProcedimento").val());
    var vlProcedimento    = parseFloat(procedimento[4]);
    var vlHonorario       = 0.0;
    var valorFinal        = 0.0;
    if($("#procedimento" + qtdProcedimento).val() !== null && $("#procedimento" + qtdProcedimento).val() !== "" && $("#procedimento" + qtdProcedimento).val().length > 4 && document.getElementById("busca_procedimento" + procedimento[2]).checked){
        adicionarNovoProcedimento();
        qtdProcedimento++;
    }
    $("#procedimento" + qtdProcedimento).val(procedimento[3]);
    $("#codigo" + qtdProcedimento).val(procedimento[1]);
    $("#porte" + qtdProcedimento).val(procedimento[0]);
    $("#codigo_procedimento" + qtdProcedimento).val(procedimento[5]);
    $("#valorProcedimento" + qtdProcedimento).val(vlProcedimento.toFixed(2).replace(".", ","));
    if(qtdProcedimento === 1){
        vlHonorario = vlProcedimento;
    }else if(qtdProcedimento > 1){
        if($("#incisao" + qtdProcedimento).val() === "m"){
            vlHonorario = vlProcedimento * 0.5;
        }else if($("#incisao" + qtdProcedimento).val() === "d"){
            vlHonorario = vlProcedimento * 0.7;
        }
    }
    if($("#tipo" + qtdProcedimento).val() === "u"){
        vlHonorario = vlHonorario + (vlProcedimento * 0.3);
    }
    $("#valorHonorario" + qtdProcedimento).val(vlHonorario.toFixed(2).replace(".", ","));
    var valorHonorario2 = document.getElementsByName('valorHonorario[]');
    var aux             = valorHonorario2.length;
    var i               = 0;
    for (i = 0; i < aux; i++) { 
        var linhaIteracao = i + 1;
        valorFinal += parseFloat($("#valorHonorario" + linhaIteracao).val().replace(",", "."));
    }
    $("#valor").val(valorFinal.toFixed(2).replace(".", ","));    
}

function calculoLinha(linhaProcedimento){
    if($("#valorProcedimento" + linhaProcedimento).val() !== null && $("#valorProcedimento" + linhaProcedimento).val() !== ""){
        var valorFinal        = 0.0;
        var valorHonorario    = 0.0;
        var valorProcedimento = parseFloat($("#valorProcedimento" + linhaProcedimento).val().replace(",", "."));
        valorHonorario = parseFloat($("#valorProcedimento" + linhaProcedimento).val().replace(",", "."));
        if(linhaProcedimento === 1){
            if($("#tipo" + linhaProcedimento).val() === "u"){
                valorHonorario = valorHonorario + (valorProcedimento * 0.3);
            }else if($("#tipo" + linhaProcedimento).val() === "e"){
                valorHonorario = valorProcedimento;
            }    
        }
        var qtdProcedimento = parseInt($("#qtdProcedimento").val());
        if(linhaProcedimento > 1){
            if($("#incisao" + linhaProcedimento).val() === "m"){
                valorHonorario = (valorProcedimento * 0.5);
            }else if($("#incisao" + linhaProcedimento).val() === "d"){
                valorHonorario = (valorProcedimento * 0.7);
            }
            if($("#tipo" + linhaProcedimento).val() === "u"){
                valorHonorario = valorHonorario + (valorHonorario * 0.3);
            }else if($("#tipo" + linhaProcedimento).val() === "e"){
                valorHonorario = valorHonorario - (valorHonorario * 0.3);
            }         
            if($("#bilateral" + linhaProcedimento).val() === "b"){
                valorHonorario = (valorProcedimento * 0.7) + valorHonorario;
            }    
        }
        $("#valorHonorario" + linhaProcedimento).val(valorHonorario.toFixed(2).replace(".", ","));
        var valorHonorario2 = document.getElementsByName('valorHonorario[]');
        var aux             = valorHonorario2.length;
        var i               = 0;
        for (i = 0; i < aux; i++) { 
            var linhaIteracao = i + 1;
            valorFinal += parseFloat($("#valorHonorario" + linhaIteracao).val().replace(",", "."));
        }
        $("#valor").val(valorFinal.toFixed(2).replace(".", ","));    
    }
}

function valorBase(porte) {
    var valor = 0.0;
    switch (porte) {
        case 1:
            valor = 249.7;
            break;
        case 2:
            valor = 249.7;
            break;
        case 3:
            valor = 249.7;
            break;
        case 4:
            valor = 369.18;
            break;
        case 5:
            valor = 571.07;
            break;
        case 6:
            valor = 796.89;
            break;
    }
    return valor;
}

function adicionarNovoProcedimento(){
    var qtdProcedimento = parseInt($("#qtdProcedimento").val()) + 1;
    $("#qtdProcedimento").val(qtdProcedimento);
    console.log("Adicionando nova linha na tabela");
    
    var linhaNova  = '<p style="margin: 0px 0px 5px 10px;">';
    linhaNova     += '<input type="hidden" name="codigo_procedimento[]" id="codigo_procedimento'+qtdProcedimento+'">';
    linhaNova     += '<input type="search" onkeyup="procurarProcedimento3('+qtdProcedimento+')" style="width: 130px;" name="procedimento[]" id="procedimento'+qtdProcedimento+'" placeholder="Procedimento" title="Procedimento"/>';
    linhaNova     += '<input type="text" style="width: 130px;  margin-left: 10px;" name="codigo[]" id="codigo'+qtdProcedimento+'" size="50" maxlength="250" placeholder="código" title="Código">';
    linhaNova     += '<input type="text" style="width: 120px;  margin-left: 10px;" name="porte[]" id="porte'+qtdProcedimento+'" size="50" maxlength="250" placeholder="Porte" title="Porte">';
    linhaNova     += '<input style="width: 90px;  margin-left: 7px;" type="text" name="valorProcedimento[]" id="valorProcedimento'+qtdProcedimento+'" class="real" value="" placeholder="integral" title="Valor integral procedimento"/>';
    linhaNova     += '<select onchange="calculoLinha('+qtdProcedimento+');" style="width: 135px; margin-left: 10px;" name="tipo[]" id="tipo'+qtdProcedimento+'" title="Tipo de cirurgia"><option value="e">eletiva</option><option value="u">urgência</option></select>';
    linhaNova     += '<select onchange="calculoLinha('+qtdProcedimento+');" style="width: 135px; margin-left: 10px;" name="incisao[]" id="incisao'+qtdProcedimento+'" title="Incisão de cirurgia"><option value="m">Mesma via</option><option value="d">Diferente via</option></select>';
    linhaNova     += '<select onchange="calculoLinha('+qtdProcedimento+');" style="width: 135px; margin-left: 10px;" name="bilateral[]" id="bilateral'+qtdProcedimento+'" title="bilateral"><option value="u">Unilateral</option><option value="b">Bilateral</option></select>';
    linhaNova     += '<input style="width: 90px;  margin-left: 7px;" type="text" name="valorHonorario[]" id="valorHonorario'+qtdProcedimento+'" class="real" value="" placeholder="calculado" title="Valor calculado procedimento"/>';
    linhaNova     += '<a class="botao" href="javascript: adicionarNovoProcedimento();" id="adicionarNovoProcedimento" title="Adicionar novo procedimento">+</a>';
    linhaNova     += '</p>';
    document.getElementById("procedimentosAdicionar").innerHTML = linhasAntigasProcedimento() + linhaNova;    
}

function linhasAntigasProcedimento(){
    var procedimento = document.getElementsByName('procedimento[]');
    var i           = 1;
    var aux         = procedimento.length;
    var conc        = "";
    for(i = 0; i < aux; i++){
        var linhaIteracao = i + 1;
        if(procedimento[i].value !== null && procedimento[i].value !== ""){
            var linhaNova  = '<p style="margin: 0px 0px 5px 10px;">';
            linhaNova     += '<input type="hidden" name="codigo_procedimento[]" id="codigo_procedimento'+linhaIteracao+'" value="'+document.getElementById("codigo_procedimento" + linhaIteracao).value+'">';
            linhaNova     += '<input type="search" onkeyup="procurarProcedimento3('+linhaIteracao+')" style="width: 130px;" name="procedimento[]" id="procedimento'+linhaIteracao+'" placeholder="Procedimento" title="Procedimento" value="'+document.getElementById("procedimento"+linhaIteracao).value+'"/>';
            linhaNova     += '<input type="text" style="width: 130px;  margin-left: 10px;" name="codigo[]" id="codigo'+linhaIteracao+'" size="50" maxlength="250" placeholder="código" title="Código" value="'+document.getElementById("codigo"+linhaIteracao).value+'">';
            linhaNova     += '<input type="text" style="width: 120px;  margin-left: 10px;" name="porte[]" id="porte'+linhaIteracao+'" size="50" maxlength="250" placeholder="Porte" title="Porte" value="'+document.getElementById("porte"+linhaIteracao).value+'">';
            linhaNova     += '<input style="width: 90px;  margin-left: 7px;" type="text" name="valorProcedimento[]" id="valorProcedimento'+linhaIteracao+'" class="real" value="'+document.getElementById("valorProcedimento"+linhaIteracao).value+'" placeholder="integral" title="Valor integral procedimento"/>';
            if(document.getElementById("tipo"+linhaIteracao).value === "u"){
                linhaNova += '<select onchange="calculoLinha('+linhaIteracao+');" style="width: 135px; margin-left: 10px;" name="tipo[]" id="tipo'+linhaIteracao+'" title="Tipo de cirurgia"><option value="e">eletiva</option><option selected value="u">urgência</option></select>';
            }else{
                linhaNova += '<select onchange="calculoLinha('+linhaIteracao+');" style="width: 135px; margin-left: 10px;" name="tipo[]" id="tipo'+linhaIteracao+'" title="Tipo de cirurgia"><option selected value="e">eletiva</option><option value="u">urgência</option></select>';
            }
            if(document.getElementById("incisao"+linhaIteracao).value === "m"){
                linhaNova += '<select onchange="calculoLinha('+linhaIteracao+');" style="width: 135px; margin-left: 10px;" name="incisao[]" id="incisao'+linhaIteracao+'" title="Incisão de cirurgia"><option selected value="m">Mesma via</option><option value="d">Diferente via</option></select>';
            }else if(document.getElementById("incisao"+linhaIteracao).value === "d"){
                linhaNova += '<select onchange="calculoLinha('+linhaIteracao+');" style="width: 135px; margin-left: 10px;" name="incisao[]" id="incisao'+linhaIteracao+'" title="Incisão de cirurgia"><option value="m">Mesma via</option><option selected value="d">Diferente via</option></select>';
            }
            if(document.getElementById("bilateral"+linhaIteracao).value === "u"){
                linhaNova += '<select onchange="calculoLinha('+linhaIteracao+');" style="width: 135px; margin-left: 10px;" name="bilateral[]" id="bilateral'+linhaIteracao+'" title="bilateral"><option selected value="u">Unilateral</option><option value="b">Bilateral</option></select>';
            }else{
                linhaNova += '<select onchange="calculoLinha('+linhaIteracao+');" style="width: 135px; margin-left: 10px;" name="bilateral[]" id="bilateral'+linhaIteracao+'" title="bilateral"><option value="u">Unilateral</option><option selected value="b">Bilateral</option></select>';
            }
            
            linhaNova     += '<input style="width: 90px;  margin-left: 7px;" type="text" name="valorHonorario[]" id="valorHonorario'+linhaIteracao+'" class="real" value="'+document.getElementById("valorHonorario"+linhaIteracao).value+'" placeholder="calculado" title="Valor calculado procedimento"/>';
            linhaNova     += '<a class="botao" href="javascript: adicionarNovoProcedimento();" id="adicionarNovoProcedimento" title="Adicionar novo procedimento">+</a>';
            linhaNova     += '</p>';                          
            conc          += linhaNova;
        }
    }
    return conc;
}

$(function () {
    $("#busca_procedimento").keyup(function () {
        if($("#busca_procedimento").val().length > 2){
            procurarProcedimento();
        }
    });    
    $("#codigo").keyup(function () {
        if($("#codigo").val().length > 2){
            procurarProcedimento();
        }
    });    
    $("#tipo").change(function () {
        if ($("#tipo option:selected").val() == "urgência") {
            var valorAnt = parseFloat($("#valor").val());
            if (isNaN(valorAnt)) {
                valorAnt = 0;
            }
            valorAnt = valorAnt + (valorAnt * 0.3);
            $("#valor").val(valorAnt.toFixed(2).replace(".", ","));
        }
    });

});

procurarProcedimento();