/* ---------- Demo data (trocar por API quando tiveres) ---------- */
// Packs por mês (Jul, Ago, Set)
const dataPacks = {
  meses: ["Julho","Agosto","Setembro"],
  series: [
    {name:"Médio",  data:[35,28,22]},
    {name:"Pro",     data:[18,24,31]},
    {name:"Duo",     data:[12,16,10]},
    {name:"Lar",   data:[8, 10, 14]},
  ]
};
// Individuais & Grupo por mês
const dataIndivGrupo = {
  "2025-07": { indiv:{Consultas:44, Sessões:56}, grupo:{HIIT:35, Yoga:28, "Aulas PT":15} },
  "2025-08": { indiv:{Consultas:38, Sessões:49}, grupo:{HIIT:32, Yoga:26, "Aulas PT":18} },
  "2025-09": { indiv:{Consultas:52, Sessões:41}, grupo:{HIIT:29, Yoga:24, "Aulas PT":12} },
};
// Faturação
const dataFaturacao = [
  {cliente:"Maria Silva", servico:"Personal Trainer", data:"2025-09-12", valor:88,  estado:"Pago"},
  {cliente:"Carlos Santos", servico:"Nutricionista", data:"2025-09-12", valor:120, estado:"Pendente"},
  {cliente:"João Gomes", servico:"Personal Trainer", data:"2025-09-10", valor:88,  estado:"Pago"},
  {cliente:"Beatriz Melo", servico:"Psicologia", data:"2025-09-09", valor:75,  estado:"Cancelado"},
];

/* ---------- Packs: Bar chart + Tabela ---------- */
(function renderPacks(){
  const options = {
    chart:{ type:"bar", height:320, toolbar:{show:false}, foreColor:"#c7c7c7" },
    series: dataPacks.series,
    xaxis:{ categories:dataPacks.meses, labels:{ style:{fontSize:"12px"} } },
    plotOptions:{ bar:{ columnWidth:"48%", borderRadius:4 } },
    dataLabels:{ enabled:false },
    grid:{ borderColor:"rgba(255,255,255,.08)" },
    tooltip:{ theme:"dark" },
    colors:["#7aa9ff","#64ca9b","#ffda8e","#69badf"]
  };
  const chart = new ApexCharts(document.querySelector("#chart-packs"), options);
  chart.render();

  // tabela
  const tbody = document.getElementById("tbl-packs");
  const rows = dataPacks.meses.map((mes,idx)=>{
    const vals = dataPacks.series.map(s=>s.data[idx]);
    return `<tr>
      <td>${mes}</td>
      ${vals.map(v=>`<td class="text-end">${v}</td>`).join("")}
    </tr>`;
  }).join("");
  tbody.innerHTML = rows;
})();

/* ---------- Individuais & Grupo: Donuts com selector de mês ---------- */
let chartIndiv=null, chartGrupo=null;
function donutOptions(labels, series){
  return {
    chart:{ type:"donut", height:320, toolbar:{show:false}, foreColor:"#c7c7c7" },
    labels, series,
    dataLabels:{ enabled:false },
    stroke:{ width:0 },
    legend:{ position:"bottom" },
    tooltip:{ theme:"dark" },
    colors:["#89A000","#7aa9ff","#69badf","#ffda8e","#64ca9b"]
  };
}
function setMonth(monthKey){
  document.querySelectorAll('.month-switch .btn').forEach(b=>b.classList.toggle('active', b.dataset.month===monthKey));
  const d = dataIndivGrupo[monthKey];

  const indivLabels = Object.keys(d.indiv);
  const indivSeries = Object.values(d.indiv);
  const grupoLabels = Object.keys(d.grupo);
  const grupoSeries = Object.values(d.grupo);

  if(!chartIndiv){
    chartIndiv = new ApexCharts(document.querySelector("#chart-individuais"), donutOptions(indivLabels, indivSeries));
    chartGrupo = new ApexCharts(document.querySelector("#chart-grupo"), donutOptions(grupoLabels, grupoSeries));
    chartIndiv.render(); chartGrupo.render();
  }else{
    chartIndiv.updateOptions({labels:indivLabels}); chartIndiv.updateSeries(indivSeries);
    chartGrupo.updateOptions({labels:grupoLabels}); chartGrupo.updateSeries(grupoSeries);
  }

  // tabela resumo
  const totInd = indivSeries.reduce((a,b)=>a+b,0);
  const totGrp = grupoSeries.reduce((a,b)=>a+b,0);
  document.getElementById("tbl-indiv-grupo").innerHTML = `
    <tr><td>Julho</td><td class="text-end">${sumMonth("2025-07","indiv")}</td><td class="text-end">${sumMonth("2025-07","grupo")}</td><td class="text-end">${sumMonth("2025-07","indiv")+sumMonth("2025-07","grupo")}</td></tr>
    <tr><td>Agosto</td><td class="text-end">${sumMonth("2025-08","indiv")}</td><td class="text-end">${sumMonth("2025-08","grupo")}</td><td class="text-end">${sumMonth("2025-08","indiv")+sumMonth("2025-08","grupo")}</td></tr>
    <tr><td>Setembro</td><td class="text-end">${sumMonth("2025-09","indiv")}</td><td class="text-end">${sumMonth("2025-09","grupo")}</td><td class="text-end">${sumMonth("2025-09","indiv")+sumMonth("2025-09","grupo")}</td></tr>
  `;
}
function sumMonth(key, type){
  const o = dataIndivGrupo[key][type];
  return Object.values(o).reduce((a,b)=>a+b,0);
}
document.querySelectorAll('.month-switch .btn').forEach(b=>{
  b.addEventListener('click', ()=> setMonth(b.dataset.month));
});
setMonth("2025-09"); // mês inicial

/* ---------- Faturação: tabela + modal upload (demo) ---------- */
(function renderFaturacao(){
  const tb = document.getElementById('tbl-faturacao');
  tb.innerHTML = dataFaturacao.map(r=>`
    <tr>
      <td>${r.cliente}</td>
      <td>${r.servico}</td>
      <td>${new Date(r.data).toLocaleDateString('pt-PT')}</td>
      <td class="text-end">€ ${r.valor}</td>
      <td>
        <span class="badge ${r.estado==='Pago'?'bg-success-subtle text-success':r.estado==='Pendente'?'bg-warning-subtle text-warning':'bg-danger-subtle text-danger'}">${r.estado}</span>
      </td>
      <td class="text-end">
        <button class="btn btn-sm btn-soft-primary me-1"><i class="ri-mail-send-line"></i></button>
        <button class="btn btn-sm btn-soft-secondary"><i class="ri-file-download-line"></i></button>
      </td>
    </tr>
  `).join('');
  document.getElementById('faturacao-info').textContent = `Carregado ${dataFaturacao.length} registo(s).`;
})();

// Submit modal (demo). Trocar por POST real.
document.getElementById('form-fatura').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const fd = new FormData(e.currentTarget);
  const enviar = document.getElementById('chk-enviar').checked;
  // TODO: await fetch('/api/faturas', {method:'POST', body: fd});
  alert(`Fatura guardada para ${fd.get('cliente')} (${fd.get('email')})${enviar?' e enviada por email':''}.`);
  bootstrap.Modal.getInstance(document.getElementById('mdl-upload-fatura')).hide();
  e.target.reset();
});