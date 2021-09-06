var jadataTablePadrao = 'n';

function botaoNovoReload() {
    location.href = window.location.pathname;
}

function redirecionaLogin() {
    alert("Não pode acessar funcionalidade sem estar logado!");
    location.href = 'Login.php';
}

function expandirMenu() {
    for (var i = 1; i < 100; i++) {
        if (document.getElementById("dd" + i) !== null) {
            document.getElementById("dd" + i).style.display = "";
        }
    }
}

function metaFalta() {
    $.ajax({
        url: "../control/MetaFalta.php",
        type: "POST",
        data: {codempresa: $("#mudaEmpresa option:selected").val()},
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Mudança feita", data.mensagem, "success");
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao mudar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function reenviaSenha() {
    $.ajax({
        url: "../control/ReenviarSenha.php",
        type: "POST",
        data: {email: document.getElementById("email").value},
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
            if (data.situacao === true) {
                swal("Senha enviada", data.mensagem, "success");
            } else if (data.situacao === false) {
                swal("Erro", data.mensagem, "error");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            swal("Erro ao enviar", "Erro causado por:" + errorThrown, "error");
        }
    });
}

function abrirPopUp(url) {
    TINY.box.show({url: url, width: 430, height: 155, opacity: 20, topsplit: 3});
}

function verPopupAvisoMeta() {
    TINY.box.show({url: '../control/AvisoMeta.php', width: 430, height: 300, opacity: 20, topsplit: 3});
}

function dataTablePadrao(id) {
    if (jadataTablePadrao == 'n') {
        $("body").append('<link rel="stylesheet" href="./visao/recursos/css/jquery.dataTables.css"/>')
                .append('<script type="text/javascript" src="./visao/recursos/js/jquery.dataTables.js"></script>');
        jadataTablePadrao = 's';
    }    
    $('#' + id).DataTable({
        "language": {
            "lengthMenu": "Mostrando _MENU_ resultados por pág.",
            "zeroRecords": "Nada encontrado - desculpe",
            "info": "Mostrando pág _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum resultado disponivel",
            "infoFiltered": "(filtered from _MAX_ total resultados)",
            "search": 'Procurar',
            "paginate": {
                "previous": "Pág. ant.",
                "next": "Próx. pág."
            }
        }
    });
    $("input[type=search]").addClass('form-control');
    $("select").addClass('form-control');
}

$(window).load(function () {

    $("body").append('<link rel="stylesheet" href="./visao/recursos/css/ionicons.min.css">')
            .append('<link rel="stylesheet" href="./visao/recursos/css/sweet-alert.css">')
            .append('<script type="text/javascript" charset="utf-8" src="./visao/recursos/js/sweet-alert.min.js"></script>')
            .append('<script type="text/javascript" charset="utf-8" src="./visao/recursos/js/app.min.js"></script>')
            .append('<script type="text/javascript" charset="utf-8" src="./visao/recursos/js/dashboard.js"></script>')
            .append('<script type="text/javascript" charset="utf-8" src="./visao/plugins/fastclick/fastclick.min.js"></script>');

    if ($(".real").length || $(".porcentagem").length || $(".porcentagemMaisCasas").length) {
        $("body").append('<script type="text/javascript" src="/visao/recursos/js/jquery.maskMoney.min.js"></script>');
    }
    if ($(".cep").length || $(".cpf").length || $(".placa").length || $(".telefone").length || $(".data").length) {
        $("body").append('<script type="text/javascript" src="/visao/recursos/js/jquery.mask.min.js"></script>');
    }
    if ($(".real").length) {
        $(".real").maskMoney({showSymbol: true, symbol: "R$", decimal: ",", thousands: ""});
    }
    if ($(".porcentagem").length) {
        $(".porcentagem").maskMoney({showSymbol: true, symbol: "%", decimal: ",", thousands: ""});
    }
    if ($(".porcentagemMaisCasas").length) {
        $(".porcentagemMaisCasas").maskMoney({showSymbol: true, symbol: "%", decimal: ',', thousands: "", precision: 6});
    }
    if ($(".inteiro").length) {
        $('.inteiro').keypress(function (event) {
            var tecla = (window.event) ? event.keyCode : event.which;
            if ((tecla > 47 && tecla < 58))
                return true;
            else {
                if (tecla !== 8)
                    return false;
                else
                    return true;
            }
        });
    }
    if ($(".data").length) {
        $(".data").datepicker({/**usado para input text*/
            dateFormat: 'dd/mm/yy',
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior',
            maxDate: "2099-12-30"
        });
        $(".data").mask("99/99/9999");

    }

    if ($(".cep").length) {
        $("#cep").mask("99.999-999");
    }
    if ($(".cpf").length) {
        $('.cpf').mask("999.999.999-99");
    }
    if ($(".placa").length) {
        $(".placa").mask("aaa-9999");
    }
    if ($(".telefone").length) {
        $(".telefone").mask("(99)99999-999?9");
    }
});

$(function () {

    var textoPiscante = $('.textoPiscante');
    window.setInterval(function () {
        textoPiscante.css('visibility', 'hidden');
        window.setTimeout(function () {
            textoPiscante.css('visibility', 'visible');
        }, 150);
    }, 1 * 1000);

    /**    Deixando o texto em Maiúsculo*/
    $("#mudaEmpresa").change(function () {
        $.ajax({
            url: "../control/MudaEmpresaMaster.php",
            type: "POST",
            data: {codempresa: $("#mudaEmpresa option:selected").val()},
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.situacao === true) {
                    swal("Mudança feita", data.mensagem, "success");
                    location.reload();
                } else if (data.situacao === false) {
                    swal("Erro", data.mensagem, "error");
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao mudar", "Erro causado por:" + errorThrown, "error");
            }
        });
    });
    $("#input_imagem").change(function (e) {
        document.getElementById("input_img_carregada").src = "";
        for (var i = 0; i < e.originalEvent.srcElement.files.length; i++) {
            var file = e.originalEvent.srcElement.files[i];

            var reader = new FileReader();
            reader.onloadend = function () {
                $("#input_img_carregada").attr("src", reader.result);
            }
            reader.readAsDataURL(file);
        }
    });
    if (document.getElementById("tabs") !== null) {
        $("#tabs").tabs();
    }
    $("#cep").blur(function () {
        $.ajax({
            url: "../control/BuscaCep.php",
            type: "POST",
            data: {cep: $("#cep").val()},
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                $("#tipologradouro").val(data.tipologradouro);
                $("#logradouro").val(data.logradouro);
                $("#cidade").val(data.cidade);
                $("#uf").val(data.uf);
                $("#bairro").val(data.bairro);
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro ao excluir", "Erro causado por:" + errorThrown, "error");
            }
        });
    });


});


