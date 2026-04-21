$(function () {
  "use strict";

  var $pesoAtual = $("#peso-atual");
  var $caloriasAtual = $("#calorias-atual");
  var $treinoAtual = $("#treino-atual");

  var $inputPeso = $("#peso");
  var $inputCalorias = $("#calorias");
  var $inputTreino = $("#treino");

  var $btnGuardar = $("#btnGuardarDados");
  var $modalEl = $("#modalAtualizarProgresso");


  var chartPeso = null;
  var ctx = document.getElementById("chart-peso");
  if (ctx && typeof Chart !== "undefined") {
    chartPeso = new Chart(ctx, {
      type: "line",
      data: {
        labels: [],
        datasets: [{
          label: "Peso (kg)",
          data: [],
          borderColor: "#28a745",
          backgroundColor: "rgba(40,167,69,0.20)",
          fill: true,
          tension: 0.3,
          pointRadius: 4
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: {
          x: { title: { display: true, text: "Registos" } },
          y: { title: { display: true, text: "Peso (kg)" } }
        },
        responsive: true,
        maintainAspectRatio: false
      }
    });
  }

  function apiPost(data, cb) {
    $.post("src/controller/controllerDashboardCliente.php", data, function (res) {
      cb(null, res);
    }, "json").fail(function (jqXHR, status, err) {
      cb({ status: status, err: err, text: jqXHR.responseText }, null);
    });
  }

function carregarDados() {
  apiPost({ op: 1 }, function (err, res) {
    if (err) {
      console.error("API error carregarDados:", err);
      return;
    }

    if (!res || !res.flag) {
      console.warn("Resposta inválida ao carregar dados:", res);
      return;
    }

    var peso = (res.peso === null || res.peso === undefined || res.peso === "") ? null : res.peso;
    var calorias = (res.calorias === null || res.calorias === undefined || res.calorias === "") ? null : res.calorias;
    var tempo = (res.tempo === null || res.tempo === undefined || res.tempo === "") ? null : res.tempo;

    $pesoAtual.text(peso ? peso + " kg" : "-- kg");
    $caloriasAtual.text(calorias ? calorias + " kcal" : "-- kcal");
    $treinoAtual.text(tempo ? tempo + " min" : "-- min");

    $inputPeso.val(peso || "");
    $inputCalorias.val(calorias || "");
    $inputTreino.val(tempo || "");

    if (chartPeso) {
      if (Array.isArray(res.historico) && res.historico.length > 0) {
        var labels = res.historico.map(function (_, i) {
          return "Registo " + (i + 1);
        });
        var data = res.historico.map(function (v) {
          return parseFloat(v) || 0;
        });
        chartPeso.data.labels = labels;
        chartPeso.data.datasets[0].data = data;
        chartPeso.update();
      } else {
        if (peso !== null) {
          chartPeso.data.labels = ["Registo 1"];
          chartPeso.data.datasets[0].data = [parseFloat(peso) || 0];
          chartPeso.update();
        } else {
          chartPeso.data.labels = [];
          chartPeso.data.datasets[0].data = [];
          chartPeso.update();
        }
      }
    }

    // ===============================
    // PRÓXIMAS ATIVIDADES (AGENDA)
    // ===============================
    var $agenda = $("#lista-agenda");
    $agenda.html("");

    if (Array.isArray(res.consultas) && res.consultas.length) {

      res.consultas.slice(0, 3).forEach(function (c) {

        var d = new Date(c.data);
        var dia = d.toLocaleDateString("pt-PT", { weekday: "long" });

        $agenda.append(`
          <li class="list-group-item py-3 d-flex justify-content-between align-items-center">
            <div>
              <b>${c.titulo}</b><br>
              <small class="text-muted">${dia}</small>
            </div>
            <small class="fw-semibold text-secondary">
              ${c.hora_inicio} - ${c.hora_fim}
            </small>
          </li>
        `);

      });

    } else {

      $agenda.append(`
        <li class="list-group-item py-3 d-flex justify-content-between align-items-center">
          <div class="text-center w-100 text-muted">
            Sem atividades agendadas
          </div>
        </li>
      `);

    }
  });
}


  carregarDados();

  $btnGuardar.on("click", function (e) {
    e.preventDefault();

    $btnGuardar.prop("disabled", true).text("Guardando...");

    var pesoVal = $inputPeso.val();
    var caloriasVal = $inputCalorias.val();
    var treinoVal = $inputTreino.val();

    apiPost({ op: 2, peso: pesoVal, calorias: caloriasVal, tempo: treinoVal }, function (err, res) {
      $btnGuardar.prop("disabled", false).text("Guardar");

      if (err) {
        console.error("Erro ao guardar:", err);
        if (window.Swal) Swal.fire("Erro", "Falha na comunicação com o servidor.", "error");
        else alert("Falha na comunicação com o servidor.");
        return;
      }

      if (!res || !res.flag) {
        var msg = (res && res.msg) ? res.msg : "Erro ao guardar os dados.";
        if (window.Swal) Swal.fire("Erro", msg, "error");
        else alert(msg);
        return;
      }

      if (window.Swal) {
        Swal.fire({ icon: "success", title: "Sucesso", text: res.msg || "Dados atualizados!", timer: 1200, showConfirmButton: false });
      }

    
      var modalDom = document.getElementById("modalAtualizarProgresso");
      if (modalDom && typeof bootstrap !== "undefined" && bootstrap.Modal) {
        var inst = bootstrap.Modal.getInstance(modalDom);
        if (inst) inst.hide();
        else {
          var tmp = new bootstrap.Modal(modalDom);
          tmp.hide();
        }
      } else {
        $modalEl.modal("hide");
      }

  
      carregarDados();
    });
  });

function carregarParticipacao() {
  apiPost({ op: 3 }, function (err, res) {
    if (err || !res || !res.flag) return

    var total = res.consultas + res.aulas
    if (total === 0) return

    var ctx = document.getElementById("chart-pizza")
    if (!ctx || typeof Chart === "undefined") return

    if (ctx._chart) ctx._chart.destroy()

    ctx._chart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Consultas", "Aulas"],
        datasets: [{
          data: [res.consultas, res.aulas],
          backgroundColor: ["#0d6efd", "#20c997"],
          borderWidth: 2,
          borderColor: "#2b2b2b"
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        devicePixelRatio: window.devicePixelRatio || 1,
        cutout: "65%",
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              color: "#cfcfcf",
              padding: 14
            }
          }
        }
      }
    })
  })
}

carregarParticipacao()


 

});
