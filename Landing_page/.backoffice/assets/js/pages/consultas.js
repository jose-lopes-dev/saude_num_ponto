/* Dados de exemplo */
const dados = {
  consultas: [
    {id:1, data:'2025-09-16T09:00', cliente:'Maria Silva', profissional:'Dr. João', servico:'Personal Trainer', valor:88, estado:'Confirmada'},
    {id:2, data:'2025-09-16T11:30', cliente:'Carlos Santos', profissional:'Dra. Ana', servico:'Nutricionista', valor:120, estado:'Pendente'},
    {id:5, data:'2025-09-22T18:00', cliente:'João Gomes', profissional:'Dr. João', servico:'Personal Trainer', valor:88, estado:'Cancelada'},
    {id:6, data:'2025-09-23T09:30', cliente:'Beatriz Melo', profissional:'Dr. Pedro', servico:'Psicologia', valor:75, estado:'Pendente'},
  ],
  lembretes: [
    {cliente:'Maria Silva', texto:'Pagamento vence em 3 dias', valor:20},
    {cliente:'Dra. Ana - Nutricionista', texto:'Consultas abertas: 2', valor:0},
  ]
};

document.getElementById('btn-nova-consulta')
    .addEventListener('click', () => new bootstrap.Modal(document.getElementById('novaConsultaModal')).show());


/* —— Helpers —— */
const df = (iso)=> new Date(iso);
const fmtDataHora = (iso)=>{
  const d=df(iso);
  return d.toLocaleDateString('pt-PT',{day:'2-digit',month:'2-digit',year:'numeric'})+' '+d.toLocaleTimeString('pt-PT',{hour:'2-digit',minute:'2-digit'});
};
const somaMes = (items)=>{
  const agora = new Date();
  const m=agora.getMonth(), y=agora.getFullYear();
  return items
    .filter(c=>{const d=df(c.data); return d.getMonth()===m && d.getFullYear()===y && c.estado!=='Cancelada';})
    .reduce((t,c)=> t + (Number(c.valor)||0), 0);
};
const conta = (pred)=> dados.consultas.filter(pred).length;

/* —— KPIs —— */
function atualizarKPIs(){
  const hoje = new Date();
  const hojeStr = hoje.toISOString().slice(0,10);
  const agendadas = conta(()=>true);
  const confirmadas = conta(c=>c.estado==='Confirmada');
  const receita = somaMes(dados.consultas);

  document.getElementById('kpi-agendadas').textContent = agendadas;
  document.getElementById('kpi-confirmadas').textContent = confirmadas;
  document.getElementById('kpi-receita').textContent = '€ '+ receita.toLocaleString('pt-PT',{minimumFractionDigits:0});
}

/* —— Lembretes —— */
function desenharLembretes(){
  const box = document.getElementById('lista-lembretes');
  box.innerHTML = '';
  dados.lembretes.forEach(l=>{
    const li = document.createElement('div');
    li.className='list-group-item d-flex justify-content-between align-items-center';
    li.innerHTML = `<div><div class="fw-semibold">${l.cliente}</div><div class="text-muted small">${l.texto}</div></div>
                    <span class="badge bg-warning-subtle text-warning">€ ${l.valor}</span>`;
    box.appendChild(li);
  });
}

/* —— Consultas de Hoje —— */
function desenharHoje(){
  const hoje = new Date();
  const ymd = hoje.toISOString().slice(0,10);
  const lista = document.getElementById('consultas-hoje');
  const deHoje = dados.consultas.filter(c=>c.data.slice(0,10)===ymd);
  if(!deHoje.length){
    lista.innerHTML = `<div class="text-muted">Sem consultas hoje.</div>`;
    return;
  }
  lista.innerHTML = deHoje.map(c=>`
    <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
      <div>
        <div class="fw-semibold">${c.cliente}</div>
        <div class="text-muted small">${c.profissional} • ${c.servico}</div>
      </div>
      <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">${df(c.data).toLocaleTimeString('pt-PT',{hour:'2-digit',minute:'2-digit'})}</span>
        <span class="status-pill ${c.estado==='Confirmada'?'status-confirm':(c.estado==='Pendente'?'status-pendente':'status-cancel')}">${c.estado}</span>
      </div>
    </div>
  `).join('');
}

/* —— Tabela —— */
function desenharTabela(filtroServico='', filtroTexto=''){
  const corpo = document.getElementById('tabela-consultas');
  const txt = filtroTexto.toLowerCase();
  const filtradas = dados.consultas.filter(c =>
    (!filtroServico || c.servico===filtroServico) &&
    (!txt || [c.cliente,c.profissional,c.servico].some(v=>v.toLowerCase().includes(txt)))
  );
  corpo.innerHTML = filtradas.map(c=>`
    <tr>
      <td>${c.cliente}</td>
      <td>${c.profissional}</td>
      <td>${c.servico}</td>
      <td>${fmtDataHora(c.data)}</td>
      <td>€ ${Number(c.valor).toFixed(0)}</td>
      <td>
        <span class="status-pill ${c.estado==='Confirmada'?'status-confirm':(c.estado==='Pendente'?'status-pendente':'status-cancel')}">${c.estado}</span>
      </td>
      <td class="text-end">
        <button class="btn btn-sm btn-soft-primary me-1">Editar</button>
        <button class="btn btn-sm btn-soft-danger">Cancelar</button>
      </td>
    </tr>
  `).join('');
  document.getElementById('contador-registos').textContent = `${filtradas.length} registo(s)`;
}

/* —— Filtros (select serviços) —— */
function preencherFiltroServicos(){
  const sel = document.getElementById('filtro-servico');
  const servicos = Array.from(new Set(dados.consultas.map(c=>c.servico)));
  servicos.forEach(s=>{
    const opt=document.createElement('option'); opt.value=s; opt.textContent=s; sel.appendChild(opt);
  });
}

/* —— Calendário minimal —— */
let calRef = new Date(); // mês em foco
function tituloMes(d){ return d.toLocaleDateString('pt-PT',{month:'long', year:'numeric'}); }
function desenharCalendario(){
  const body = document.getElementById('cal-body');
  const header = document.getElementById('cal-title');
  const ano = calRef.getFullYear(), mes = calRef.getMonth();
  header.textContent = tituloMes(calRef);

  const first = new Date(ano, mes, 1);
  const startIdx = first.getDay(); // 0=Dom
  const daysInMonth = new Date(ano, mes+1, 0).getDate();

  body.innerHTML = '';
  let row = document.createElement('tr');
  // espaços antes do dia 1
  for(let i=0;i<startIdx;i++){ row.appendChild(document.createElement('td')); }

  const hojeStr = new Date().toDateString();
  for(let d=1; d<=daysInMonth; d++){
    const td = document.createElement('td');
    const data = new Date(ano, mes, d);
    td.textContent = d;
    td.dataset.date = data.toISOString().slice(0,10);
    if(data.toDateString()===hojeStr) td.classList.add('is-today');
    td.onclick = ()=> onDiaClick(td.dataset.date, td);
    row.appendChild(td);
    if((startIdx + d)%7===0 || d===daysInMonth){ body.appendChild(row); row=document.createElement('tr'); }
  }
}
function onDiaClick(ymd, td){
  // destacar
  document.querySelectorAll('.mini-calendar td').forEach(x=>x.classList.remove('active'));
  td.classList.add('active');
  // filtrar “Consultas de Hoje” e Tabela p/ esse dia
  const hojeBackup = new Date();
  const lista = document.getElementById('consultas-hoje');
  const doDia = dados.consultas.filter(c=>c.data.slice(0,10)===ymd);
  lista.innerHTML = doDia.length ? doDia.map(c=>`
    <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
      <div><div class="fw-semibold">${c.cliente}</div><div class="text-muted small">${c.profissional} • ${c.servico}</div></div>
      <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">${df(c.data).toLocaleTimeString('pt-PT',{hour:'2-digit',minute:'2-digit'})}</span>
        <span class="status-pill ${c.estado==='Confirmada'?'status-confirm':(c.estado==='Pendente'?'status-pendente':'status-cancel')}">${c.estado}</span>
      </div>
    </div>
  `).join('') : `<div class="text-muted">Sem consultas em ${ymd.split('-').reverse().join('/')}</div>`;
  // filtrar tabela
  const texto = document.getElementById('filtro-texto').value;
  desenharTabela(document.getElementById('filtro-servico').value, texto);
  // destacar apenas as linhas do dia selecionado (opcional: poderias esconder as outras)
  document.querySelectorAll('#tabela-consultas tr').forEach(tr=>{
    const dataTxt = tr.children[3]?.textContent||'';
    tr.style.opacity = dataTxt.includes(ymd.split('-').reverse().join('/')) ? '1' : '0.4';
  });
}

/* —— Ligações UI —— */
document.getElementById('cal-prev').addEventListener('click', ()=>{ calRef.setMonth(calRef.getMonth()-1); desenharCalendario(); });
document.getElementById('cal-next').addEventListener('click', ()=>{ calRef.setMonth(calRef.getMonth()+1); desenharCalendario(); });

document.getElementById('filtro-servico').addEventListener('change', ()=>{
  desenharTabela(document.getElementById('filtro-servico').value, document.getElementById('filtro-texto').value);
});
document.getElementById('filtro-texto').addEventListener('input', ()=>{
  desenharTabela(document.getElementById('filtro-servico').value, document.getElementById('filtro-texto').value);
});
document.getElementById('btn-limpar').addEventListener('click', ()=>{
  document.getElementById('filtro-servico').value='';
  document.getElementById('filtro-texto').value='';
  desenharTabela();
});

/* —— Boot —— */
preencherFiltroServicos();
atualizarKPIs();
desenharLembretes();
desenharHoje();
desenharTabela();
desenharCalendario();
