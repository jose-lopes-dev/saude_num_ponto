// financas.js
$(document).ready(function(){
  carregarDados();
});

function carregarDados(){
  $.ajax({
    url: "src/controller/controllerFinancas.php",
    method: 'POST',
    data: { op: 'getData' },
    dataType: 'json'
  })
  .done(function(resp){
    if(!resp || resp.error){
      Swal.fire({
        title: "Erro!",
        text: "Erro a carregar dados do servidor.",
        icon: "error",
        confirmButtonText: "OK"
      });
      return;
    }
    popularKPIs(resp.kpis);
    popularTabela(resp.prestacoes);
    desenharGraficos(resp.prestacoes, resp.kpis);
  })
  .fail(function(){
    Swal.fire({
      title: "Falha de Comunicação",
      text: "Não foi possível obter os dados. Verifique a ligação.",
      icon: "warning",
      confirmButtonText: "Tentar Novamente"
    });
  });
}

function popularKPIs(kpis){
  const fmt = v => Number(v || 0).toLocaleString('pt-PT', { style: 'currency', currency: 'EUR' });

  $('#k_valor_emprestimo').text(fmt(kpis.valor_inicial));
  $('#k_valor_pago').text(fmt(kpis.total_pago));
  $('#k_proxima_prestacao').text(fmt(kpis.proxima_valor));
  $('#k_proxima_data').text(kpis.proxima_data ? kpis.proxima_data : '');
  $('#k_valor_por_pagar').text(fmt(kpis.saldo_devedor));
  $('#k_juros_pagos').text(fmt(kpis.total_juros_pagos));
}

function popularTabela(prestacoes){
  if($.fn.DataTable.isDataTable('#prestacoesTable')){
    $('#prestacoesTable').DataTable().destroy();
  }

  let html = '';
  prestacoes.forEach(p => {
    const estado = p.pago == 1 
      ? '<span class="badge bg-success">Pago</span>' 
      : '<span class="badge bg-warning">Pendente</span>';
    const acao = p.pago == 1 
      ? '' 
      : `<button class="btn btn-sm btn-success" onclick="marcarPago(${p.id})">Marcar como Pago</button>`;
    html += `<tr>
      <td>${p.numero || p.id}</td>
      <td>${p.data_prevista || ''}</td>
      <td>${Number(p.valor_prestacao).toLocaleString('pt-PT', { style: "currency", currency: "EUR" })}</td>
      <td>${Number(p.juros).toLocaleString("pt-PT", { style: "currency", currency: "EUR" })}</td>
      <td>${Number(p.amortizacao).toLocaleString("pt-PT", { style: "currency", currency: "EUR" })}</td>
      <td>${Number(p.saldo_devedor).toLocaleString("pt-PT", { style: "currency", currency: "EUR" })}</td>
      <td>${estado}</td>
      <td>${acao}</td>
    </tr>`;
  });

  $('#prestacoesBody').html(html);
  $('#prestacoesTable').DataTable({
    paging: true,
    searching: true,
    order: [[1,'asc']]
  });
}

function desenharGraficos(prestacoes, kpis) {
  // Agrupar prestações por ano
  const porAno = {};

  prestacoes.forEach(p => {
    const data = new Date(p.data_prevista);
    if (isNaN(data)) return; // ignora registros sem data válida
    const ano = data.getFullYear();

    if (!porAno[ano]) {
      porAno[ano] = { amortizacao: 0, juros: 0 };
    }

    porAno[ano].amortizacao += Number(p.amortizacao || 0);
    porAno[ano].juros += Number(p.juros || 0);
  });

  // Preparar arrays ordenados
  const anos = Object.keys(porAno).sort((a, b) => a - b);
  const amort = anos.map(a => porAno[a].amortizacao);
  const juros = anos.map(a => porAno[a].juros);

 //  Gráfico de Linha: Amortização vs Juros (por ano)
const ctxL = document.getElementById('chartLine').getContext('2d');
if (window.lineChart) window.lineChart.destroy();
window.lineChart = new Chart(ctxL, {
  type: 'line',
  data: {
    labels: anos,
    datasets: [
      {
        label: 'Amortização',
        data: amort,
        borderColor: '#1f77b4',  
        backgroundColor: 'transparent',
        tension: 0.2
      },
      {
        label: 'Juros',
        data: juros,
        borderColor: '#ff7f0e',  
        backgroundColor: 'transparent',
        tension: 0.2
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        labels: {
          color: '#ffffff', 
          font: { size: 13 }
        }
      }
    },
    scales: {
      x: {
        title: {
          display: true,
          text: 'Ano',
          color: '#ffffff' 
        },
        ticks: {
          color: '#ffffff' 
        },
        grid: {
          color: 'rgba(255,255,255,0.2)' 
        }
      },
      y: {
        title: {
          display: true,
          text: 'Valor (€)',
          color: '#ffffff' 
        },
        ticks: {
          color: '#ffffff' 
        },
        grid: {
          color: 'rgba(255,255,255,0.2)' 
        }
      }
    }
  }
});


 // Gráfico Doughnut: Pago vs Por Pagar
const pago = Number(kpis.total_pago || 0);
const porPagar = Number(kpis.saldo_devedor || 0);

const ctxP = document.getElementById('chartPie').getContext('2d');
if (window.pieChart) window.pieChart.destroy();

window.pieChart = new Chart(ctxP, {
  type: 'doughnut',
  data: {
    labels: ['Pago', 'Por Pagar'],
    datasets: [{
      data: [pago, porPagar],
      backgroundColor: ['#4CAF50', '#E91E63']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'bottom', 
        labels: {
          color: '#ffffff', 
          font: {
            size: 12
          }
        }
      }
    }
  }
});

}


function marcarPago(id){
  Swal.fire({
    title: "Tens a certeza?",
    text: "Queres marcar esta prestação como paga?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sim, marcar como paga",
    cancelButtonText: "Cancelar",
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: 'src/controller/controllerFinancas.php',
        method: 'POST',
        data: { op: 'marcarPago', id: id },
        dataType: 'json'
      })
      .done(function(resp){
        if(resp.flag){
          Swal.fire({
            title: "Sucesso",
            text: resp.msg,
            icon: "success",
            timer: 2000,
            showConfirmButton: false
          });
          carregarDados();
        } else {
          Swal.fire({
            title: "Erro",
            text: resp.msg,
            icon: "error",
            confirmButtonText: "OK"
          });
        }
      })
      .fail(function(){
        Swal.fire({
          title: "Erro",
          text: "Erro na comunicação com o servidor",
          icon: "error"
        });
      });
    }
  });
}
