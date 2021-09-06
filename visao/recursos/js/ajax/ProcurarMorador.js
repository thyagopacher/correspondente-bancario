$(function () {
    $("#bloco").change(function () {
        comboApartamento("", $("#bloco option:selected").val(), "", document.getElementById("apartamento"));
        comboMorador("", $("#bloco option:selected").val(), "", document.getElementById("codmorador"));
    });

    $("#apartamento").change(function () {
        comboMorador("", "", $("#apartamento option:selected").val(), document.getElementById("codmorador"));
        comboBloco("", "", $("#apartamento option:selected").val(), document.getElementById("bloco"));
    });

    $("#codmorador").change(function () {
        comboApartamento($("#codmorador option:selected").val(), "", "", document.getElementById("apartamento"));
        comboBloco($("#codmorador option:selected").val(), "", "", document.getElementById("bloco"));
    });
    
    $("#bloco2").change(function () {
        comboApartamento("", $("#bloco2 option:selected").val(), "", document.getElementById("apartamento2"));
        comboMorador("", $("#bloco2 option:selected").val(), "", document.getElementById("codmorador2"));
    });

    $("#apartamento2").change(function () {
        comboMorador("", "", $("#apartamento2 option:selected").val(), document.getElementById("codmorador2"));
        comboBloco("", "", $("#apartamento2 option:selected").val(), document.getElementById("bloco2"));
    });

    $("#codmorador2").change(function () {
        comboApartamento($("#codmorador2 option:selected").val(), "", "", document.getElementById("apartamento2"));
        comboBloco($("#codmorador2 option:selected").val(), "", "", document.getElementById("bloco2"));
    });

    $("#pbloco").change(function () {
        comboApartamento("", $("#pbloco option:selected").val(), "", document.getElementById("papartamento"));
        comboMorador("", $("#pbloco option:selected").val(), "", document.getElementById("pcodmorador"));
    });

    $("#papartamento").change(function () {
        comboMorador("", "", $("#papartamento option:selected").val(), document.getElementById("pcodmorador"));
        comboBloco("", "", $("#papartamento option:selected").val(), document.getElementById("pbloco"));
    });

    $("#pcodmorador").change(function () {
        comboApartamento($("#pcodmorador option:selected").val(), "", "", document.getElementById("papartamento"));
        comboBloco($("#pcodmorador option:selected").val(), "", "", document.getElementById("pbloco"));
    });
});

function comboApartamento(codpessoa, bloco, apartamento, componente) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ComboApartamento.php",
        type: "POST",
        data: {codmorador: codpessoa, bloco: bloco, apartamento: apartamento},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            componente.innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function comboMorador(codpessoa, bloco, apartamento, componente) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ComboMorador.php",
        type: "POST",
        data: {codmorador: codpessoa, bloco: bloco, apartamento: apartamento},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            componente.innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}

function comboBloco(codpessoa, bloco, apartamento, componente) {
    $("#carregando").show();
    $.ajax({
        url: "../control/ComboBloco.php",
        type: "POST",
        data: {codmorador: codpessoa, bloco: bloco, apartamento: apartamento},
        dataType: 'text',
        success: function (data, textStatus, jqXHR) {
            componente.innerHTML = data;
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
        }
    });
    $("#carregando").hide();
}
