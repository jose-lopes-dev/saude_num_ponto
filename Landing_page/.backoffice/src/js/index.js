// dashboard.js - fetch simples (sem $.post)
(function () {
  'use strict';

  function call(op) {
    // retorna Promise com JSON parseado
    return fetch('src/controller/controllerindex.php', {
      method: 'POST',
      body: new URLSearchParams({ op: op })
    })
    .then(resp => {
      return resp.json();
    });
  }

  // formata em euro pt-PT
  function fmtEUR(num) {
    if (num === null || num === undefined || isNaN(num)) return '€0,00';
    return new Intl.NumberFormat('pt-PT', { style: 'currency', currency: 'EUR' }).format(Number(num));
  }

  function setText(id, text) {
    const el = document.getElementById(id);
    if (el) el.innerText = text;
  }

  // Define percent text and color based on sign (green for >=0, red for <0)
  function setPercent(id, value) {
    const el = document.getElementById(id);
    if (!el) return;
    const num = (value === null || value === undefined || isNaN(Number(value))) ? 0 : Number(value);
    const text = (num >= 0 ? '+' : '') + num + ' %';
    el.innerText = text;
    el.classList.remove('text-success', 'text-danger');
    el.classList.add(num >= 0 ? 'text-success' : 'text-danger');
  }

  // Carregar e preencher cartões
  function carregarSaldoTotal() {
    call(1).then(d => {
      setText('saldoTotal', fmtEUR(d.saldo_raw));
      setPercent('percentualSaldo', d.percentagemRaw);
    }).catch(e => console.error('Erro saldo:', e));
  }

  function carregarCustosSetembro() {
    call(2).then(d => {
      setText('custosSetembro', fmtEUR(d.total_raw));
      setPercent('percentualCustos', d.percentagem);
    }).catch(e => console.error('Erro custos:', e));
  }

  function carregarRendimentosSetembro() {
    call(3).then(d => {
      setText('rendimentosSetembro', fmtEUR(d.rendimentos_raw));
      setPercent('percentualRendimentos', d.percentagem);
    }).catch(e => console.error('Erro rendimentos:', e));
  }

  function carregarRAISetembro() {
    call(4).then(d => {
      setText('raiSetembro', fmtEUR(d.rai_raw));
      setPercent('percentualRAI', d.percentagem);
    }).catch(e => console.error('Erro RAI:', e));
  }

  // Gráfico mensal
  function carregarGraficoMensal() {
    call(5).then(d => {
      const labels = ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"];
      const ctx = document.getElementById('lineChart');
      if (!ctx) return;

      // garantir altura mínima
      ctx.style.minHeight = '320px';
      ctx.style.height = '320px';

      // destruir chart existente se houver
      if (ctx.__chartInstance) {
        try { ctx.__chartInstance.destroy(); } catch(e) {}
      }

      const cfg = {
        type: 'line',
        data: {
          labels: labels,
          datasets: [
            {
              label: 'Custos',
              data: d.custos || [],
              borderColor: 'rgba(255,99,132,1)',
              backgroundColor: 'rgba(255,99,132,0.25)',
              tension: 0.3,
              fill: true,
              pointRadius: 4
            },
            {
              label: 'Rendimentos',
              data: d.rendimentos || [],
              borderColor: 'rgba(75,192,192,1)',
              backgroundColor: 'rgba(75,192,192,0.25)',
              tension: 0.3,
              fill: true,
              pointRadius: 4
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              labels: { color: '#ffffff' }
            }
          },
          scales: {
            x: {
              ticks: { color: '#cccccc' },
              grid: { color: 'rgba(255,255,255,0.05)' }
            },
            y: {
              beginAtZero: true,
              ticks: {
                color: '#cccccc',
                callback: (val) => '€' + val
              },
              grid: { color: 'rgba(255,255,255,0.05)' }
            }
          }
        }
      };

      ctx.__chartInstance = new Chart(ctx, cfg);

      // em resize, pedir resize do chart
      window.addEventListener('resize', () => {
        if (ctx.__chartInstance) ctx.__chartInstance.resize();
      });
    }).catch(e => console.error('Erro grafico:', e));
  }

  // Inicialização
  window.addEventListener('DOMContentLoaded', () => {
    carregarSaldoTotal();
    carregarCustosSetembro();
    carregarRendimentosSetembro();
    carregarRAISetembro();
    carregarGraficoMensal();

    // se usas flatpickr no front, podes inicializar aqui (não obrigatório)
    if (typeof flatpickr !== 'undefined' && document.querySelector('#date-range')) {
      try {
        flatpickr('#date-range', {
          mode: 'range',
          dateFormat: 'Y-m-d',
          locale: 'pt',
          onClose: function() {
            if (typeof filtrarTarefas === 'function') filtrarTarefas();
          }
        });
      } catch (e) { console.warn('flatpickr init failed', e); }
    }
  });

})();
