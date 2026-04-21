var chartConsultasSemana = null;
var chartConsultasEstado = null;

$(function () {

    carregarResumoDashboard();
    carregarConsultasSemana();
    carregarConsultasEstado();
    carregarProximasConsultas();
});

// 1) KPIs topo
function carregarResumoDashboard(){

    let dados = new FormData();
    dados.append("op", 1);

    $.ajax({
        url: "src/controller/controllerDashboard_pt.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(res){

        $('#kpi-consultas-hoje').text(res.consultas_hoje || 0);
        $('#kpi-consultas-semana').text(res.consultas_semana || 0);

        if (res.receita_mes !== undefined && res.receita_mes !== null) {
            $('#kpi-receita-mes').text(res.receita_mes + '€');
        } else {
            $('#kpi-receita-mes').text('0€');
        }

        $('#kpi-novos-clientes').text(res.novos_clientes_mes || 0);

    })
    .fail(function(jqXHR, textStatus){
        alert("Request failed: " + textStatus);
    });
}

// 2) Gráfico: consultas por dia da semana
function carregarConsultasSemana(){

    let dados = new FormData();
    dados.append("op", 2);

    $.ajax({
        url: "src/controller/controllerDashboard_pt.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(res){

        if (!res || !res.labels) return;

        let ctx = document.getElementById('chartConsultasSemana').getContext('2d');

        if (chartConsultasSemana) {
            chartConsultasSemana.destroy();
        }

        chartConsultasSemana = new Chart(ctx, {
            type: 'bar', 
            data: {
                labels: res.labels,
                datasets: [{
                    label: 'Consultas',
                    data: res.data,
                    borderWidth: 1,
                    borderRadius: 6,
                    maxBarThickness: 40,
                    borderColor: 'rgba(0, 153, 255, 1)',      
                    backgroundColor: 'rgba(0, 153, 255, 0.75)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,   
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true
                    }
                },
                layout: {
                padding: {
                    top: 25,
                    right: 10,
                    bottom: 10,
                    left: 10
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: '#FBFBFA'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#FBFBFA'
                        },
                        suggestedMax: Math.max(...res.data, 1) + 1,
                        grid: {
                            borderDash: [4, 4],
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                          border: {
                            display: true,
                            color: 'rgba(255,255,255,0.05)' 
                        },
                    }
                }
            }
        });

        if (res.label_periodo) {
            $('#label-periodo-semana').text(res.label_periodo);
        }

    })
    .fail(function(jqXHR, textStatus){
        alert("Request failed: " + textStatus);
    });
}

// 3) Gráfico: consultas por estado
function carregarConsultasEstado(){

    let dados = new FormData();
    dados.append("op", 3);

    $.ajax({
        url: "src/controller/controllerDashboard_pt.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(res){

        let labels = res.labels || [];
        let data   = res.data   || [];

        let canvas = document.getElementById('chartConsultasEstado');
        if (!canvas) return;

        let ctx = canvas.getContext('2d');

        if (chartConsultasEstado) {
            chartConsultasEstado.destroy();
        }

        chartConsultasEstado = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                data: data,
                backgroundColor: ['#289fc4', '#36be32', '#f2c80f'],
                borderColor: 'rgba(255,255,255,0.5)',
                borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',

                layout: {
                    padding: {
                        top: 16
                    }
                },

                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 30,
                            boxWidth: 36,
                            boxHeight: 10,                 
                            color: '#FBFBFA',            
                            font: {
                                size: 13,
                                weight: '500'
                            }
                        }
                    }
                }
            }
            });

        })
        .fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });
    }

// 4) Tabela de próximas consultas
function carregarProximasConsultas(){

    let dados = new FormData();
    dados.append("op", 4);

    $.ajax({
        url: "src/controller/controllerDashboard_pt.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(res){

        let $tbody = $('#tabelaProximasConsultas tbody');
        $tbody.empty();

        if (!res || !res.length) {
            $('#sem-proximas-consultas').show();
            return;
        }

        $('#sem-proximas-consultas').hide();

        $.each(res, function(i, consulta){
            let linha = "<tr>" +
                "<td>" + (consulta.data || '') + "</td>" +
                "<td>" + (consulta.hora || '') + "</td>" +
                "<td>" + (consulta.cliente || '') + "</td>" +
                "<td>" + (consulta.servico || '') + "</td>" +
                "<td>" + (consulta.estado || '') + "</td>" +
            "</tr>";

            $tbody.append(linha);
        });

    })
    .fail(function(textStatus){
        alert("Request failed: " + textStatus);
    });
}
