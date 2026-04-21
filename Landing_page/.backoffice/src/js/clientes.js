let chartTotal = null;
let chartNovosRecorrentes = null;
let chartReceitaMedia = null;
let chartCrescimento = null;

$(document).ready(function(){
  carregarDashboard();
});

function carregarDashboard(){
  $.ajax({
    url: "src/controller/controllerCliente.php",
    method: "POST",
    data: { op: "getDashboard" },
    dataType: "json",
    success: function(data){
      preencherKPIs(data.kpis);
      desenharGraficos(data);
    },
    error: function(xhr, status, error){
      console.error("Erro AJAX:", error);
      alert("Erro ao carregar dashboard de clientes");
    }
  });
}

function preencherKPIs(k) {
  const d = new Date();
  d.setMonth(d.getMonth() - 1);
  const nomeMes = d.toLocaleString('pt-PT', { month: 'long' });
  $('#titulo_novos_mes').text('Novos em ' + nomeMes.charAt(0).toUpperCase() + nomeMes.slice(1));

  $('#k_total_clientes').text(k.total || 0);
  $('#k_novos_mes').text(k.novos_mes || 0);
  $('#k_receita_total').text('€ ' + Number(k.receita_total || 0).toLocaleString('pt-PT'));
  $('#k_crescimento').text((k.crescimento || 0) + '%');
}


function desenharGraficos(data) {
  const meses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

  // ------- Total de Clientes ---------
  const ctxTotal = document.getElementById('chartTotalClientes');
  if (chartTotal) chartTotal.destroy();
  chartTotal = new Chart(ctxTotal, {
    type: 'bar',
    data: {
      labels: data.total.map(d => meses[d.mes - 1]),
      datasets: [{
        label: 'Clientes',
        data: data.total.map(d => d.total),
        backgroundColor: '#007bff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { labels: { color: '#ffffff' } }
      },
      scales: {
        x: {
          ticks: { color: '#ffffff' },
          grid: { color: 'rgba(255,255,255,0.2)' }
        },
        y: {
          ticks: { color: '#ffffff' },
          grid: { color: 'rgba(255,255,255,0.2)' }
        }
      }
    }
  });

  // -------- Novos vs Recorrentes ----------
  const ctxNR = document.getElementById('chartNovosRecorrentes');
  if (chartNovosRecorrentes) chartNovosRecorrentes.destroy();
  chartNovosRecorrentes = new Chart(ctxNR, {
    type: 'bar',
    data: {
      labels: data.novosRecorrentes.map(d => meses[d.mes - 1]),
      datasets: [
        { label: 'Novos', data: data.novosRecorrentes.map(d => d.novos), backgroundColor: '#28a745' },
        { label: 'Recorrentes', data: data.novosRecorrentes.map(d => d.recorrentes), backgroundColor: '#ffc107' }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { labels: { color: '#ffffff' } }
      },
      scales: {
        x: { 
          stacked: true,
          ticks: { color: '#ffffff' },
          grid: { color: 'rgba(255,255,255,0.2)' }
        },
        y: { 
          stacked: true,
          ticks: { color: '#ffffff' },
          grid: { color: 'rgba(255,255,255,0.2)' }
        }
      }
    }
  });

  // -------- Receita Média --------
  const ctxReceita = document.getElementById('chartReceitaMedia');
  if (chartReceitaMedia) chartReceitaMedia.destroy();
  chartReceitaMedia = new Chart(ctxReceita, {
    type: 'line',
    data: {
      labels: data.receitaMedia.map(d => meses[d.mes - 1]),
      datasets: [{
        label: '€ por Cliente',
        data: data.receitaMedia.map(d => d.receita_media),
        borderColor: '#E91E63',
        backgroundColor: 'rgba(233, 30, 99, 0.2)',
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { labels: { color: '#ffffff' } }
      },
      scales: {
        x: { 
          ticks: { color: '#ffffff' },
          grid: { color: 'rgba(255,255,255,0.2)' }
        },
        y: { 
          ticks: { color: '#ffffff' },
          grid: { color: 'rgba(255,255,255,0.2)' }
        }
      }
    }
  });

  // ---------- Crescimento ----------
  const ctxCrescimento = document.getElementById('chartCrescimento');
  if (chartCrescimento) chartCrescimento.destroy();

  const mesesFiltro = ['Jul', 'Ago', 'Set'];
  const dadosCrescimento = {};
  data.crescimento.forEach(d => { dadosCrescimento[d.mes] = d.crescimento ?? 0; });
  const valores = [7, 8, 9].map(m => dadosCrescimento[m] ?? 0);

  chartCrescimento = new Chart(ctxCrescimento, {
    type: 'line',
    data: {
      labels: mesesFiltro,
      datasets: [{
        label: 'Crescimento (%)',
        data: valores,
        borderColor: '#FF5722',
        backgroundColor: 'rgba(255, 87, 34, 0.2)',
        fill: true,
        tension: 0.3,
        borderWidth: 2,
        pointRadius: 4,
        pointBackgroundColor: '#FF5722'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { 
          display: true, 
          position: 'bottom',
          labels: { color: '#ffffff' }
        },
        tooltip: { callbacks: { label: ctx => ctx.parsed.y + '%' } }
      },
      scales: {
        x: { 
          ticks: { color: '#ffffff' },
          grid: { color: 'rgba(255,255,255,0.2)' }
        },
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Crescimento (%)', color: '#ffffff' },
          ticks: { color: '#ffffff' },
          grid: { color: 'rgba(255,255,255,0.2)' }
        }
      }
    }
  });
}

