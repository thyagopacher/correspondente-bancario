/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/
//window.localStorage.removeItem('listagemAchado');

$(function () {

    "use strict";

    //Make the dashboard widgets sortable Using jquery UI
    $(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    });
    $(".connectedSortable .box-header, .connectedSortable .nav-tabs-custom").css("cursor", "move");

    //jQuery UI sortable for the todo list
    $(".todo-list").sortable({
        placeholder: "sort-highlight",
        handle: ".handle",
        forcePlaceholderSize: true,
        zIndex: 999999
    });

    //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();

    $('.daterange').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
    }, function (start, end) {
        window.alert("You chose: " + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    });

    /* jQueryKnob */
    $(".knob").knob();

    //jvectormap data
    var visitorsData = {
        "US": 398, //USA
        "SA": 400, //Saudi Arabia
        "CA": 1000, //Canada
        "DE": 500, //Germany
        "FR": 760, //France
        "CN": 300, //China
        "AU": 700, //Australia
        "BR": 600, //Brazil
        "IN": 800, //India
        "GB": 320, //Great Britain
        "RU": 3000 //Russia
    };
    //World map by jvectormap
    $('#world-map').vectorMap({
        map: 'world_mill_en',
        backgroundColor: "transparent",
        regionStyle: {
            initial: {
                fill: '#e4e4e4',
                "fill-opacity": 1,
                stroke: 'none',
                "stroke-width": 0,
                "stroke-opacity": 1
            }
        },
        series: {
            regions: [{
                    values: visitorsData,
                    scale: ["#92c1dc", "#ebf4f9"],
                    normalizeFunction: 'polynomial'
                }]
        },
        onRegionLabelShow: function (e, el, code) {
            if (typeof visitorsData[code] != "undefined")
                el.html(el.html() + ': ' + visitorsData[code] + ' new visitors');
        }
    });

    //Sparkline charts
    var myvalues = [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021];
    $('#sparkline-1').sparkline(myvalues, {
        type: 'line',
        lineColor: '#92c1dc',
        fillColor: "#ebf4f9",
        height: '50',
        width: '80'
    });
    myvalues = [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921];
    $('#sparkline-2').sparkline(myvalues, {
        type: 'line',
        lineColor: '#92c1dc',
        fillColor: "#ebf4f9",
        height: '50',
        width: '80'
    });
    myvalues = [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21];
    $('#sparkline-3').sparkline(myvalues, {
        type: 'line',
        lineColor: '#92c1dc',
        fillColor: "#ebf4f9",
        height: '50',
        width: '80'
    });

    //The Calender
    $("#calendar").datepicker({format: 'dd/mm/yyyy', language: 'pt-BR'});

    //SLIMSCROLL FOR CHAT WIDGET
    $('#chat-box').slimScroll({
        height: '250px'
    });

    /* Morris.js Charts */
    // Sales chart

    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart2);
    google.charts.setOnLoadCallback(drawChart);

    var chart1, data1, options1;
    function drawChart2() {
        var jsonData = $.ajax({
            url: "../control/ProducaoMes.php",
            dataType: "json",
            async: false,
            type: "POST"
        }).responseText;
        jsonData = JSON.parse(jsonData.replace(/'/g, '"'));

        data1 = google.visualization.arrayToDataTable(jsonData);
        var formatter = new google.visualization.NumberFormat(
                {prefix: 'R$ ', negativeColor: 'red', negativeParens: true, decimalSymbol: ',', groupingSymbol: '.'});
        formatter.format(data1, 1); // Apply formatter to second column
        formatter.format(data1, 2);
        options1 = {
            title: 'Produção mensal',
            hAxis: {title: 'Mês', titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0},
            height: 300,
            width: 500
        };

        chart1 = new google.visualization.AreaChart(document.getElementById('grafico_mes'));
        chart1.draw(data1, options1);
    }

    function drawChart() {
        var jsonData = $.ajax({
            url: "../control/ProducaoDiaria.php",
            dataType: "json",
            async: false,
            type: "POST"
        }).responseText;
        jsonData = JSON.parse(jsonData.replace(/'/g, '"'));
        var data = google.visualization.arrayToDataTable(jsonData);
        var formatter = new google.visualization.NumberFormat(
                {prefix: 'R$ ', negativeColor: 'red', negativeParens: true, decimalSymbol: ',', groupingSymbol: '.'});
        formatter.format(data, 1); // Apply formatter to second column
        formatter.format(data, 2);
        var options = {
            title: 'Produção diária',
            hAxis: {title: 'Dia', titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('sales-chart'));
        chart.draw(data, options);
    }



    //Fix for charts under tabs
    $('.box ul.nav a').on('shown.bs.tab', function () {
        drawChart2();
        drawChart();
    });

    /* The todo list plugin */
    $(".todo-list").todolist({
        onCheck: function (ele) {
            window.console.log("The element has been checked");
            return ele;
        },
        onUncheck: function (ele) {
            window.console.log("The element has been unchecked");
            return ele;
        }
    });

});
