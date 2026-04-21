const PHP_CONTROLLER = "src/controller/controllerRendimentos.php";

// Obter cores de tema
function getChartColorsArray(elementId) {
  const el = document.getElementById(elementId);
  if (!el) return [];
  const rawColors = el.getAttribute("data-colors");
  if (!rawColors) return [];
  return JSON.parse(rawColors).map(color => {
    color = color.trim();
    if (color.indexOf(",") === -1) {
      return getComputedStyle(document.documentElement).getPropertyValue(color) || color;
    } else {
      const parts = color.split(",");
      return "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(parts[0]) + "," + parts[1] + ")";
    }
  });
}

let myChart = null;

// Carregar gráfico trimestral
// Carregar gráfico trimestral (substituir a função existente)
function carregarGraficoTrimestral(trimestre) {
    const chartColors = getChartColorsArray("chart-line");
    const dados = new FormData();
    dados.append("op", "graficoTrimestral");
    dados.append("trimestre", trimestre);

    fetch(PHP_CONTROLLER, { method: "POST", body: dados })
        .then(res => res.json())
        .then(obj => {
            const chartDom = document.getElementById("chart-line");
            const titulo = document.getElementById("tituloTrimestre");

            // Dispose do chart anterior se existir
            if (myChart) {
                try { myChart.dispose(); } catch(e) {}
                myChart = null;
            }

            // Atualiza título
            titulo.textContent = "Rendimentos — " + trimestre + "º Trimestre";

            if (!obj.valores || obj.valores.length === 0) {
                chartDom.innerHTML = "<p class='text-center mt-3 text-muted'>Sem dados para este trimestre</p>";
                return;
            }

            // Inicializa ECharts
            myChart = echarts.init(chartDom);

            // Opções do gráfico (com cores forçadas para branco)
            const option = {
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(50,50,50,0.85)',
                    borderColor: '#333',
                    textStyle: { color: '#fff' },
                    formatter: function (params) {
                        const ponto = params[0];
                        return `<b>${ponto.name}</b><br/>Rendimento: € ${ponto.value.toFixed(2)}`;
                    }
                },
                grid: { left: "5%", right: "5%", bottom: "10%", top: "10%", containLabel: true },

                // EIXOS (forçar labels e linhas para branco)
                xAxis: {
                    type: "category",
                    data: obj.labels,
                    axisLine: { lineStyle: { color: "#ffffff" } },
                    axisLabel: { rotate: 0, color: "#ffffff" } // label x em branco
                },
            yAxis: {
    type: "value",
    axisLine: { lineStyle: { color: "#ffffff" } },
    axisLabel: {
        color: "#ffffff",
        formatter: function (val) {
            return "€" + val.toFixed(2);
        }
    },
    splitLine: { lineStyle: { color: "rgba(255,255,255,0.06)" } }
},

                // SÉRIE
                series: [{
                    name: "Rendimentos",
                    data: obj.valores,
                    type: "line",
                    smooth: true,
                    symbol: "circle",
                    symbolSize: 8,
                    itemStyle: { color: chartColors[0] || "#28a745" },
                    lineStyle: { width: 3, color: chartColors[0] || "#28a745" },
                    emphasis: { focus: 'series' }
                }],

                // LEGENDA (ativada e forçada a branco)
                legend: {
                    show: true,
                    bottom: 0,
                    textStyle: { color: "#ffffff", fontSize: 12 }
                },

                // Texto global (fallback)
                textStyle: { color: "#ffffff", fontFamily: "Poppins, sans-serif" },

                // cores
                color: chartColors.length > 0 ? chartColors : ["#28a745"]
            };

            // Aplica opções e desenha
            myChart.setOption(option);
        })
        .catch(err => console.error("Erro ao carregar gráfico:", err));
}


document.addEventListener("DOMContentLoaded", () => {
  const select = document.getElementById("trimestreSelect");
  carregarGraficoTrimestral(select.value);
  select.addEventListener("change", e => carregarGraficoTrimestral(e.target.value));
});
