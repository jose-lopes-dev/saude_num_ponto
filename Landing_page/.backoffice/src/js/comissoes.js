$(document).ready(function () {
  carregarComissoes();
});

/* Tabela Comissões */

function carregarComissoes() {
  $.ajax({
    url: "src/controller/controllerComissao.php",
    method: "POST",
    data: { op: "getData" },
    dataType: "json",
  })
    .done(function (resp) {
      popularTabela(resp.lista);
      desenharGraficos(resp.graficos);
    })
    .fail(function () {
      Swal.fire("Erro", "Falha ao carregar comissões", "error");
    });
}

function popularTabela(lista) {
  if ($.fn.DataTable.isDataTable("#tabelaComissoes")) {
    $("#tabelaComissoes").DataTable().destroy();
  }

  let html = "";
  lista.forEach((c) => {
    const estado =
      c.id_estado == 13
        ? '<span class="badge bg-warning">Pendente</span>'
        : '<span class="badge bg-success">Pago</span>';

    const acao =
      c.id_estado == 13
        ? `<button class="btn btn-sm btn-success" onclick="marcarPago(${c.id})">Marcar como Pago</button>`
        : '<span class="text-muted">—</span>';

    html += `<tr>
      <td>${c.nome}</td>
      <td>${c.funcao}</td>
      <td>${c.numero_consultas}</td>
      <td>${Number(c.total_pagar).toLocaleString("pt-PT", {
        style: "currency",
        currency: "EUR",
      })}</td>
      <td>${c.data_prevista}</td>
      <td>${estado}</td>
      <td>${acao}</td>
    </tr>`;
  });

  $("#listagemComissoes").html(html);
  $("#tabelaComissoes").DataTable({
    language: {
      lengthMenu: "Mostrar _MENU_ registos",
      zeroRecords: "Nenhum registo encontrado",
      info: "A mostrar _START_ a _END_ de _TOTAL_ registos",
      infoEmpty: "A mostrar 0 a 0 de 0 registos",
      infoFiltered: "(filtrado de _MAX_ registos)",
      search: "Pesquisar:",
      paginate: {
        first: "Primeiro",
        last: "Último",
        next: "Seguinte",
        previous: "Anterior"
      }
    }
  });
}

/* ---------  GRÁFICOS --------- */

function desenharGraficos(dados) {
/* ---------  Evitar undefined/NaN --------- */
  const cleanArray = (arr) =>
    (arr || []).map((v) => (v === undefined || Number.isNaN(v) ? null : Number(v)));

  const meses = [...(dados.meses || [])];

  const optionsComuns = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: "nearest", intersect: false, axis: "x" },
    plugins: {
      tooltip: { enabled: true, mode: "nearest", intersect: false },
      legend: {
        position: "bottom",
        labels: { color: "#ffffff" },
        onClick: (e, legendItem, legend) => {
          const chart = legend.chart;
          const idx = legendItem.datasetIndex;
          if (typeof chart.toggleDataVisibility === "function") {
            chart.toggleDataVisibility(idx);
          } else {
            const meta = chart.getDatasetMeta(idx);
            meta.hidden = meta.hidden === null ? !chart.data.datasets[idx].hidden : null;
          }
          chart.update();
        },
      },
    },
    elements: {
      point: { radius: 3, hoverRadius: 6, hitRadius: 10 },
      line: { spanGaps: true },
    },
    scales: {
      x: {
        ticks: { color: "#ffffff" },
        grid: { color: "rgba(255,255,255,0.2)" },
      },
      y: {
        beginAtZero: true,
        ticks: { color: "#ffffff" },
        grid: { color: "rgba(255,255,255,0.2)" },
      },
    },
  };

  /* --- Gráfico: Evolução do Pagamento --- */
  const ctxMes = document.getElementById("chartComissoesMes").getContext("2d");
  if (window.chartMes) window.chartMes.destroy();

  window.chartMes = new Chart(ctxMes, {
    type: "line",
    data: {
      labels: meses,
      datasets: [
        {
          label: "Total Pago (€)",
          data: cleanArray(dados.totalPorMes),
          borderColor: "#4CAF50",
          backgroundColor: "rgba(76,175,80,0.3)",
          tension: 0.3,
          fill: true,
        },
      ],
    },
    options: {
      ...optionsComuns,
      plugins: {
        ...optionsComuns.plugins,
        legend: { labels: { color: "#ffffff" } }, // só 1 dataset
      },
    },
  });
/* --- Gráfico: Pagamento por Função --- */
const ctxFuncao = document.getElementById("chartComissoesFuncao").getContext("2d");
if (window.chartFuncao) window.chartFuncao.destroy();

const cores = ["#4CAF50", "#F44336", "#2196F3", "#FFC107", "#9C27B0"];

const datasets = (dados.funcoes || []).map((funcao, i) => ({
  label: funcao.nome,
  data: cleanArray(funcao.valores),
  borderColor: cores[i % cores.length],
  borderWidth: 2,
  tension: 0.3,
  fill: false,
  pointRadius: 3,
  pointHoverRadius: 7,
  pointHitRadius: 22   
}));

window.chartFuncao = new Chart(ctxFuncao, {
  type: "line",
  data: { labels: meses, datasets },
  options: {
    ...optionsComuns,
    
    interaction: { mode: "point", intersect: true, axis: "x" },
    hover:       { mode: "point", intersect: true },
    normalized: true,
    elements: {
      ...optionsComuns.elements,
      point: { radius: 3, hoverRadius: 7, hitRadius: 22 }
    },
    plugins: {
      legend: {
        position: "bottom",
        labels: { color: "#ffffff" },
        onClick: (evt, li, legend) => {
          const chart = legend.chart;
          const i = li.datasetIndex;
          if (chart.toggleDataVisibility) chart.toggleDataVisibility(i);
          else {
            const meta = chart.getDatasetMeta(i);
            meta.hidden = meta.hidden === null ? !chart.data.datasets[i].hidden : null;
          }
          chart.update();
        }
      },
      tooltip: {
        enabled: true,
        mode: "point",
        intersect: true,
        callbacks: {
          label: (ctx) => `${ctx.dataset.label}: ${ctx.formattedValue}`
        }
      }
    }
  }
});

}

/* ----------- Ação ---------- */

function marcarPago(id) {
  Swal.fire({
    title: "Confirmar Pagamento?",
    text: "Deseja marcar esta comissão como paga?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sim, marcar como paga!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "src/controller/controllerComissao.php",
        method: "POST",
        data: { op: "marcarPago", id: id },
        dataType: "json",
      })
        .done(function (resp) {
          if (resp.flag) {
            Swal.fire("Sucesso", resp.msg, "success");
            carregarComissoes();
          } else {
            Swal.fire("Erro", resp.msg, "error");
          }
        })
        .fail(function () {
          Swal.fire("Erro", "Falha na comunicação com o servidor", "error");
        });
    }
  });
}

/* ---------- Tabela Salários ------------ */

function carregarSalarios() {
  $.ajax({
    url: "src/controller/controllerComissao.php",
    method: "POST",
    data: { op: "getSalarios" },
    dataType: "json",
  })
    .done(function (resp) {
      popularTabelaSalarios(resp.listaS);
    })
    .fail(function () {
      Swal.fire("Erro", "Falha ao carregar salários", "error");
    });
}

function popularTabelaSalarios(listaS) {
  if ($.fn.DataTable.isDataTable("#tabelaSalarios")) {
    $("#tabelaSalarios").DataTable().destroy();
  }

  let html = "";
  listaS.forEach((s) => {
    const estado =
      s.id_estado == 13
        ? '<span class="badge bg-warning">Pendente</span>'
        : '<span class="badge bg-success">Pago</span>';

    const acao =
      s.id_estado == 13
        ? `<button class="btn btn-sm btn-success" onclick="marcarSalarioPago(${s.id})">Marcar como Pago</button>`
        : '<span class="text-muted">—</span>';

    html += `<tr>
      <td>${s.nome}</td>
      <td>${s.funcao}</td>
      <td>${Number(s.salario_bruto).toLocaleString("pt-PT", { style: "currency", currency: "EUR" })}</td>      
      <td>${Number(s.salario_liquido).toLocaleString("pt-PT", { style: "currency", currency: "EUR" })}</td>     
      <td>${s.data_prevista}</td>
      <td>${estado}</td>
      <td>${acao}</td>
    </tr>`;
  });

  $("#listagemSalarios").html(html);
  $("#tabelaSalarios").DataTable({
    language: {
      lengthMenu: "Mostrar _MENU_ registos",
      zeroRecords: "Nenhum registo encontrado",
      info: "A mostrar _START_ a _END_ de _TOTAL_ registos",
      infoEmpty: "A mostrar 0 a 0 de 0 registos",
      infoFiltered: "(filtrado de _MAX_ registos)",
      search: "Pesquisar:",
      paginate: {
        first: "Primeiro",
        last: "Último",
        next: "Seguinte",
        previous: "Anterior"
      }
    }
  });
}

function marcarSalarioPago(id) {
  Swal.fire({
    title: "Confirmar Pagamento?",
    text: "Deseja marcar este salário como pago?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sim, marcar como pago!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "src/controller/controllerComissao.php",
        method: "POST",
        data: { op: "marcarSalarioPago", id: id },
        dataType: "json",
      })
        .done(function (resp) {
          if (resp.flag) {
            Swal.fire("Sucesso", resp.msg, "success");
            carregarSalarios();
          } else {
            Swal.fire("Erro", resp.msg, "error");
          }
        })
        .fail(function () {
          Swal.fire("Erro", "Falha na comunicação com o servidor", "error");
        });
    }
  });
}

/* ------------ TABS ----------- */

$('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
  const target = $(e.target).data('bs-target');
  if (target === '#tab-rh-vencimento') {
    carregarSalarios();
  }
});
