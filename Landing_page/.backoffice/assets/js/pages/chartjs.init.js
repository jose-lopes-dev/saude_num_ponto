function getChartColorsArray(r) {
    if (null !== document.getElementById(r))
        return (
            (r = document.getElementById(r).getAttribute("data-colors")),
            (r = JSON.parse(r)).map(function (r) {
                var o = r.replace(" ", "");
                return -1 === o.indexOf(",")
                    ? getComputedStyle(document.documentElement).getPropertyValue(o) || o
                    : 2 == (r = r.split(",")).length
                    ? "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(r[0]) + "," + r[1] + ")"
                    : o;
            })
        );
}

// Defaults
Chart.defaults.borderColor = "rgba(133, 141, 152, 0.1)";
Chart.defaults.color = "#858d98";

// ========== LINE CHART ==========
const ctxLine = document.getElementById("lineChart");
if (ctxLine) {
    new Chart(ctxLine, {
        type: "line",
        data: {
            labels: [
                "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
                "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
            ],
            datasets: [{
                label: "Saldo Mensal",
                data: [65, 59, 80, 81, 56, 55, 40, 55, 30, 80, 95, 100],
                borderColor: "#007bff",
                backgroundColor: "rgba(0,123,255,0.2)",
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true }
    });
}

// ========== BAR CHART ==========
const ctxBar = document.getElementById("bar");
if (ctxBar) {
    new Chart(ctxBar, {
        type: "bar",
        data: {
            labels: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho"],
            datasets: [{
                label: "Vendas",
                data: [65, 59, 81, 45, 56, 80],
                backgroundColor: "#28a745"
            }]
        },
        options: { responsive: true }
    });
}

// ========== PIE CHART ==========
const ctxPie = document.getElementById("pieChart");
if (ctxPie) {
    new Chart(ctxPie, {
        type: "pie",
        data: {
            labels: ["Desktops", "Tablets"],
            datasets: [{
                data: [300, 180],
                backgroundColor: ["#007bff", "#ffc107"]
            }]
        },
        options: { responsive: true }
    });
}

// ========== DOUGHNUT ==========
const ctxDoughnut = document.getElementById("doughnut");
if (ctxDoughnut) {
    new Chart(ctxDoughnut, {
        type: "doughnut",
        data: {
            labels: ["Desktops", "Tablets"],
            datasets: [{
                data: [200, 150],
                backgroundColor: ["#dc3545", "#17a2b8"]
            }]
        },
        options: { responsive: true }
    });
}

// ========== POLAR AREA ==========
const ctxPolar = document.getElementById("polarArea");
if (ctxPolar) {
    new Chart(ctxPolar, {
        type: "polarArea",
        data: {
            labels: ["Series 1", "Series 2", "Series 3", "Series 4"],
            datasets: [{
                data: [11, 16, 7, 18],
                backgroundColor: ["#007bff", "#28a745", "#ffc107", "#dc3545"]
            }]
        },
        options: { responsive: true }
    });
}

// ========== RADAR ==========
const ctxRadar = document.getElementById("radar");
if (ctxRadar) {
    new Chart(ctxRadar, {
        type: "radar",
        data: {
            labels: ["Comer", "Dormir", "Codar", "Estudar", "Jogar", "Ler", "Correr"],
            datasets: [
                {
                    label: "Dataset 1",
                    data: [65, 59, 90, 81, 56, 55, 40],
                    backgroundColor: "rgba(0,123,255,0.2)",
                    borderColor: "#007bff"
                },
                {
                    label: "Dataset 2",
                    data: [28, 48, 40, 19, 96, 27, 100],
                    backgroundColor: "rgba(40,167,69,0.2)",
                    borderColor: "#28a745"
                }
            ]
        },
        options: { responsive: true }
    });
}
