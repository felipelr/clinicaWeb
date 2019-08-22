/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function myFunction() {
    window.print();
}

var arrayCompareceu = [];
var arrayNaoCompareceu = [];
var arrayAdiado = [];
var arrayRecebimento = [];
var arrayDespesa = [];
var startTimeline = 0;

$(document).ready(function () {
    jQuery("#btnMapa").click(function () {
        jQuery("#myModalMapa").modal("show");
        if (!jQuery("#mapa-content").hasClass("clicked")) {
            jQuery("#mapa-content").addClass("clicked");
            jQuery("#mapa-content").append("<iframe src='" + $NOME_APLICACAO + "/index/mapa' style='height: 500px; width: 100%; border: none; overflow: hidden;'></iframe>");
        }
    });

    $("#consultas-hoje").click(function () {
        $("#titulo-tabela").html("Consultas hoje");
        $.ajax({
            url: $NOME_APLICACAO + "/index/ajax_consultas_hoje",
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = $.parseJSON(data);
                    if (json.result) {
                        var html = '';
                        $.each(json.dados, function (index, value) {
                            if (value.p.nome != undefined && value.p.nome != null) {
                                html += '<tr><td>' + value.po.nome + " " + value.po.sobrenome + '</td> <td>' + value.p.nome + " " + value.p.sobrenome + '</td> <td>' + value.p.email + '</td> <td class="text-center">' + value.e.data_inicio + ' - ' + value.e.data_fim + '</td></tr>';
                            } else if(value.ae.nome != undefined && value.ae.nome != null){
                                html += '<tr><td>' + value.po.nome + " " + value.po.sobrenome + '</td> <td>' + value.ae.nome + " " + value.ae.sobrenome + '</td> <td>' + value.ae.email + '</td> <td class="text-center">' + value.e.data_inicio + ' - ' + value.e.data_fim + '</td></tr>';
                            }
                        });
                        $('#tabela-consultas tbody').html(html);
                        $("#myModalConsultas").modal('show');
                    } else {
                        $('#tabela-consultas tbody').html('<tr><td colspan="4"><span class="text-center" style="width: 100%;">Não há registros</span></td></tr>');
                        $("#myModalConsultas").modal('show');
                    }
                } else {

                }
            },
            error: function () {

            }
        });
    });

    $("#consultas-amanha").click(function () {
        $("#titulo-tabela").html("Consultas amanhã");
        $.ajax({
            url: $NOME_APLICACAO + "/index/ajax_consultas_amanha",
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                if (data !== null) {
                    var json = $.parseJSON(data);
                    if (json.result) {
                        var html = '';
                        $.each(json.dados, function (index, value) {
                            if (value.p.nome != undefined && value.p.nome != null) {
                                html += '<tr><td>' + value.po.nome + " " + value.po.sobrenome + '</td> <td>' + value.p.nome + " " + value.p.sobrenome + '</td> <td>' + value.p.email + '</td> <td class="text-center">' + value.e.data_inicio + ' - ' + value.e.data_fim + '</td></tr>';
                            } else if(value.ae.nome != undefined && value.ae.nome != null){
                                html += '<tr><td>' + value.po.nome + " " + value.po.sobrenome + '</td> <td>' + value.ae.nome + " " + value.ae.sobrenome + '</td> <td>' + value.ae.email + '</td> <td class="text-center">' + value.e.data_inicio + ' - ' + value.e.data_fim + '</td></tr>';
                            }
                        });
                        $('#tabela-consultas tbody').html(html);
                        $("#myModalConsultas").modal('show');
                    } else {
                        $('#tabela-consultas tbody').html('<tr><td colspan="4"><span class="text-center" style="width: 100%;">Não há registros</span></td></tr>');
                        $("#myModalConsultas").modal('show');
                    }
                } else {

                }
            },
            error: function () {

            }
        });
    });

//    $("#consultas-essa-semana").click(function () {
//        $("#titulo-tabela").html("Consultas esta semana");
//        $.ajax({
//            url: $NOME_APLICACAO + "/index/ajax_consultas_essa_semana",
//            type: 'POST',
//            success: function (data, textStatus, jqXHR) {
//                if (data !== null) {
//                    var json = $.parseJSON(data);
//                    if (json.result) {
//                        var html = '';
//                        $.each(json.dados, function (index, value) {
//                            if (value.p.nome != undefined && value.p.nome != null) {
//                                html += '<tr><td>' + value.po.nome + " " + value.po.sobrenome + '</td> <td>' + value.p.nome + " " + value.p.sobrenome + '</td> <td>' + value.p.email + '</td> <td class="text-center">' + value.e.data_inicio + ' - ' + value.e.data_fim + '</td></tr>';
//                            } else if(value.ae.nome != undefined && value.ae.nome != null){
//                                html += '<tr><td>' + value.po.nome + " " + value.po.sobrenome + '</td> <td>' + value.ae.nome + " " + value.ae.sobrenome + '</td> <td>' + value.ae.email + '</td> <td class="text-center">' + value.e.data_inicio + ' - ' + value.e.data_fim + '</td></tr>';
//                            }
//                        });
//                        $('#tabela-consultas tbody').html(html);
//                        $("#myModalConsultas").modal('show');
//                    } else {
//                        $('#tabela-consultas tbody').html('<tr><td colspan="4"><span class="text-center" style="width: 100%;">Não há registros</span></td></tr>');
//                        $("#myModalConsultas").modal('show');
//                    }
//                } else {
//
//                }
//            },
//            error: function () {
//
//            }
//        });
//    });
    
    $("#contratos-renovacao").click(function () {
        window.location.href = $NOME_APLICACAO + "/renovacao_contrato/";
    });

    $(".pointer-hover").click(function () {
        $(this).next("table").toggle("slow");
    });

    //despesas
    $.get($NOME_APLICACAO + "/index/ajax_financeiro_diario", function (data) {
        if (data !== null) {
            var json = $.parseJSON(data);
            if (json.result) {
                var html = '';
                var total = 'R$ 0,00';
                $.each(json.dados, function (index, value) {
                    var classDisabled = "";
                    var styleBackground = "";
                    if (parseInt(value.financeiro.pago) === 1) {
                        classDisabled = "disabled";
                    }
                    if (parseInt(value.financeiro.vencida) === 1) {
                        styleBackground = "background-color: #f2dede;";
                    }
                    if (value.financeiro.valor_total !== null && value.financeiro.valor_total !== undefined) {
                        total = value.financeiro.valor_total;
                    }
                    html += '<tr style="' + styleBackground + '"><td>' + value.despesa.descricao + '</td> <td>' + value.favorecido.nome + '</td> <td class="text-center">' + value.financeiro.data_vencimento + '</td> <td class="text-center">' + value.financeiro.valor + '</td> <td><a href="' + $NOME_APLICACAO + '/despesa/gestao/' + value.despesa.iddespesa + '" class="btn btn-success"><i class="fa fa-edit" title="Gerenciar Despesa"></i></a></td> </tr>';
                });
                $("#total-pagar-hoje").html(total);
                $('#table-diario tbody').html(html);
            }
        }
    });

    $.get($NOME_APLICACAO + "/index/ajax_financeiro_semanal", function (data) {
        if (data !== null) {
            var json = $.parseJSON(data);
            if (json.result) {
                var html = '';
                var total = 'R$ 0,00';
                $.each(json.dados, function (index, value) {
                    var classDisabled = "";
                    var styleBackground = "";
                    if (parseInt(value.financeiro.pago) === 1) {
                        classDisabled = "disabled";
                    }
                    if (parseInt(value.financeiro.vencida) === 1) {
                        styleBackground = "background-color: #f2dede;";
                    }
                    if (value.financeiro.valor_total !== null && value.financeiro.valor_total !== undefined) {
                        total = value.financeiro.valor_total;
                    }
                    html += '<tr style="' + styleBackground + '"><td>' + value.despesa.descricao + '</td> <td>' + value.favorecido.nome + '</td> <td class="text-center">' + value.financeiro.data_vencimento + '</td> <td class="text-center">' + value.financeiro.valor + '</td> <td><a href="' + $NOME_APLICACAO + '/despesa/gestao/' + value.despesa.iddespesa + '" class="btn btn-success"><i class="fa fa-edit" title="Gerenciar Despesa"></i></a></td> </tr>';
                });
                $("#total-pagar-semana").html(total);
                $('#table-semanal tbody').html(html);
            }
        }
    });

    $.get($NOME_APLICACAO + "/index/ajax_financeiro_mensal", function (data) {
        if (data !== null) {
            var json = $.parseJSON(data);
            if (json.result) {
                var html = '';
                var total = 'R$ 0,00';
                $.each(json.dados, function (index, value) {
                    var classDisabled = "";
                    var styleBackground = "";
                    if (parseInt(value.financeiro.pago) === 1) {
                        classDisabled = "disabled";
                    }
                    if (parseInt(value.financeiro.vencida) === 1) {
                        styleBackground = "background-color: #f2dede;";
                    }
                    if (value.financeiro.valor_total !== null && value.financeiro.valor_total !== undefined) {
                        total = value.financeiro.valor_total;
                    }
                    html += '<tr style="' + styleBackground + '"><td>' + value.despesa.descricao + '</td> <td>' + value.favorecido.nome + '</td> <td class="text-center">' + value.financeiro.data_vencimento + '</td> <td class="text-center">' + value.financeiro.valor + '</td> <td><a href="' + $NOME_APLICACAO + '/despesa/gestao/' + value.despesa.iddespesa + '" class="btn btn-success"><i class="fa fa-edit" title="Gerenciar Despesa"></i></a></td> </tr>';
                });
                $("#total-pagar-mes").html(total);
                $('#table-mensal tbody').html(html);
            }
        }
    });

    //recebimentos
    $.get($NOME_APLICACAO + "/index/ajax_financeiro_diario_recebimento", function (data) {
        if (data !== null) {
            var json = $.parseJSON(data);
            if (json.result) {
                var html = '';
                var total = 'R$ 0,00';
                $.each(json.dados, function (index, value) {
                    var classDisabled = "";
                    var styleBackground = "";
                    if (parseInt(value.financeiro.pago) === 1) {
                        classDisabled = "disabled";
                    }
                    if (parseInt(value.financeiro.vencida) === 1) {
                        styleBackground = "background-color: #f2dede;";
                    }
                    if (value.financeiro.valor_total !== null && value.financeiro.valor_total !== undefined) {
                        total = value.financeiro.valor_total;
                    }
                    html += '<tr style="' + styleBackground + '"><td>' + value.recebimento.descricao + '</td> <td>' + value.paciente.nome + ' ' + value.paciente.sobrenome + '</td> <td class="text-center">' + value.financeiro.data_vencimento + '</td> <td class="text-center">' + value.financeiro.valor + '</td> <td><a href="' + $NOME_APLICACAO + '/recebimento/gestao/' + value.recebimento.idrecebimento + '" class="btn btn-success"><i class="fa fa-edit" title="Gerenciar Recebimento"></i></a></td> </tr>';
                });
                $("#total-receber-hoje").html(total);
                $('#table-receber-diario tbody').html(html);
            }
        }
    });

    $.get($NOME_APLICACAO + "/index/ajax_financeiro_semanal_recebimento", function (data) {
        if (data !== null) {
            var json = $.parseJSON(data);
            if (json.result) {
                var html = '';
                var total = 'R$ 0,00';
                $.each(json.dados, function (index, value) {
                    var classDisabled = "";
                    var styleBackground = "";
                    if (parseInt(value.financeiro.pago) === 1) {
                        classDisabled = "disabled";
                    }
                    if (parseInt(value.financeiro.vencida) === 1) {
                        styleBackground = "background-color: #f2dede;";
                    }
                    if (value.financeiro.valor_total !== null && value.financeiro.valor_total !== undefined) {
                        total = value.financeiro.valor_total;
                    }
                    html += '<tr style="' + styleBackground + '"><td>' + value.recebimento.descricao + '</td> <td>' + value.paciente.nome + ' ' + value.paciente.sobrenome + '</td> <td class="text-center">' + value.financeiro.data_vencimento + '</td> <td class="text-center">' + value.financeiro.valor + '</td> <td><a href="' + $NOME_APLICACAO + '/recebimento/gestao/' + value.recebimento.idrecebimento + '" class="btn btn-success"><i class="fa fa-edit" title="Gerenciar Recebimento"></i></a></td> </tr>';
                });
                $("#total-receber-semana").html(total);
                $('#table-receber-semanal tbody').html(html);
            }
        }
    });

    $.get($NOME_APLICACAO + "/index/ajax_financeiro_mensal_recebimento", function (data) {
        if (data !== null) {
            var json = $.parseJSON(data);
            if (json.result) {
                var html = '';
                var total = 'R$ 0,00';
                $.each(json.dados, function (index, value) {
                    var classDisabled = "";
                    var styleBackground = "";
                    if (parseInt(value.financeiro.pago) === 1) {
                        classDisabled = "disabled";
                    }
                    if (parseInt(value.financeiro.vencida) === 1) {
                        styleBackground = "background-color: #f2dede;";
                    }
                    if (value.financeiro.valor_total !== null && value.financeiro.valor_total !== undefined) {
                        total = value.financeiro.valor_total;
                    }
                    html += '<tr style="' + styleBackground + '"><td>' + value.recebimento.descricao + '</td> <td>' + value.paciente.nome + ' ' + value.paciente.sobrenome + '</td> <td class="text-center">' + value.financeiro.data_vencimento + '</td> <td class="text-center">' + value.financeiro.valor + '</td> <td><a href="' + $NOME_APLICACAO + '/recebimento/gestao/' + value.recebimento.idrecebimento + '" class="btn btn-success"><i class="fa fa-edit" title="Gerenciar Recebimento"></i></a></td> </tr>';
                });
                $("#total-receber-mes").html(total);
                $('#table-receber-mensal tbody').html(html);
            }
        }
    });

    $("#aniversariantes-mes").click(function () {
        $.get($NOME_APLICACAO + "/index/ajax_aniversariantes", function (data) {
            if (data !== null) {
                var json = $.parseJSON(data);
                if (json.result) {
                    var html = '';
                    var total = json.total;
                    $.each(json.pacientes, function (index, value) {
                        html += '<tr><td>' + value.paciente.nome + " " + value.paciente.sobrenome + '</td> <td class="text-center">' + value.paciente.data_nascimento + '</td> </tr>';
                    });
                    $('#table-aniversariantes-mes tbody').html(html);
                    $("#myModalAniversariantes").modal("show");
                } else {
                    html += '<tr><td colspan="2">Não há registros</td></tr>';
                    $('#table-aniversariantes-mes tbody').html(html);
                    $("#myModalAniversariantes").modal("show");
                }
            }
        });
    });

    carregarGraficoEventos();
    carregarGraficoFinanceiro();
    carregarGraficoContratos();
    carregarGraficoPlanoSessao();
    carregarTimeline(startTimeline);
});

function carregarGraficoEventos() {
    //-------------
    //- AREA CHART -
    //-------------
    $.get($NOME_APLICACAO + "/index/ajax_grafico_eventos", function (data) {
        if (data !== null) {
            var dadosEventos = jQuery.parseJSON(data);
            arrayCompareceu = dadosEventos.compareceu;
            arrayNaoCompareceu = dadosEventos.naocompareceu;
            arrayAdiado = dadosEventos.adiado;
        }

        // Get context with jQuery - using jQuery's .get() method.
        var areaChartCanvas = $("#areaChartEventos").get(0).getContext("2d");
        var areaChart = new Chart(areaChartCanvas);

        var areaChartData = {
            labels: ["JAN", "FEV", "MAR", "ABR", "MAI", "JUN", "JUL", "AGO", "SET", "OUT", "NOV", "DEZ"],
            datasets: [
                {
                    label: "Compareceu",
                    fillColor: "rgba(0,166,90,1)",
                    strokeColor: "rgba(0,166,90,1)",
                    pointColor: "rgba(0,166,90,1)",
                    pointStrokeColor: "rgba(0,166,90,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(0,166,90,1)",
                    data: arrayCompareceu
                },
                {
                    label: "Não Compareceu",
                    fillColor: "rgba(221,75,57,1)",
                    strokeColor: "rgba(221,75,57,1)",
                    pointColor: "rgba(221,75,57,1)",
                    pointStrokeColor: "rgba(221,75,57,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(221,75,57,1)",
                    data: arrayNaoCompareceu
                },
                {
                    label: "Adiado",
                    fillColor: "rgba(243,156,18,1)",
                    strokeColor: "rgba(243,156,18,1)",
                    pointColor: "rgba(243,156,18,1)",
                    pointStrokeColor: "rgba(243,156,18,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(243,156,18,1)",
                    data: arrayAdiado
                }
            ]
        };

        var areaChartOptions = {
            //Boolean - If we should show the scale at all
            showScale: true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: false,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - Whether the line is curved between points
            bezierCurve: true,
            //Number - Tension of the bezier curve between points
            bezierCurveTension: 0.3,
            //Boolean - Whether to show a dot for each point
            pointDot: true,
            //Number - Radius of each point dot in pixels
            pointDotRadius: 4,
            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth: 1,
            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius: 20,
            //Boolean - Whether to show a stroke for datasets
            datasetStroke: true,
            //Number - Pixel width of dataset stroke
            datasetStrokeWidth: 2,
            //Boolean - Whether to fill the dataset with a color
            datasetFill: false,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: true,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true
        };

        //Create the line chart
        areaChart.Line(areaChartData, areaChartOptions);
    });

}

function carregarGraficoFinanceiro() {
    //-------------
    //- BAR CHART -
    //-------------
    $.get($NOME_APLICACAO + "/index/ajax_grafico_financeiro", function (data) {
        if (data !== null) {
            var dadosFinanceiro = jQuery.parseJSON(data);
            arrayRecebimento = dadosFinanceiro.recebimentos;
            arrayDespesa = dadosFinanceiro.despesas;
        }
        var barChartCanvas = $("#barChartFinanceiro").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = {
            labels: ["JAN", "FEV", "MAR", "ABR", "MAI", "JUN", "JUL", "AGO", "SET", "OUT", "NOV", "DEZ"],
            datasets: [
                {
                    label: "Compareceu",
                    fillColor: "rgba(0,166,90,1)",
                    strokeColor: "rgba(0,166,90,1)",
                    pointColor: "rgba(0,166,90,1)",
                    pointStrokeColor: "rgba(0,166,90,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(0,166,90,1)",
                    data: arrayRecebimento
                },
                {
                    label: "Não Compareceu",
                    fillColor: "rgba(221,75,57,1)",
                    strokeColor: "rgba(221,75,57,1)",
                    pointColor: "rgba(221,75,57,1)",
                    pointStrokeColor: "rgba(221,75,57,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(221,75,57,1)",
                    data: arrayDespesa
                }
            ]
        };

        var barChartOptions = {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero: true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: true,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - If there is a stroke on each bar
            barShowStroke: true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth: 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing: 5,
            //Number - Spacing between data sets within X values
            barDatasetSpacing: 1,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
            //Boolean - whether to make the chart responsive
            responsive: true,
            maintainAspectRatio: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= ' R$ ' + Number(value).toFixed(2).replace('.', ',') %>",
            multiTooltipTemplate: "<%= ' R$ ' + Number(value).toFixed(2).replace('.', ',')%>",
            scaleLabel: "<%= ' R$ ' + Number(value).toFixed(2).replace('.', ',') %>"
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);
    });

}

function carregarTimeline(start) {
    $.get($NOME_APLICACAO + "/index/ajax_timeline_consultas?start=" + start, function (data) {
        if (data !== null) {
            var dadosTimeline = jQuery.parseJSON(data);
            var html = '<ul class="timeline">';
            var dataTimeline = '';
            for (var i = 0; i < dadosTimeline.length; i++) {
                if (dataTimeline === '') {
                    dataTimeline = dadosTimeline[i].e.data_timeline;
                    html += '<li class="time-label"><span class="bg-yellow">' + dadosTimeline[i].e.data_timeline + '</span></li>';
                    html += '<li>';
                    html += '<i class="fa fa-calendar bg-blue"></i>';
                    html += '<div class="timeline-item">';
                    html += '<span class="time"><i class="fa fa-clock-o"></i> ' + dadosTimeline[i].e.data_inicio + '</span>';
                    html += '<h3 class="timeline-header"><a href="#">Profissional: </a>' + dadosTimeline[i].po.nome + ' ' + dadosTimeline[i].po.sobrenome + '</h3>';
                    html += '<div class="timeline-body">';
                    html += '<p><b>Consulta:</b> ' + dadosTimeline[i].e.descricao + '</p>';
                    html += '<p><b>Paciente:</b> ' + dadosTimeline[i].p.nome + ' ' + dadosTimeline[i].p.sobrenome + '</p>';
                    html += '<p><b>E-mail:</b> ' + dadosTimeline[i].p.email + '</p>';
                    html += '</div>';
                    html += '</div>';
                    html += '</li>';
                    dataTimeline = dadosTimeline[i].e.data_timeline;
                } else {
                    while (i < dadosTimeline.length && dadosTimeline[i].e.data_timeline === dataTimeline) {
                        html += '<li>';
                        html += '<i class="fa fa-calendar bg-blue"></i>';
                        html += '<div class="timeline-item">';
                        html += '<span class="time"><i class="fa fa-clock-o"></i> ' + dadosTimeline[i].e.data_inicio + '</span>';
                        html += '<h3 class="timeline-header"><a href="#">Profissional: </a>' + dadosTimeline[i].po.nome + ' ' + dadosTimeline[i].po.sobrenome + '</h3>';
                        html += '<div class="timeline-body">';
                        html += '<p><b>Consulta:</b> ' + dadosTimeline[i].e.descricao + '</p>';
                        html += '<p><b>Paciente:</b> ' + dadosTimeline[i].p.nome + ' ' + dadosTimeline[i].p.sobrenome + '</p>';
                        html += '<p><b>E-mail:</b> ' + dadosTimeline[i].p.email + '</p>';
                        html += '</div>';
                        html += '</div>';
                        html += '</li>';
                        dataTimeline = dadosTimeline[i].e.data_timeline;
                        i++;
                    }
                    i--;
                    dataTimeline = '';
                }
            }
            html += '</ul>';
            jQuery("#body-timeline").append(html);
            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() == $(document).height()) {
                    startTimeline = startTimeline + 10;
                    carregarTimeline(startTimeline);
                }
            });
        }
    });
}

function carregarGraficoContratos() {
    //-------------
    //- AREA CHART -
    //-------------
    $.get($NOME_APLICACAO + "/index/ajax_grafico_contratos_efetivados", function (data) {
        if (data !== null) {
            var dadosContratos = jQuery.parseJSON(data);
            arrayEfetivados = dadosContratos.efetivados;
            arrayCancelados = dadosContratos.finalizados;
        }

        // Get context with jQuery - using jQuery's .get() method.
        var areaChartCanvas = $("#areaChartContratos").get(0).getContext("2d");
        var areaChart = new Chart(areaChartCanvas);

        var areaChartData = {
            labels: ["JAN", "FEV", "MAR", "ABR", "MAI", "JUN", "JUL", "AGO", "SET", "OUT", "NOV", "DEZ"],
            datasets: [
                {
                    label: "Efetivados",
                    fillColor: "rgba(0,166,90,1)",
                    strokeColor: "rgba(0,166,90,1)",
                    pointColor: "rgba(0,166,90,1)",
                    pointStrokeColor: "rgba(0,166,90,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(0,166,90,1)",
                    data: arrayEfetivados
                },
                {
                    label: "Finalizados",
                    fillColor: "rgba(221,75,57,1)",
                    strokeColor: "rgba(221,75,57,1)",
                    pointColor: "rgba(221,75,57,1)",
                    pointStrokeColor: "rgba(221,75,57,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(221,75,57,1)",
                    data: arrayCancelados
                }
            ]
        };

        var areaChartOptions = {
            //Boolean - If we should show the scale at all
            showScale: true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: false,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - Whether the line is curved between points
            bezierCurve: true,
            //Number - Tension of the bezier curve between points
            bezierCurveTension: 0.3,
            //Boolean - Whether to show a dot for each point
            pointDot: true,
            //Number - Radius of each point dot in pixels
            pointDotRadius: 4,
            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth: 1,
            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius: 20,
            //Boolean - Whether to show a stroke for datasets
            datasetStroke: true,
            //Number - Pixel width of dataset stroke
            datasetStrokeWidth: 2,
            //Boolean - Whether to fill the dataset with a color
            datasetFill: false,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: true,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true
        };

        //Create the line chart
        areaChart.Line(areaChartData, areaChartOptions);
    });

}

function carregarGraficoPlanoSessao() {
    //-------------
    //- PIE CHART -
    //-------------
    var PieData = [];

    $.get($NOME_APLICACAO + "/index/ajax_grafico_plano_sessao", function (data) {
        if (data !== null) {
            var dadosPlanos = jQuery.parseJSON(data);
            var arrayColors = ["#3c8dbc", "#00a65a", "#f39c12", "#f56954", "#d2d6de", "#00c0ef", "#9C27B0", "#009688", "#CDDC39", "#607D8B", "#00BCD4"];
            var i = 0;
            for (var k = 0; k < dadosPlanos.planos.length; k++) {
                var item = {
                    value: dadosPlanos.planos[k][0].valor,
                    color: arrayColors[i],
                    highlight: arrayColors[i],
                    label: dadosPlanos.planos[k][0].plano
                };
                i++;
                if (i > 9) {
                    i = 0;
                }
                PieData.push(item);
                $("#pieChartPlanoSessaoLegend").append('<span style="background-color: ' + item.color + ';padding-left: 15px;"></span>&nbsp; ' + item.label + '&nbsp;&nbsp;');
            }
        }

        var pieChartCanvas = $("#pieChartPlanoSessao").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);

        var pieOptions = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke: true,
            //String - The colour of each segment stroke
            segmentStrokeColor: "#fff",
            //Number - The width of each segment stroke
            segmentStrokeWidth: 2,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps: 100,
            //String - Animation easing effect
            animationEasing: "easeOutBounce",
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate: true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale: false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= ' R$ ' + Number(value).toFixed(2).replace('.', ',') %>",
            multiTooltipTemplate: "<%= ' R$ ' + Number(value).toFixed(2).replace('.', ',')%>",
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);
    });
}



