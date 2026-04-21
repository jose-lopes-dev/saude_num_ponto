// Helpers bem simples
function pad(n){ return String(n).padStart(2,'0'); }
function hojeLocal(){
  const d = new Date();
  return d.getFullYear()+"-"+pad(d.getMonth()+1)+"-"+pad(d.getDate()); 
}
function money(v){ v=Number(v||0); return "€ "+v.toLocaleString('pt-PT',{minimumFractionDigits:2}); }

function parseValor(v){
  if (v == null) return 0;
  let s = String(v).replace('€','').trim().replace(/\s+/g,'');
  if (s.indexOf(',') >= 0 && s.indexOf('.') < 0) s = s.replace(',', '.');
  const n = Number(s);
  return isNaN(n) ? 0 : n;
}

// ---------- KPIs (op=3) ----------
let calRef = new Date();
calRef.setFullYear(calRef.getFullYear(), calRef.getMonth(), 1);
var calSelectedDate = null;

function carregarKPIsConsultas(){
  let dados = new FormData();
  dados.append('op', 3);
  dados.append('year', calRef.getFullYear());
  dados.append('month', calRef.getMonth()+1);

  $.ajax({
    url:'src/controller/controllerConsultas.php',
    method:'POST',
    data:dados,
    contentType:false,
    processData:false,
    dataType:'json'            
  }).done(function(k){         
    $('#kpi-agendadas').text(k.agendadas||0);
    $('#kpi-concluidas').text(k.concluidas||0);
    $('#kpi-receita').text(money(k.receita||0));
  }).fail(function(xhr){
    console.error('KPIs:', xhr.responseText);
  });
}

// ---------- CALENDÁRIO (op=4) ----------
let eventosMes = {};
function renderConsultasDoDia(dateStr){
  const arr = eventosMes[dateStr]||[];
  if(!arr.length){ $('#consultas-hoje').html('<div class="text-muted">Sem consultas para '+dateStr+'</div>'); return; }
  let html='';
  arr.forEach(function(it){
    html += `
      <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
        <div>
          <div class="fw-semibold">${it.hora_consulta || it.hora || ''} — ${it.cliente}</div>
          <div class="text-muted fs-12">${it.servico}${it.estado?' • '+it.estado:''}</div>
        </div>
        <div class="fw-semibold">${money(parseValor(it.preco ?? it.valor))}</div>
      </div>`;
  });
  $('#consultas-hoje').html(html);
}

function renderCalendar(){
  const y = calRef.getFullYear();
  const m = calRef.getMonth(); 
  const nomes = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
  $('#cal-title').text(nomes[m]+' '+y);

  const firstWeekday = new Date(y, m, 1).getDay(); 
  const lastDay = new Date(y, m+1, 0).getDate();
  let html = '<tr class="text-center">', col=0;

  for(let i=0;i<firstWeekday;i++){
    html += '<td></td>';
    col++;
  }

  const hoje = hojeLocal();
  for(let d=1; d<=lastDay; d++){
    const ds  = y+'-'+pad(m+1)+'-'+pad(d);
    const has = (eventosMes[ds]||[]).length;

    let cls = 'p-0 day-cell text-center';
    if(has)      cls += ' has-event';
    if(ds===hoje) cls += ' today';

    html += `
      <td data-date="${ds}" class="${cls}" style="height:42px;cursor:pointer">
        <div class="d-flex flex-column align-items-center">
          <span class="fw-semibold">${d}</span>
          ${has
            ? '<span class="badge rounded-pill bg-primary-subtle mt-1">'+has+'</span>'
            : '<span class="mt-1" style="height:.6rem"></span>'}
        </div>
      </td>`;

    col++;
    if(col===7 && d!==lastDay){
      html += '</tr><tr class="text-center">';
      col = 0;
    }
  }

  if(col>0 && col<7){
    for(let i=col; i<7; i++) html += '<td></td>';
    html += '</tr>';
  }

  $('#cal-body').html(html);

  $('#cal-body .day-cell').off('click').on('click', function(){
    $('#cal-body .day-cell.selected').removeClass('selected');
    $(this).addClass('selected');

    calSelectedDate = $(this).data('date');
    renderConsultasDoDia(calSelectedDate);
  });
}

function carregarEventosMes(){
  let dados = new FormData();
  dados.append('op', 4);
  dados.append('year', calRef.getFullYear());
  dados.append('month', calRef.getMonth()+1);

  $.ajax({
    url:'src/controller/controllerConsultas.php',
    method:'POST',
    data:dados,
    contentType:false,
    processData:false,
    dataType:'json'
  }).done(function(rows){
    eventosMes = {};
    (rows||[]).forEach(function(r){
    const d = r.date || r.data_consulta;
    if(!d) return; (eventosMes[d] = eventosMes[d] || []).push(r);
  });
    renderCalendar();
    renderConsultasDoDia(hojeLocal());
  }).fail(function(xhr){
    console.error('Eventos:', xhr.responseText);
  });
}

// -------- PREÇO: serviço base + extras --------
let precoBase  = 0;
let precoExtra = 0;

function atualizarValorTotal() {
  const total = precoBase + precoExtra;
  const $preco = $('[name="valor"]');

  if (total > 0) {
    const txt = total.toLocaleString('pt-PT', { minimumFractionDigits: 2 });
    $preco.val(txt).prop('readonly', true);
  } else {
    $preco.val('').prop('readonly', false);
  }
}

function atualizarLabelExtras() {
  const ids = $('#sel-servico-extra').val() || [];
  let txt = 'Selecionar extras...';

  if (ids.length === 1) {
    txt = '1 extra selecionado';
  } else if (ids.length > 1) {
    txt = ids.length + ' extras selecionados';
  }

  $('#label-extras').text(txt);
}

// --------- SERVIÇO EXTRA (select + tabela) ----------
function recarregarExtras () {
  const $sel = $('#sel-servico-extra');
  const ids  = $sel.val() || [];          // array de ids selecionados

  let totalExtra = 0;
  let linhas     = '';

  ids.forEach(function (id) {
    const $opt   = $sel.find('option[value="' + id + '"]');
    const nome   = $opt.text().trim();
    const preco  = Number($opt.data('preco') || 0);

    totalExtra += preco;

    linhas += `
      <tr>
        <td>${nome}</td>
        <td class="text-end">€ ${preco.toFixed(2)}</td>
      </tr>`;
  });

  // Atualiza a tabela/lista
  if (!linhas) {
    $('#lista-extras').html(
      '<tr><td colspan="2" class="text-muted">Sem extras selecionados</td></tr>'
    );
  } else {
    $('#lista-extras').html(linhas);
  }

  // Atualiza o total
  precoExtra = totalExtra;
  atualizarValorTotal();
}

// Quando se marca/desmarca um extra na dropdown
$(document).on('change', '.chk-extra', function () {
  const ids = $('.chk-extra:checked')
    .map(function () { return $(this).val(); })
    .get();

  // sincroniza com o <select> escondido
  $('#sel-servico-extra').val(ids);

  // atualiza label e tabela + preço
  atualizarLabelExtras();
  recarregarExtras();
});

// ---------- CRUD (op=2,5,6,7) ----------
function abrirEditar(id){
  var dados = new FormData();
  dados.append('op', 2);
  dados.append('id', id);

  $.ajax({
    url:'src/controller/controllerConsultas.php',
    method:'POST',
    data:dados,
    contentType:false,
    processData:false,
    dataType:'json'
  }).done(function(r){
    var f = document.getElementById('form-nova-consulta');

    var dataISO = (r.data || r.data_consulta) || '';
    if (dataISO === '0000-00-00') dataISO = '';

    f.data.value = dataISO;

    if (fpData) {
      fpData.set('minDate', null);

      if (dataISO) fpData.setDate(dataISO, true, "Y-m-d");
      else fpData.clear();
    }
    f.hora.value = (r.hora_consulta || r.hora) || '';
    f.valor.value     = (r.preco ?? r.valor ?? '');
    f.id_estado.value = r.id_estado || 15;

    $('#sel-cliente').empty().append(
      $('<option>', {value: r.id_cliente, text: r.cliente || r.id_cliente, selected: true})
    ).trigger('change');

    $('#sel-prof').empty().append(
      $('<option>', {value: r.id_prestador, text: r.profissional || r.id_prestador, selected: true})
    ).trigger('change');

    $('#sel-servico').val(r.id_servico || '0').trigger('change');

    var extras = Array.isArray(r.extras) ? r.extras.map(String) : [];

    $('.chk-extra').each(function () {
      var id = $(this).val();
      $(this).prop('checked', extras.indexOf(id) >= 0);
    });

    $('#sel-servico-extra').val(extras);

    atualizarLabelExtras();
    recarregarExtras();

    var $form = $('#form-nova-consulta');
    $form.data('id',             r.id             || null);
    $form.data('id_cliente',     r.id_cliente     || null);
    $form.data('id_prestador',   r.id_prestador   || null);
    $form.data('id_servico',     r.id_servico     || null);
    $form.data('id_estado',      r.id_estado      || null);

    $(f).closest('.modal-content').find('.modal-title').text('Editar Consulta');
    $(f).find('button[type="submit"]').text('Guardar alterações');

    new bootstrap.Modal('#novaConsultaModal').show();
  }).fail(function(xhr){
    console.error('Consulta:', xhr.responseText);
    Swal.fire({icon:'error', title:'Erro', text:'Não foi possível carregar a consulta.'});
  });
}

function apagarConsulta(id){
  Swal.fire({
    title: 'Tem a certeza?',
    text: 'Esta ação não pode ser revertida.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sim, apagar',
    cancelButtonText: 'Cancelar'
  }).then((res)=>{
    if(!res.isConfirmed) return;

    let dados = new FormData();
    dados.append('op', 7);
    dados.append('id', id);

  $.ajax({
    url:'src/controller/controllerConsultas.php',
    method:'POST',
    data:dados,
    contentType:false,
    processData:false,
    dataType:'json'
  }).done(function(r){
    if(r.ok){
      Swal.fire({icon:'success', title:'Apagado', timer:1300, showConfirmButton:false});
      carregarConsultas(consPag); carregarKPIsConsultas(); carregarEventosMes();
    }else{
      Swal.fire({icon:'error', title:'Erro ao apagar', text:(r.msg||'Tenta novamente')});
    }
  }).fail(function(xhr){
    Swal.fire({icon:'error', title:'Erro de rede', text:'Verifica a ligação.'});
  });
  });
}

function initSelects(){
    var $parent = $("#novaConsultaModal");

// ---------- Clientes (op=8) ----------
    $("#sel-cliente").select2({
        dropdownParent: $parent,
        placeholder: "Procurar cliente…",
        minimumInputLength: 1,
        ajax: {
            url: "src/controller/controllerConsultas.php",
            type: "POST",
            dataType: "json",
            delay: 200,
            data: function (params) {
                return { op: 8, q: params.term || "" };
            },
            processResults: function (data) {
                var arr = Array.isArray(data) ? data : [];
                return {
                    results: arr.map(function (x) {
                        var id = x.codigo != null ? x.codigo : x.id;
                        var text =
                            x.nome != null
                                ? x.nome
                                : x.text || x.codigo || x.id;
                        return { id: id, text: text };
                    }),
                };
            },
        },
    });

    // ---------- Profissionais (op=9) ----------
    $("#sel-prof").select2({
        dropdownParent: $parent,
        placeholder: "Procurar profissional…",
        minimumInputLength: 1,
        ajax: {
            url: "src/controller/controllerConsultas.php",
            type: "POST",
            dataType: "json",
            delay: 200,
            data: function (params) {
                return { op: 9, q: params.term || "" };
            },
            processResults: function (data) {
                var arr = Array.isArray(data) ? data : [];
                return {
                    results: arr.map(function (x) {
                        var id = x.id != null ? x.id : x.codigo;
                        var text =
                            x.nome != null
                                ? x.nome
                                : x.text || x.codigo || x.id;
                        return { id: id, text: text };
                    }),
                };
            },
        },
    });
}
// ---------- TABELA PAGINADA (op=10) ----------
var consLim = 10;
var consPag = 0;
var consQ   = ""; 

function carregarConsultas(pag){
  consPag = Number(pag) || 0;
  var off = consPag * consLim;

  $.ajax({
    url: 'src/controller/controllerConsultas.php',
    method: 'POST',
    data: { op: 10, lim: consLim, off: off, q: consQ },
    success: function(resp){
      var data = resp;
      if (typeof data === 'string') { try { data = JSON.parse(data); } catch(e) {} }

      var rows = (data && data.rows) ? data.rows : [];
      var $body = $('#tabela-consultas');

      if (!rows.length){
        $body.html('<tr><td colspan="7">Sem registos nesta página.</td></tr>');
        $('#consultas-info').text('Página ' + (consPag+1) + ' • 0 registos');
        $('#cons-prev').prop('disabled', consPag <= 0);
        $('#cons-next').prop('disabled', rows.length < consLim);
        return;
      }

      var html = '';
      $.each(rows, function(_, r){
        var est = String(r.estado||'').toLowerCase();
        var badge = 'bg-secondary-subtle text-secondary';
        if(est.indexOf('confirm')>-1) badge='bg-success-subtle text-success';
        else if(est.indexOf('pend')>-1) badge='bg-warning-subtle text-warning';
        else if(est.indexOf('cancel')>-1) badge='bg-danger-subtle text-danger';

        html += `
          <tr>
            <td>${r.cliente||'-'}</td>
            <td>${r.profissional||'-'}</td>
            <td>${r.servico||'-'}</td>
            <td>${(r.data_consulta||r.data)||''}${(r.hora_consulta||r.hora) ? ' · ' + (r.hora_consulta||r.hora) : ''}</td>
            <td>€ ${parseValor(r.preco ?? r.valor).toLocaleString('pt-PT',{minimumFractionDigits:2})}</td>
            <td><span class="badge ${badge}">${r.estado||''}</span></td>
            <td class="text-end">
              <button class="btn btn-icon btn-sm rounded-2 shadow-none btn-editar-consulta me-1"
                      title="Editar" onclick="abrirEditar(${r.id})">
                <i class="ri-pencil-line"></i>
              </button>
              <button class="btn btn-icon btn-sm btn-danger rounded-2 shadow-none"
                      title="Eliminar" onclick="apagarConsulta(${r.id})">
                <i class="ri-delete-bin-line"></i>
              </button>
            </td>
          </tr>`;
      });

      $body.html(html);
      $('#consultas-info').text('Página ' + (consPag+1) + ' • ' + rows.length + ' registo(s)');
      $('#cons-prev').prop('disabled', consPag <= 0);
      $('#cons-next').prop('disabled', rows.length < consLim);
    }
  });
}

$('#cons-next').off('click').on('click', function(){
  consPag = consPag + 1;
  carregarConsultas(consPag);
});
$('#cons-prev').off('click').on('click', function(){
  if (consPag > 0) consPag = consPag - 1;
  carregarConsultas(consPag);
});

$(function(){
  carregarConsultas(0);
  carregarKPIsConsultas();
  carregarEventosMes();
  initSelects && initSelects();
});


$(function(){
  // Formulário (Criar / Editar)
  $(document).on('submit', '#form-nova-consulta', function(e){
    e.preventDefault();
    
    var $form = $('#form-nova-consulta');
    var id = $form.data('id');

    var dados = new FormData(this);
    dados.append('op', id ? 6 : 5);
    if(id) dados.append('id', id);

    $.ajax({
      url:'src/controller/controllerConsultas.php',
      method:'POST',
      data:dados,
      contentType:false,
      processData:false,
      dataType:'json'
    }).done(function(r){
      if(r.ok){
        Swal.fire({icon:'success', title: id ? 'Alterações guardadas!' : 'Consulta criada!', timer:1200, showConfirmButton:false})
        .then(function(){
          var modalEl = document.getElementById('novaConsultaModal');
          var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
          modal.hide();
          $('body').removeClass('modal-open'); $('.modal-backdrop').remove();

          if(!id) $form.removeData().get(0).reset();

          if (typeof carregarConsultas === 'function') carregarConsultas(id ? consPag : 0);
          if (typeof carregarKPIsConsultas   === 'function') carregarKPIsConsultas();
          if (typeof carregarEventosMes      === 'function') carregarEventosMes();
        });
      }else{
        Swal.fire({icon:'error', title:'Erro ao guardar', text:(r.msg||'Revê os dados e tenta novamente')});
      }
    }).fail(function(xhr){
      Swal.fire({
        icon:'error',
        title:'Erro de rede',
        html:
          '<div class="swal-erro-rede">' +
            '<b>Status:</b> ' + xhr.status + ' ' + (xhr.statusText||'') +
            '<pre>' + String(xhr.responseText||'').slice(0,1500) + '</pre>' +
          '</div>'
      }).then(function(){
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
      });
    });
  });

// SERVIÇO BASE
$(document).on('change', '#sel-servico', function(){
  const id = $(this).val();

  if (!id || id === '0'){
    precoBase = 0;
    atualizarValorTotal();
    return;
  }

  $.ajax({
    url: 'src/controller/controllerConsultas.php',
    method: 'POST',
    dataType: 'json',
    data: { op: 11, id_servico: id }
  }).done(function(r){
    precoBase = Number(r && r.preco || 0);
    atualizarValorTotal();
  }).fail(function(){
    precoBase = 0;
    atualizarValorTotal();
  });
});

// NOVA consulta
$(document).on('click', '#btn-nova-consulta', function(){
  var f = document.getElementById('form-nova-consulta');
  f.reset();

  if (fpData) {
    fpData.set('minDate', 'today'); 
    fpData.clear();
  }

  precoBase  = 0;
  precoExtra = 0;
  atualizarValorTotal();

  $('#sel-servico-extra').val([]);
  $('.chk-extra').prop('checked', false);
  atualizarLabelExtras();
  recarregarExtras();

  var $f = $('#form-nova-consulta');
  $f.removeData();
  $f.removeData('id');

  $(f).closest('.modal-content').find('.modal-title').text('Nova Consulta');
  $(f).find('button[type="submit"]').text('Guardar');

  $('#sel-cliente').val(null).trigger('change');
  $('#sel-prof').val(null).trigger('change');
  $('#sel-servico').val('0');

  new bootstrap.Modal('#novaConsultaModal').show();
});
});

function carregarLembretesDemo() {
  const lembretes = [
    {
      nome: 'SP_Naiara Reis Correia - PT',
      msg: 'Pagamento vencido há 2 dias',
      valor: 40.00,
      cor: 'bg-danger-subtle'
    },
    {
      nome: 'João Ferreira - Nutrição',
      msg: 'Pagamento vence hoje',
      valor: 50.00,
      cor: 'bg-warning-subtle'
    },
    {
      nome: 'Ana Sofia Marques - Psicologia',
      msg: 'Pagamento vence em 2 dias',
      valor: 60.00,
      cor: 'bg-warning-subtle'
    },
    {
      nome: 'Maria Beatriz Martins - PT',
      msg: 'Pagamento vence em 3 dias',
      valor: 40.00,
      cor: 'bg-warning-subtle'
    }
  ];

  const lista = document.getElementById('lista-lembretes');
  if (!lista) return;

  lista.innerHTML = lembretes.map(l => `
    <div class="list-group-item d-flex justify-content-between align-items-center ${l.cor}" style="border:none; border-radius:6px; margin-bottom:4px;">
      <div class="d-flex align-items-start gap-2">
        <span class="badge rounded-circle bg-dark-subtle text-muted d-inline-flex align-items-center justify-content-center" style="width:22px;height:22px;">!</span>
        <div>
          <div class="fw-semibold">${l.nome}</div>
          <small class="text-muted">${l.msg} · €${l.valor.toFixed(2)}</small>
        </div>
      </div>
    </div>
  `).join('');
}

$(function(){
  carregarLembretesDemo();
});

$(function(){
  $('#cal-prev').on('click', function(){ calRef.setMonth(calRef.getMonth()-1); carregarKPIsConsultas(); carregarEventosMes(); });
  $('#cal-next').on('click', function(){ calRef.setMonth(calRef.getMonth()+1); carregarKPIsConsultas(); carregarEventosMes(); });

  $('#btn-cal-expand').on('click', function(){
    const y = calRef.getFullYear();
    const m = calRef.getMonth() + 1; 
    const d = calSelectedDate ? calSelectedDate.split('-')[2] : '';

    const params = new URLSearchParams();
    params.set('year', y);
    params.set('month', m);
    if (d) params.set('day', d);

    window.location.href = 'calendarioConsultas.html';
  });

  carregarConsultas(0);
  carregarKPIsConsultas();
  carregarEventosMes();
  initSelects();
});

var fpData = flatpickr("#data-consulta", {
    locale: "pt",
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "d/m/Y",
    minDate: "today"
});

$('#novaConsultaModal').on('hidden.bs.modal', function () {
  if (fpData) fpData.set('minDate', 'today');
});
