$(document).ready(function () {

    $.post("./src/controller/consultaPsicologoController.php", {
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

        $("#kpi_pacientes").text(d.kpis.totalPacientes);
        $("#kpi_pendentes").text(d.kpis.totalPendentes);
        $("#kpi_hoje").text(d.kpis.totalHoje);

        $("#sessoes_hoje").html("");
        d.sessoesHoje.forEach(s => {
            $("#sessoes_hoje").append(`
                <li class="list-group-item d-flex justify-content-between">
                    <span><b>${s.paciente}</b></span>
                    <span>${s.hora}</span>
                    <span class="badge bg-${s.estado_cor}">${s.estado}</span>
                </li>
            `);
        });

        $("#ultimos_pacientes").html("");
        d.ultimosPacientes.forEach(p => {
            $("#ultimos_pacientes").append(`
                <div class="list-group-item">
                    <b>${p.nome}</b><br>
                    <small>${p.data}</small>
                </div>
            `);
        });

        $("#lista_pendentes_dashboard").html("");
        d.pendentes.slice(0, 5).forEach(s => {
            $("#lista_pendentes_dashboard").append(`
                <div class="list-group-item">
                    <b>${s.paciente}</b><br>
                    <small>${s.data_hora}</small>
                </div>
            `);
        });

        new Chart(document.getElementById("graficoSemanal"), {
            type: "bar",
            data: {
                labels: d.grafico.labels,
                datasets: [{
                    label: "Sessões",
                    data: d.grafico.valores
                }]
            }
        });

    });

});
