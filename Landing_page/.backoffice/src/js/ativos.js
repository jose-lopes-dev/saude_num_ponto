(function () {
  "use strict";

  // ---------- Utilidades ----------
  function eur(v) {
    v = Number(v || 0);
    return v.toLocaleString("pt-PT", { style: "currency", currency: "EUR" });
  }

  Apex.chart = {
    locales: [
      {
        name: "pt",
        options: {
          months: [
            "Janeiro",
            "Fevereiro",
            "Março",
            "Abril",
            "Maio",
            "Junho",
            "Julho",
            "Agosto",
            "Setembro",
            "Outubro",
            "Novembro",
            "Dezembro",
          ],
          shortMonths: [
            "Jan",
            "Fev",
            "Mar",
            "Abr",
            "Mai",
            "Jun",
            "Jul",
            "Ago",
            "Set",
            "Out",
            "Nov",
            "Dez",
          ],
          days: [
            "Domingo",
            "Segunda-feira",
            "Terça-feira",
            "Quarta-feira",
            "Quinta-feira",
            "Sexta-feira",
            "Sábado",
          ],
          shortDays: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
        },
      },
    ],
    defaultLocale: "pt",
  };

  function ajax(op, extraFormData, done, fail) {
    var fd = new FormData();
    fd.append("op", op);
    if (extraFormData && typeof extraFormData.forEach === "function") {
      extraFormData.forEach(function (v, k) {
        fd.append(k, v);
      });
    } else if (extraFormData && typeof extraFormData === "object") {
      Object.keys(extraFormData).forEach(function (k) {
        fd.append(k, extraFormData[k]);
      });
    }
    $.ajax({
      url: "src/controller/controllerAtivos.php",
      method: "POST",
      data: fd,
      contentType: false,
      processData: false,
    })
      .done(done)
      .fail(
        fail ||
          function (xhr) {
            console.error("Erro AJAX", xhr?.status, xhr?.responseText);
            Swal.fire({
              icon: "error",
              title: "Erro",
              text: "Falha na comunicação com o servidor.",
            });
          }
      );
  }

  // ---------- Categorias (op=3) ----------
  function carregarCategorias() {
    ajax(3, null, function (resp) {
      var arr = [];
      try {
        arr = JSON.parse(resp || "[]");
      } catch (e) {}
      var html = '<option value="">-- Selecionar --</option>';
      arr.forEach(function (c) {
        html += '<option value="' + c.id + '">' + c.categoria + "</option>";
      });
      $("#sel-categoria").html(html);
    });
  }

  // ---------- Tabela / Listagem (op=1) ----------
  function carregarTabela() {
    ajax(1, null, function (resp) {
      var rows = [];
      try {
        rows = JSON.parse(resp || "[]");
      } catch (e) {}
      var html = "";
      rows.forEach(function (r) {
        html += `
          <tr data-id="${r.id}" data-nome="${r.descricao}">
            <td class="fw-medium">${r.descricao}</td>
            <td>${r.categoria_nome || "-"}</td>
            <td>${eur(r.valor_inicial)}</td>
            <td>${eur(r.dep_acumulada)}</td>
            <td>${eur(r.justo_valor)}</td>
            <td class="text-end">
              <div class="btn-group">
                <button class="btn btn-icon btn-sm rounded-2 shadow-none btn-editar-consulta" data-action="edit" data-id="${r.id}"><i class="ri-pencil-line"></i></button>
                
                <button class="btn btn-icon btn-sm btn-danger rounded-2 shadow-none" data-action="del" data-id="${r.id}"><i class="ri-delete-bin-line"></i></button>

                <button class="btn btn-icon btn-sm btn-primary rounded-2 shadow-none" data-action="ts" data-id="${r.id}" title="Ver evolução"><i class="ri-line-chart-line"></i></button>
              </div>
            </td>
          </tr>`;
      });
      $("#assets-table-body").html(html);
      $("#contador-ativos").text((rows.length || 0) + " registos");
    });
  }

  // ---------- Gráficos gerais (op=7) ----------
  function carregarCharts() {
    var fd = new FormData();
    fd.append("op", 7);

    $.ajax({
      url: "src/controller/controllerAtivos.php",
      method: "POST",
      data: fd,
      contentType: false,
      processData: false,
    })
      .done(function (resp) {
        // segurança: parse robusto
        var payload = {};
        try {
          payload = JSON.parse(resp || "{}");
        } catch (e) {
          payload = {};
        }
        if (!payload || payload.ok === false) return;

        // formatação €
        const eurFmt = (v) =>
          (Number(v) || 0).toLocaleString("pt-PT", {
            style: "currency",
            currency: "EUR",
            maximumFractionDigits: 2,
          });

        // ===== BAR 100% EMPILHADO (correto: nomes à esquerda + % certas) =====
        var barArr = Array.isArray(payload.bar) ? payload.bar : [];
        var depData = barArr.map((x) => {
          var ini = Number(x.valor_inicial) || 0,
            jus = Number(x.justo_valor) || 0;
          return Math.max(ini - jus, 0);
        });
        var jusData = barArr.map((x) => Number(x.justo_valor) || 0);
        var labels = barArr.map((x) => x.descricao);

        // ===== BAR 100% EMPILHADO =====
        var barOpts = {
          chart: {
            type: "bar",
            height: 360,
            stacked: true,
            stackType: "100%",
            toolbar: { show: false },
          },
          series: [
            { name: "Depreciado", data: depData },
            { name: "Justo valor", data: jusData },
          ],
          plotOptions: {
            bar: { horizontal: true, barHeight: "70%", borderRadius: 6 },
          },

          xaxis: {
            categories: labels,
            max: 100,
            tickAmount: 5,
            labels: {
              formatter: (val) => Math.round(val) + "%", 
              style: { colors: "#999", fontSize: "11px" },
            },
          },

          yaxis: {
            labels: {
              style: { colors: "#ddd", fontSize: "13px", fontWeight: 500 },
            },
          },

          dataLabels: {
            enabled: true,
            formatter: function (_val, opts) {
              var i = opts.dataPointIndex;
              var dep = depData[i],
                jus = jusData[i],
                total = dep + jus || 1;
              var pct =
                opts.seriesIndex === 0
                  ? (dep / total) * 100
                  : (jus / total) * 100;
              return Math.round(pct) + "%";
            },
            offsetX: 8,
            style: { fontSize: "13px", fontWeight: 600, colors: ["#fff"] },
          },

          tooltip: {
            shared: true,
            intersect: false,
            y: { formatter: (v) => eurFmt(Number(v.toFixed(2))) },
            x: {
              formatter: (_, opts) => {
                var i = opts.dataPointIndex;
                var ini = Number(barArr[i]?.valor_inicial) || 0;
                return labels[i] + " · Valor inicial: " + eurFmt(ini);
              },
            },
          },
          legend: { position: "bottom", labels: { colors: "#ccc" } },
          colors: ["#ff7675", "#00c689"],
          grid: { borderColor: "#333", strokeDashArray: 4 },
        };

        var elBar = document.querySelector("#chart-bar-inicial-vs-justo");
        if (elBar) {
          if (window.chartBarAtivos) window.chartBarAtivos.destroy();
          window.chartBarAtivos = new ApexCharts(elBar, barOpts);
          window.chartBarAtivos.render();
        }

        // ===  LINE  ====

        var lineArr = Array.isArray(payload.line) ? payload.line : [];
        var lineOpts = {
          chart: { type: "line", height: 300, toolbar: { show: false } },
          series: [
            {
              name: "Depreciação",
              data: lineArr.map((x) => ({ x: x.mes, y: Number(x.total) })),
            },
          ],
          xaxis: { type: "datetime" },
          yaxis: { labels: { formatter: eurFmt } },
          dataLabels: { enabled: false },
          stroke: { curve: "smooth", width: 3 },
          tooltip: { y: { formatter: eurFmt } },
        };
        var elLine = document.querySelector("#chart-line-depreciacao-mensal");
        if (elLine) {
          if (window.chartLineAtivos) window.chartLineAtivos.destroy();
          window.chartLineAtivos = new ApexCharts(elLine, lineOpts);
          window.chartLineAtivos.render();
        }
      })
      .fail(function (xhr) {
        console.error("charts fail", xhr?.status, xhr?.responseText);
      });
  }
  
  // ---------- Timeseries de 1 ativo (op=8) ----------
  function abrirTimeseries(id) {
    var extra = new FormData();
    extra.append("id", id);
    ajax(8, extra, function (resp) {
      var d = {};
      try {
        d = JSON.parse(resp || "{}");
      } catch (e) {}
      if (!d.ok) return;
      if (window.assetAreaChart) window.assetAreaChart.destroy();
      window.assetAreaChart = new ApexCharts(
        document.querySelector("#chart-area-ativo"),
        {
          chart: { type: "area", height: 360, toolbar: { show: false } },
          series: [{ name: "Valor", data: d.serie || [] }],
          xaxis: { type: "datetime" },
          dataLabels: { enabled: false },
          stroke: { curve: "smooth" },
        }
      );
      window.assetAreaChart.render();
      new bootstrap.Modal("#modalAssetChart").show();
    });
  }

  // ---------- Novo / Editar ----------
  function abrirModalNovo() {
    var f = document.getElementById("form-add-asset");
    f.reset();
    $("#form-add-asset").removeData("id"); // limpa "id" (modo criar)
    new bootstrap.Modal("#modalNovoAtivo").show();
  }

  function abrirEditar(id) {
    var extra = new FormData();
    extra.append("id", id);
    ajax(2, extra, function (resp) {
      var r = {};
      try {
        r = JSON.parse(resp || "{}");
      } catch (e) {}
      if (!r || r.ok === false) {
        Swal.fire({
          icon: "error",
          title: "Erro",
          text: r.msg || "Ativo não encontrado",
        });
        return;
      }
      var f = document.getElementById("form-add-asset");
      f.reset();
      f.nome.value = r.descricao || "";
      f.valor_inicial.value = r.valor_inicial || "";
      f.vida_util.value = r.vida_util || ""; // anos (conversão feita no model)
      f.data_aquisicao.value = r.data_aquisicao || "";
      $("#sel-categoria")
        .val(r.id_categoria || "")
        .trigger("change");
      $("#form-add-asset").data("id", r.id || null);
      new bootstrap.Modal("#modalNovoAtivo").show();
    });
  }

  function guardarAtivo(e) {
    e.preventDefault();
    var $form = $("#form-add-asset");
    var fd = new FormData($form.get(0));
    var id = $form.data("id");
    fd.append("op", id ? 5 : 4); // 4=criar, 5=atualizar
    if (id) fd.append("id", id);

    $.ajax({
      url: "src/controller/controllerAtivos.php",
      method: "POST",
      data: fd,
      contentType: false,
      processData: false,
    })
      .done(function (resp) {
        var r = {};
        try {
          r = JSON.parse(resp || "{}");
        } catch (e) {}
        if (r.ok) {
          Swal.fire({
            icon: "success",
            title: $("#form-add-asset").data("id") ? "Alterado!" : "Criado!",
            timer: 1100,
            showConfirmButton: false,
          });

          // Fechar modal apenas se existir 
          var modalEl = document.getElementById("modalNovoAtivo");
          if (modalEl) {
            var inst = bootstrap.Modal.getInstance(modalEl);
            if (!inst) inst = new bootstrap.Modal(modalEl);
            inst.hide();
          }

          var $form = $("#form-add-asset");
          $form.removeData("id").get(0).reset();

          carregarTabela();
          carregarCharts();
        } else {
          Swal.fire({
            icon: "error",
            title: "Erro",
            text: r.msg || "Revê os dados.",
          });
        }
      })
      .fail(function (xhr) {
        console.error(xhr?.status, xhr?.responseText);
        Swal.fire({ icon: "error", title: "Erro", text: "Falha ao guardar." });
      });
  }

  // ---------- Apagar (op=6) ----------
  function apagar(id) {
    Swal.fire({
      title: "Apagar ativo?",
      text: "Esta operação é irreversível.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sim, apagar",
      cancelButtonText: "Cancelar",
    }).then(function (res) {
      if (!res.isConfirmed) return;
      var extra = new FormData();
      extra.append("id", id);
      ajax(6, extra, function (resp) {
        var r = {};
        try {
          r = JSON.parse(resp || "{}");
        } catch (e) {}
        if (r.ok) {
          Swal.fire({
            icon: "success",
            title: "Apagado!",
            timer: 1100,
            showConfirmButton: false,
          });
          carregarTabela();
          carregarCharts();
        } else {
          Swal.fire({
            icon: "error",
            title: "Erro",
            text: r.msg || "Não foi possível apagar.",
          });
        }
      });
    });
  }

  // ---------- Delegações de eventos ----------
  $(document)
  .on("submit", "#form-add-asset", guardarAtivo)
  .on("click", '#assets-table-body [data-action="edit"]', function () {
    abrirEditar($(this).data("id"));
  })
  .on("click", '#assets-table-body [data-action="del"]', function () {
    apagar($(this).data("id"));
  })
  .on("click", '#assets-table-body [data-action="ts"]', function () {
    abrirTimeseries($(this).data("id"));
  });

  // ---------- Boot ----------
  $(function () {
    carregarCategorias(); // op=3
    carregarTabela(); // op=1
    carregarCharts(); // op=7
  });
})();
