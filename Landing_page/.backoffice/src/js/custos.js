// ================================
// 📊 CUSTOS E RENDIMENTOS - JS
// ================================

// ----- GRÁFICO DONUT (Custos por mês) -----
function carregarGraficoCustos(mes) {
    const chartDom = document.getElementById("custos_donut_chart");
    chartDom.innerHTML = "";

    const dados = new FormData();
    dados.append("mes", mes);

    fetch("src/controller/controllerCustos.php", { method: "POST", body: dados })
        .then(res => res.json())
        .then(obj => {
            if (!obj.valores || obj.valores.length === 0) {
                chartDom.innerHTML = "<p class='text-center'>Nenhum dado para este mês.</p>";
                return;
            }

            const palette = [
                '#008FFB', '#00E396', '#FEB019', '#FF4560',
                '#775DD0', '#546E7A', '#26a69a', '#D10CE8'
            ];
            const cores = obj.valores.map((_, i) => palette[i % palette.length]);

            const chart = new ApexCharts(chartDom, {
                chart: {
                    type: 'donut',
                    height: 340,
                    toolbar: { show: false }
                },
                series: obj.valores,
                labels: obj.descricoes,
                colors: cores,
                legend: {
                    position: 'bottom',
                    labels: { colors: '#ccc' }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val.toFixed(1) + "%"; // Percentagens no donut
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "€ " + val.toFixed(2);
                        }
                    }
                }
            });

            chart.render();
        })
        .catch(err => {
            console.error("Erro ao carregar gráfico de custos:", err);
            chartDom.innerHTML = "<p class='text-center text-danger'>Erro ao carregar dados.</p>";
        });
}

const mesSelect = document.getElementById("mesSelect");
if (mesSelect) {
    mesSelect.addEventListener("change", e => carregarGraficoCustos(e.target.value));
    carregarGraficoCustos(mesSelect.value);
}


// ----- GRÁFICO DE LINHA (Evolução de Gastos) -----
function carregarGraficoEvolucao() {
    const chartDom = document.getElementById("line_chart_basic");
    chartDom.innerHTML = "";
    chartDom.style.minHeight = "380px";
    chartDom.style.maxHeight = "400px";
    chartDom.style.overflow = "hidden";

    const dados = new FormData();
    dados.append("op", "evolucaoGastos");

    fetch("src/controller/controllerCustos.php", { method: "POST", body: dados })
        .then(res => res.json())
        .then(obj => {
            if (!obj.valores || obj.valores.length === 0) {
                chartDom.innerHTML = "<p class='text-center'>Nenhum dado para mostrar.</p>";
                return;
            }

            const chart = new ApexCharts(chartDom, {
                chart: {
                    type: 'line',
                    height: 380,
                    toolbar: { show: false },
                    foreColor: '#ccc'
                },
                series: [{
                    name: "Gastos",
                    data: obj.valores
                }],
                xaxis: {
                    categories: obj.labels,
                    labels: { rotate: -45 },
                    axisBorder: { color: '#555' },
                    axisTicks: { color: '#555' }
                },
                yaxis: {
                    labels: {
                        formatter: val => "€" + val.toFixed(2),
                        style: { colors: '#ccc' }
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 4,
                    colors: ['#00B050'],
                    strokeWidth: 2,
                    strokeColors: '#1a1a1a'
                },
                tooltip: {
                    y: { formatter: val => "€ " + val.toFixed(2) }
                },
                dataLabels: { enabled: false }, // 🔹 remove os quadrados verdes
                colors: ['#00B050'],
                grid: {
                    borderColor: '#444',
                    padding: { bottom: 0, top: 0 }
                },
                legend: { show: false }
            });

            chart.render();
        })
        .catch(err => {
            console.error("Erro ao carregar gráfico de evolução:", err);
            chartDom.innerHTML = "<p class='text-center text-danger'>Erro ao carregar dados.</p>";
        });
}

carregarGraficoEvolucao();


// ----- FIXA TÍTULO DO GRÁFICO DE RENDIMENTOS -----
document.addEventListener("DOMContentLoaded", function () {
    const titulo = document.getElementById("tituloTrimestre");
    const select = document.getElementById("trimestreSelect");

    if (titulo && select) {
        titulo.textContent = "Rendimentos";
        select.addEventListener("change", () => (titulo.textContent = "Rendimentos"));
        setInterval(() => {
            if (titulo.textContent !== "Rendimentos") titulo.textContent = "Rendimentos";
        }, 500);
    }
});
