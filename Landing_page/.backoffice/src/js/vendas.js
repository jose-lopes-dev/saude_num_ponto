// ---------- SweetAlert2 helpers ----------
const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 1600,
  timerProgressBar: true,
});
function swalError(titulo, texto) {
  Swal.fire({
    icon: "error",
    title: titulo || "Erro",
    text: texto || "Ocorreu um erro.",
  });
}
function swalWarn(titulo, texto) {
  Swal.fire({ icon: "warning", title: titulo || "Atenção", text: texto || "" });
}

// ---------- Helpers ----------
function pad(n) {
  return String(n).padStart(2, "0");
}
function ymNomeCurto(ym) {
  const [y, m] = String(ym).split("-").map(Number);
  return new Date(y, (m || 1) - 1, 1).toLocaleString("pt-PT", {
    month: "short",
  });
}
function money(v) {
  v = Number(v || 0);
  return v.toLocaleString("pt-PT", { minimumFractionDigits: 2 }) + "€";
}

function escapeHtml(v) {
  const map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#39;",
    "/": "&#x2F;",
  };
  return String(v ?? "").replace(/[&<>"'\/]/g, (s) => map[s]);
}

function gerarMeses(quantos) {
  // devolve array de 'YYYY-MM' do mês atual para trás
  const out = [];
  const d = new Date();
  d.setDate(1);
  for (let i = 0; i < quantos; i++) {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, "0");
    out.push(`${y}-${m}`);
    d.setMonth(d.getMonth() - 1);
  }
  return out; 
}

function nomeMesPT(ym) {
  const [y, m] = ym.split("-").map(Number);
  return new Date(y, m - 1, 1).toLocaleString("pt-PT", {
    month: "long",
    year: "numeric",
  });
}

// Ajax com FormData + tratamento de erros c/ SweetAlert2
function postJSON(url, obj) {
  const fd = new FormData();
  Object.keys(obj || {}).forEach((k) => fd.append(k, obj[k]));
  return $.ajax({
    url,
    method: "POST",
    data: fd,
    contentType: false,
    processData: false,
  })
    .then(function (resp) {
      if (typeof resp === "string") {
        try {
          return JSON.parse(resp);
        } catch (e) {
          return resp;
        }
      }
      return resp;
    })
    .catch(function (xhr) {
      let txt = "Falha a comunicar com o servidor.";
      if (xhr && xhr.responseText) {
        try {
          const j = JSON.parse(xhr.responseText);
          if (j && j.msg) txt = j.msg;
        } catch (_) {}
      }
      swalError("Erro de comunicação", txt);
      throw xhr;
    });
}

const fmtPct = (val) =>
  new Intl.NumberFormat("pt-PT", {
    minimumFractionDigits: 1,
    maximumFractionDigits: 1,
  }).format(val);

const CTRL = "src/controller/controllerVendas.php";

// ---------- State (charts) ----------
let charts = { packs: null, indiv: null, grupo: null };

// ---------- 1) Packs ----------
function carregarPacks(endYm) {
  let dados = endYm ? { op: 1, end: String(endYm).slice(0, 7) } : { op: 1 };

  return postJSON(CTRL, dados)
    .then(function (data) {
      if (!data || !data.labels || data.labels.length === 0) {
        swalWarn(
          "Sem dados",
          "Não existem vendas de packs nos últimos 3 meses."
        );
        // limpar se existir gráfico
        if (charts.packs) {
          charts.packs.destroy();
          charts.packs = null;
        }
        const tbl = document.getElementById("tbl-packs");
        if (tbl) {
          const body = tbl.querySelector("tbody") || tbl;
          body.innerHTML = "";
        }
        return;
      }

      const labels = (data.labels || []).map(ymNomeCurto);

      const src = data.seriesValor || data.series || {};
      const series = [
        { name: "Médio", data: src.medio || [] },
        { name: "Pro", data: src.pro || [] },
        { name: "Duo", data: src.duo || [] },
        { name: "Lar", data: src.lar || [] },
      ];

      const el = document.getElementById("chart-packs");
      if (el && typeof ApexCharts !== "undefined") {
        charts.packs && charts.packs.destroy();
        charts.packs = new ApexCharts(el, {
        chart: {
          type: "bar",
          height: 400,
          stacked: true,
          toolbar: { show: false },
        },
        series,
        xaxis: { categories: labels },
        yaxis: { labels: { formatter: (v) => money(v) } },     
        tooltip: { y: { formatter: (v) => money(v) } },      
        dataLabels: { enabled: false },
        legend: { position: "top" },
        grid: { borderColor: "rgba(0,0,0,0.08)" },

        annotations: {
          points: labels.map((lbl, i) => {
            const total =
              (series[0]?.data?.[i] || 0) +
              (series[1]?.data?.[i] || 0) +
              (series[2]?.data?.[i] || 0) +
              (series[3]?.data?.[i] || 0);
            return {
              x: lbl,
              y: total,
              marker: { size: 0 },              
              label: {
                text: money(total),         
                offsetY: -13,                   
                style: {
                  background: "transparent",   
                  color: "#FBFBFA",            
                  fontWeight: 600
                }
              }
            };
          })
        }
      });
      charts.packs.render();
    }

      const tbl = document.getElementById("tbl-packs");
      if (tbl) {
        const body = tbl.querySelector("tbody") || tbl;
        body.innerHTML = "";
        (data.labels || []).forEach((ym, i) => {
          const row = document.createElement("tr");
          const medio = Number(data.series?.medio?.[i] || 0);
          const pro = Number(data.series?.pro?.[i] || 0);
          const duo = Number(data.series?.duo?.[i] || 0);
          const lar = Number(data.series?.lar?.[i] || 0);
          const total = medio + pro + duo + lar;
          row.innerHTML = `
          <td class="text-capitalize">${ymNomeCurto(ym)}</td>
          <td class="text-end">${medio}</td>
          <td class="text-end">${pro}</td>
          <td class="text-end">${duo}</td>
          <td class="text-end">${lar}</td>
          <td class="text-end fw-semibold">${total}</td>
        `;
          body.appendChild(row);
        });
      }
    })
    .catch(() => {});
}

// ---------- 2) Individuais vs Grupo (mês selecionado) ----------
const PALETA_PIE = [
  "#008FFB",
  "#00E396",
  "#FEB019",
  "#FF4560",
  "#775DD0",
  "#3F51B5",
  "#546E7A",
  "#D4526E",
  "#8D5B4C",
  "#F86624",
];

function renderDonut(elId, titulo, labels, series) {
  const el = document.getElementById(elId);
  if (!el || typeof ApexCharts === "undefined") return;

  window.charts ??= {};
  if (window.charts[elId]) window.charts[elId].destroy();

  const options = {
    chart: {
      type: "pie",
      height: 300,
      toolbar: { show: false },
      dropShadow: { enabled: true, top: 2, left: 2, blur: 3, opacity: 0.2 },
    },
    labels,
    series,
    colors: PALETA_PIE.slice(0, labels.length),
    legend: { position: "bottom", horizontalAlign: "center" },
    plotOptions: {
      pie: {
        expandOnClick: false,
        dataLabels: { offset: -6 }
      }
    },
    dataLabels: {
    enabled: true,
    formatter: (pct) => fmtPct(pct).replace(".", ",") + "%", 
    style: {
      fontSize: "16px",     
      fontWeight: 500,       
      colors: ["#FBFBFA"],  
    },
    tooltip: {
      fillSeriesColor: false,
      y: {
        formatter: (v) => String(v || 0) + " consulta(s)", 
      },
    },
    },
  };

  window.charts[elId] = new ApexCharts(el, options);
  window.charts[elId].render();
}


function renderBar(elId, titulo, labels, series) {
  const el = document.getElementById(elId);
  if (!el || typeof ApexCharts === "undefined") return;

  window.charts ??= {};
  if (window.charts[elId]) window.charts[elId].destroy();

  const toEUR = (v) =>
    Number(v || 0).toLocaleString("pt-PT", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }) + "€";

  const options = {
    chart: { 
      type: "bar", 
      height: 420, 
      toolbar: { show: false } },
    series: [{ 
      name: titulo, 
      data: series 
    }],

    xaxis: {
      categories: (labels || []).map(String),
      labels: { formatter: toEUR },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },

    yaxis: {
      labels: {
        style: { 
          colors: "#89A000", 
          fontSize: "12px", 
          fontWeight: 500 
        },
      },
    },

    plotOptions: {
      bar: { horizontal: true, distributed: true, borderRadius: 8 },
    },
    colors: PALETA_PIE.slice(0, (labels || []).length),

   dataLabels: {
    enabled: true,
    formatter: (v) => money(v),
    style: {
      fontSize: "15px",     
      fontWeight: 600,      
      colors: ["#FBFBFA"],   
    },
    dropShadow: {
      enabled: true,
      top: 1,
      left: 1,
      blur: 2,
      opacity: 0.45,},
    },
    tooltip: {
      x: { formatter: (val) => String(val) },
      y: { formatter: toEUR },
    },
    legend: { show: false },
  };

  window.charts[elId] = new ApexCharts(el, options);
  window.charts[elId].render();
}

function renderColumn(elId, titulo, labels, series) {
  const el = document.getElementById(elId);
  if (!el || typeof ApexCharts === "undefined") return;

  window.charts ??= {};
  if (window.charts[elId]) { try { window.charts[elId].destroy(); } catch (_) {} }

  const fmtMoney = (v) =>
    (typeof money === "function")
      ? money(v)
      : (Number(v || 0).toLocaleString("pt-PT", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "€");

  const origLabels = (labels || []).map(String);

  const displayLabels = origLabels.map(s =>
    s.toUpperCase() === "GANHO DE MASSA MUSCULAR" ? "GANHO DE MASSA" : s
  );

  const options = {
    chart: { 
      type: "bar",
      height: 435, 
      toolbar: { show: false }, 
      foreColor: "#FBFBFA", 
      parentHeightOffset: 0 },
    series: [{ 
      name: titulo, 
      data: series 
    }],

    plotOptions: { bar: { distributed: true, borderRadius: 8, columnWidth: "45%" } },
    colors: (typeof PALETA_PIE !== "undefined") ? PALETA_PIE.slice(0, displayLabels.length) : undefined,

    dataLabels: {
      enabled: true,
      formatter: (v) => fmtMoney(v),
      style: { fontSize: "15px", 
        fontWeight: 600, 
        colors: ["#FBFBFA"] 
      },
      dropShadow: { 
        enabled: true, 
        top: 1, 
        left: 1, 
        blur: 2, 
        opacity: 0.45 
      }
    },

    xaxis: {
      categories: displayLabels,
      tickPlacement: "between",
      labels: {
        rotate: 0, 
        rotateAlways: false, 
        trim: false, 
        hideOverlappingLabels: false,
        maxHeight: 120, 
        offsetY: 8,
        style: { colors: "#89A000", fontSize: "12px", fontWeight: 500 }
      },
      axisTicks: { show: false }, axisBorder: { show: false }
    },

    yaxis: {
      labels: { formatter: (v) => fmtMoney(v), style: { colors: "#FBFBFA" } },
      title: { text: "Valor (€)", style: { color: "#FBFBFA" } }
    },

    grid: { padding: { bottom: 25 } },

    legend: { show: false },

    tooltip: {
      x: {
        formatter: (val, ctx) => {
          const i = ctx?.dataPointIndex ?? 0;
          return origLabels[i] || val;
        }
      },
      y: { formatter: (v) => fmtMoney(v) }
    }
  };

  window.charts[elId] = new ApexCharts(el, options);
  window.charts[elId].render();
}

function carregarIndivGrupoMes(ym) {
  const dados = {
    op: 2,
    ym: String(ym || "").slice(0, 7) || new Date().toISOString().slice(0, 7),
  };

  return postJSON(CTRL, dados)
    .then(function (data) {
      // Donuts
      renderDonut(
        "chart-individuais",
        "Individuais",
        data?.individuais?.labels || [],
        data?.individuais?.values || []
      );
      renderDonut(
        "chart-grupo",
        "Grupo",
        data?.grupo?.labels || [],
        data?.grupo?.values || []
      );

      // Novos gráficos (€)
      renderBar(
        "chart-individuais-valor",
        "Consultas Individuais (€)",
        data?.individuais?.labels || [],
        data?.individuais?.values_eur || []
      );
      renderColumn(
        "chart-grupo-valor",
        "Aulas de Grupo (€)",
        data?.grupo?.labels || [],
        data?.grupo?.values_eur || []
      );

      $("#tot-ind-eur").text(money(data?.individuais?.total_eur || 0));
      $("#tot-grp-eur").text(money(data?.grupo?.total_eur || 0));
    })
    .catch(() => {});
}

// ---------- 3) Resumo meses (tabela) ----------
function carregarResumoMes(ym, modo = "tri") {
  const dados = { op: 3, ym: String(ym || "").slice(0, 7), modo };

  return postJSON(CTRL, dados)
    .then(function (data) {
      const tbl = document.getElementById("tbl-resumo");
      if (!tbl) return;
      const body = tbl.querySelector("tbody") || tbl;
      body.innerHTML = "";

      (data.rows || []).forEach((r) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
        <td class="text-capitalize">${ymNomeCurto(r.mes)}</td>
        <td class="text-end">${r.individuais}</td>
        <td class="text-end">${r.grupo}</td>
        <td class="text-end fw-semibold">${r.total}</td>`;
        body.appendChild(tr);
      });
    })
    .catch(() => {});
}

// --------- FATURAÇÃO (25 por página, pesquisa, remover) ----------
var fatLim = 25;
var fatPag = 0; //
var fatQ   = ""; // termo de pesquisa

function carregarFaturacao(pag) {
  fatPag = Number(pag) || 0;
  var off = fatPag * fatLim;

  $.ajax({
    url: "src/controller/controllerVendas.php",
    method: "POST",
    data: { op: 4, lim: fatLim, off: off, q: fatQ },
    success: function (resp) {   
      var data = resp;
      if (typeof data === "string") {
        try { data = JSON.parse(data); } catch (e) {}
      }

      var rows  = data && data.rows ? data.rows : [];
      var $body = $("#tbl-faturacao tbody");

      if (!rows.length) {
        $body.html('<tr><td colspan="6">Sem registos nesta página.</td></tr>');
        $("#faturacao-info").text("Página " + (fatPag + 1) + " • 0 registos");
        $("#fat-prev").prop("disabled", fatPag <= 0);
        $("#fat-next").prop("disabled", rows.length < fatLim);
        return;
      }

      var html = "";
      $.each(rows, function (_, r) {
        let botoesFatura = "";

        if (r.has_fatura) {
          // Ver + Atualizar
          botoesFatura =
            '<a href="' + escapeHtml(r.fatura_url) + '" target="_blank" ' +
              'class="btn btn-info btn-sm me-1 ri-eye-line" title="Ver"></a>' +
            '<button class="btn btn-warning btn-sm me-1 ri-refresh-line btn-fat-upd" ' +
              'data-id="' + r.id_venda + '" title="Atualizar"></button>';
        } else {
          // Enviar 
          botoesFatura =
            '<button class="btn btn-secondary btn-sm me-1 ri-upload-cloud-line btn-fat-send" ' +
              'data-id="' + r.id_venda + '" title="Enviar"></button>';
        }

        const botoesAcao = botoesFatura +
          '<button class="btn btn-danger btn-sm ms-1 ri-delete-bin-line btn-rem" ' +
            'data-id="' + r.id_venda + '" title="Remover"></button>';

        html +=
          "<tr>" +
            "<td>" + escapeHtml(r.cliente) + "</td>" +
            "<td>" + escapeHtml(r.servico) + "</td>" +
            "<td>" + escapeHtml(r.data) + "</td>" +
            '<td class="text-end">' + money(r.valor) + "</td>" +
            '<td><span class="badge bg-success-subtle text-success">' + escapeHtml(r.estado) + "</span></td>" +
            '<td class="text-end" style="white-space:nowrap;">' + botoesAcao + "</td>" +
          "</tr>";
      });

      $body.html(html);
      $("#faturacao-info").text("Página " + (fatPag + 1) + " • " + rows.length + " registo(s)");
      $("#fat-prev").prop("disabled", fatPag <= 0);
      $("#fat-next").prop("disabled", rows.length < fatLim);
    }
  });
}

// Navegação
$("#fat-next").off("click").on("click", function () {
  fatPag = fatPag + 1;
  carregarFaturacao(fatPag);
});

$("#fat-prev").off("click").on("click", function () {
  if (fatPag > 0) fatPag = fatPag - 1;
  carregarFaturacao(fatPag);
});

// Pesquisa com debounce
(function () {
  var t = null;
  $("#faturacao-search").on("input", function () {
    clearTimeout(t);
    t = setTimeout(function () {
      fatQ = String($("#faturacao-search").val() || "").trim();
      carregarFaturacao(0);
    }, 250);
  });
})();

// remover
$("#tbl-faturacao").on("click", ".btn-rem", function () {
  var id = $(this).data("id");
  Swal.fire({
    icon: "warning",
    title: "Remover transação?",
    text: "Esta operação é irreversível.",
    showCancelButton: true,
    confirmButtonText: "Remover",
    cancelButtonText: "Cancelar",
  }).then(function (res) {
    if (res.isConfirmed) {
      $.ajax({
        url: "src/controller/controllerVendas.php",
        method: "POST",
        data: { op: 5, id: id },
        success: function (r) {
          var j = r;
          if (typeof j === "string") {
            try {
              j = JSON.parse(j);
            } catch (e) {}
          }
          if (j && j.ok) {
            Toast.fire({ icon: "success", title: "Removido" });
            if ($("#tbl-faturacao tbody tr").length === 1 && fatPag > 0)
              carregarFaturacao(fatPag - 1);
            else carregarFaturacao(fatPag);
          } else {
            Swal.fire({
              icon: "error",
              title: "Erro",
              text: j && j.msg ? j.msg : "Não foi possível remover.",
            });
          }
        },
        error: function () {
          Swal.fire({
            icon: "error",
            title: "Erro",
            text: "Falha ao remover.",
          });
        },
      });
    }
  });
});

// Abre o modal (Enviar/Atualizar)
$(document).on('click', '.btn-fat-send, .btn-fat-upd', function (e) {
  e.preventDefault();
  const id = $(this).data('id');
  $('#faturaVendaId').val(id);
  $('#fileFatura').val('');
  $('#modalFatura').modal('show');  
});

// Submete o modal 
$('#formFatura').on('submit', function (e) {
  e.preventDefault();

  const fd = new FormData(this);
  fd.append('op', 6); 

  $.ajax({
    url: 'src/controller/controllerVendas.php',
    method: 'POST',
    data: fd,
    processData: false,
    contentType: false,
    dataType: 'json'
  })
  .done(function (resp) {
    if (!resp || (!resp.ok && !resp.flag)) {
      swalError('Erro!', (resp && resp.msg) || 'Falha ao enviar a fatura.');
      return;
    }
    Swal.fire('Sucesso!', (resp && resp.msg) || 'Fatura enviada/atualizada com sucesso!', 'success');
    $('#modalFatura').modal('hide');
    carregarFaturacao(fatPag);
  })
  .fail(function () {
    Swal.fire('Erro!', 'Não foi possível enviar a fatura.', 'error');
  });
});

// Ver fatura
$(document).on('click', '.btn-fat-ver', function () {
  const url = $(this).data('url');
  if (url) window.open(url, '_blank');
});

// ---------- INIT ----------
$(function () {
  // Preencher o SELECT de Trimestre (ano atual) e carregar packs
  const $tri = $("#sel-tri");
  if ($tri.length) {
    const ano = new Date().getFullYear();
    const trimestres = [
      { label: `1º Trimestre ${ano}`, end: `${ano}-03` },
      { label: `2º Trimestre ${ano}`, end: `${ano}-06` },
      { label: `3º Trimestre ${ano}`, end: `${ano}-09` },
      { label: `4º Trimestre ${ano}`, end: `${ano}-12` },
    ];

    $tri.empty();
    trimestres.forEach((t) => {
      const opt = document.createElement("option");
      opt.value = t.end;
      opt.textContent = t.label;
      $tri.append(opt);
    });

    // Seleção por defeito: 3º Trimestre
    const endTriDefault = `${ano}-09`;
    if ($tri.find(`option[value="${endTriDefault}"]`).length) {
      $tri.val(endTriDefault);
    }

    // Ao mudar, recarrega packs para esse trimestre
    $tri.on("change", function () {
      carregarPacks(this.value);
    });
  }

  // Preencher o SELECT com os 12 meses do ANO CORRENTE
  const $sel = $("#sel-mes");
  const ano = new Date().getFullYear();
  const mesesAno = Array.from(
    { length: 12 },
    (_, i) => `${ano}-${String(i + 1).padStart(2, "0")}`
  ); 

  $sel.empty();
  mesesAno.forEach((ym) => {
    const opt = document.createElement("option");
    opt.value = ym;
    opt.textContent = nomeMesPT(ym); 
    $sel.append(opt);
  });

  // selecionar JULHO do ano corrente por defeito
  const mesDefault = `${ano}-07`;
  if ($sel.find(`option[value="${mesDefault}"]`).length) {
    $sel.val(mesDefault);
  } else {
    $sel.val(`${ano}-07`);
  }

  // Carregamentos iniciais 
  $.when(
    carregarPacks($tri.val()),
    carregarIndivGrupoMes($sel.val()),
    carregarResumoMes($sel.val(), "tri"),
    carregarFaturacao(0)
  ).fail(function () {});

  // Quando o utilizador muda de mês 
  $sel.on("change", function () {
    carregarIndivGrupoMes(this.value);
    carregarResumoMes(this.value, "tri");
  });
});
