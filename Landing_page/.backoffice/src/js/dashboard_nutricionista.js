$(document).ready(function () {

    $.post("./src/controller/consultaNutriController.php", {
        acao: "dashboard"
    }, function (resp) {

        let d;
        try { d = JSON.parse(resp); } catch {
            console.log(resp);
            return;
        }

        if (d.erro === "sessao") {
            Swal.fire("Sessão expirada", "Volte a iniciar sessão", "error");
            return;
        }

        // KPIs
        $("#kpi_clientes").text(d.kpis.totalClientes);
        $("#kpi_pendentes").text(d.kpis.totalPendentes);
        $("#kpi_hoje").text(d.kpis.totalHoje);

        // Consultas de hoje
        $("#consultas_hoje").html("");
        d.consultasHoje.forEach(c => {
            $("#consultas_hoje").append(`
                <li class="list-group-item d-flex justify-content-between">
                    <span><b>${c.cliente}</b></span>
                    <span>${c.hora}</span>
                    <span class="badge bg-${c.estado_cor}">${c.estado}</span>
                </li>
            `);
        });

        // Últimos clientes
        $("#ultimos_clientes").html("");
        d.ultimosClientes.forEach(c => {
            $("#ultimos_clientes").append(`
                <div class="list-group-item">
                    <b>${c.nome}</b><br>
                    <small>${c.data}</small>
                </div>
            `);
        });

        // Pendentes rápidas
        $("#lista_pendentes_dashboard").html("");
        d.pendentes.slice(0,5).forEach(c => {
            $("#lista_pendentes_dashboard").append(`
                <div class="list-group-item">
                    <b>${c.cliente}</b><br>
                    <small>${c.data_hora}</small>
                </div>
            `);
        });

new Chart(document.getElementById("graficoSemanal"), {
    type: "bar",
    data: {
        labels: d.grafico.labels,
        datasets: [{
            label: "Consultas",
            data: d.grafico.valores
        }]
    }
});


    });

});
