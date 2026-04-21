function getChartColorsArray(e) {
    if (document.getElementById(e) !== null) {
        var t = document.getElementById(e).getAttribute("data-colors");
        if (t) {
            return JSON.parse(t).map(function (e) {
                var t = e.replace(" ", "");
                if (t.indexOf(",") === -1) {
                    return getComputedStyle(document.documentElement).getPropertyValue(t) || t;
                } else {
                    e = e.split(",");
                    if (e.length === 2) {
                        return "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(e[0]) + "," + e[1] + ")";
                    } else {
                        return t;
                    }
                }
            });
        }
        console.warn("data-colors attributes not found on", e);
    }
}

var options, chart,
    linechartcustomerColors = getChartColorsArray("customer_impression_charts"),
    chartDonutBasicColors = getChartColorsArray("store-visits-source");

// Gráfico principal (mantido igual ao original)
if (linechartcustomerColors) {
    options = {
        series: [
            { name: "Orders", type: "area", data: [34, 65, 46, 68, 49, 61, 42, 44, 78, 52, 63, 67] },
            { name: "Earnings", type: "bar", data: [89.25, 98.58, 68.74, 108.87, 77.54, 84.03, 51.24, 28.57, 92.57, 42.36, 88.51, 36.57] },
            { name: "Refunds", type: "line", data: [8, 12, 7, 17, 21, 11, 5, 9, 7, 29, 12, 35] }
        ],
        chart: { height: 370, type: "line", toolbar: { show: false } },
        stroke: { curve: "straight", dashArray: [0, 0, 8], width: [2, 0, 2.2] },
        fill: { opacity: [0.1, 0.9, 1] },
        markers: { size: [0, 0, 0], strokeWidth: 2, hover: { size: 4 } },
        xaxis: {
            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            axisTicks: { show: false },
            axisBorder: { show: false }
        },
        grid: {
            show: true,
            xaxis: { lines: { show: true } },
            yaxis: { lines: { show: false } },
            padding: { top: 0, right: -2, bottom: 15, left: 10 }
        },
        legend: {
            show: true,
            horizontalAlign: "center",
            offsetX: 0,
            offsetY: -5,
            markers: { width: 9, height: 9, radius: 6 },
            itemMargin: { horizontal: 10, vertical: 0 }
        },
        plotOptions: { bar: { columnWidth: "30%", barHeight: "70%" } },
        colors: linechartcustomerColors,
        tooltip: {
            shared: true,
            y: [
                { formatter: function (e) { return e !== undefined ? e.toFixed(0) : e; } },
                { formatter: function (e) { return e !== undefined ? "€" + e.toFixed(2) : e; } },
                { formatter: function (e) { return e !== undefined ? e.toFixed(0) + " Sales" : e; } }
            ]
        }
    };
    chart = new ApexCharts(document.querySelector("#customer_impression_charts"), options);
    chart.render();
}

// Gráfico donut — com percentagens atualizadas
if (chartDonutBasicColors) {
    options = {
        series: [65.23, 10.91, 17.39, 6.48],
        labels: ["Saldo Total", "Custos", "Rendimentos", "Lucro"],
        chart: { height: 333, type: "donut" },
        legend: { position: "bottom" },
        stroke: { show: false },
        dataLabels: { dropShadow: { enabled: false } },
        colors: chartDonutBasicColors,
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toFixed(2) + "%";
                }
            }
        }
    };
    chart = new ApexCharts(document.querySelector("#store-visits-source"), options);
    chart.render();
}
