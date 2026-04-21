(function () {
  'use strict';

  var CURRENT_VIEW_TYPE = 'dayGridMonth';
  // -------------------------
  // Helpers (estilo prof)
  // -------------------------

  function pad(n) { return (n < 10) ? ('0' + n) : String(n); }

  function ymd(dt) {
    return dt.getFullYear() + '-' + pad(dt.getMonth() + 1) + '-' + pad(dt.getDate());
  }

  function addDaysYMD(ymdStr, days) {
    var d = new Date(ymdStr + 'T00:00:00');
    d.setDate(d.getDate() + days);
    return ymd(d);
  }

  function toSqlDateTime(dt) {
    if (!(dt instanceof Date)) dt = new Date(dt);
    return dt.getFullYear() + '-' + pad(dt.getMonth() + 1) + '-' + pad(dt.getDate()) + ' ' +
      pad(dt.getHours()) + ':' + pad(dt.getMinutes()) + ':00';
  }

  function toLocalIso(dt) {
    if (!(dt instanceof Date)) dt = new Date(dt);
    return dt.getFullYear() + '-' + pad(dt.getMonth() + 1) + '-' + pad(dt.getDate()) + 'T' +
      pad(dt.getHours()) + ':' + pad(dt.getMinutes()) + ':00';
  }

  function postJSON(url, data) {
    return $.ajax({
      url: url,
      method: 'POST',
      dataType: 'json',
      data: data
    });
  }

  function killTooltips() {
    document.querySelectorAll('.fc [title]').forEach(function (el) {
      el.removeAttribute('title');
    });
  }

  function subtractIntervals(base, cuts) {
    var out = [];

    base.forEach(function (b) {
      var parts = [{ start: new Date(b.start), end: new Date(b.end) }];

      cuts.forEach(function (c) {
        var cStart = new Date(c.start);
        var cEnd = new Date(c.end);

        var nextParts = [];
        parts.forEach(function (p) {
          if (cEnd <= p.start || cStart >= p.end) { nextParts.push(p); return; }
          if (cStart > p.start) nextParts.push({ start: new Date(p.start), end: new Date(cStart) });
          if (cEnd < p.end) nextParts.push({ start: new Date(cEnd), end: new Date(p.end) });
        });

        parts = nextParts;
      });

      parts.forEach(function (p) { if (p.end > p.start) out.push(p); });
    });

    return out;
  }

  function mapDiaSemanaPt(dia) {
    var m = {
      'Domingo': 0,
      'Segunda': 1,
      'Terça': 2,
      'Terca': 2,
      'Quarta': 3,
      'Quinta': 4,
      'Sexta': 5,
      'Sábado': 6,
      'Sabado': 6
    };
    return (dia in m) ? m[dia] : null;
  }

  function buildBaseDisponibilidade(infoStart, infoEnd, horarioRows) {
    var blocks = [];

    var d = new Date(infoStart.getTime());
    d.setHours(0, 0, 0, 0);

    var end = new Date(infoEnd.getTime());
    end.setHours(0, 0, 0, 0);

    while (d < end) {
      var dow = d.getDay();

      horarioRows.forEach(function (h) {
        var hdow = mapDiaSemanaPt(h.dia_semana);
        if (hdow === null) return;
        if (Number(h.ativo || 0) !== 1) return;
        if (hdow !== dow) return;

        var ini = String(h.hora_inicio || '00:00:00').slice(0, 8);
        var fim = String(h.hora_fim || '00:00:00').slice(0, 8);

        var start = new Date(ymd(d) + 'T' + ini);
        var endi = new Date(ymd(d) + 'T' + fim);

        if (endi > start) blocks.push({ start: start, end: endi });
      });

      d.setDate(d.getDate() + 1);
    }

    return blocks;
  }

  function normalizeIndispRows(rows) {
    return rows.map(function (r) {
      var ini = String(r.inicio || '').replace(' ', 'T');
      var fim = String(r.fim || '').replace(' ', 'T');
      return {
        id: String(r.id),
        start: new Date(ini),
        end: new Date(fim),
        motivo: r.motivo || ''
      };
    });
  }

  // -------------------------
  // Event Sources
  // -------------------------

  function sourceConsultas(info, successCallback, failureCallback) {
    var mid = new Date(info.start.getTime());
    mid.setDate(mid.getDate() + 15);

    var yearReq = mid.getFullYear();
    var monthReq = mid.getMonth() + 1;

    postJSON('src/controller/consultaPTController.php', {
      acao: 'calendarPT',
      year: yearReq,
      month: monthReq
    })
      .done(function (resp) {
        var rows = Array.isArray(resp) ? resp : [];
        var events = rows.map(function (r) {
          var serv = r.servico || '';
          if (['PLANO MÉDIO', 'PLANO BASICO', 'PLANO PRO', 'PACK LAR', 'PLANO DUO'].includes(serv)) return null;

          var data = r.data_consulta || r.date;
          var hora = r.hora_consulta || r.hora || '00:00';
          if (hora.length === 5) hora += ':00';

          return {
            title: serv || 'Consulta',
            start: data ? data + 'T' + hora : null,
            classNames: ['evt-consulta'],
            extendedProps: {
              tipo: 'consulta',
              cliente: r.cliente || '',
              estado: r.estado || '',
              preco: r.valor ?? r.preco ?? null
            }
          };
        }).filter(Boolean);

        successCallback(events);
      })
      .fail(function (xhr, status, err) {
        console.error('Erro AJAX consultas:', status, err, xhr && xhr.responseText);
        if (failureCallback) failureCallback(err || status);
      });
  }

  function sourceEventosExtra(info, successCallback, failureCallback) {
    postJSON('src/controller/controllerAgenda_pt.php', {
      op: 6,
      start: toSqlDateTime(info.start),
      end: toSqlDateTime(info.end)
    })
      .done(function (resp) {
        var rows = resp && resp.ok && Array.isArray(resp.rows) ? resp.rows : [];
        var events = rows.map(function (r) {
          return {
            id: String(r.id),
            title: r.titulo || 'Evento',
            start: String(r.inicio || '').replace(' ', 'T'),
            end: String(r.fim || '').replace(' ', 'T'),
            classNames: ['evt-extra'],
            extendedProps: { tipo: 'extra', descricao: r.descricao || '' }
          };
        });

        successCallback(events);
      })
      .fail(function (xhr, status, err) {
        console.error('Erro AJAX eventos extra:', status, err, xhr && xhr.responseText);
        if (failureCallback) failureCallback(err || status);
      });
  }

function sourceIndisponibilidade(info, successCallback, failureCallback) {
  var isMonth = (CURRENT_VIEW_TYPE === 'dayGridMonth');

  postJSON('src/controller/controllerAgenda_pt.php', {
    op: 1,
    start: toSqlDateTime(info.start),
    end: toSqlDateTime(info.end)
  })
    .done(function (resp) {
      var rows = (resp && resp.ok && Array.isArray(resp.rows)) ? resp.rows : [];
      var events = [];

      rows.forEach(function (r) {
        var motivo = (r.motivo || '').trim();
        var label = motivo ? motivo : 'Ausência';

        if (isMonth) {
          var s = String(r.inicio || '').slice(0, 10);
          var eDate = String(r.fim || '').slice(0, 10);
          var eTime = String(r.fim || '').slice(11, 19);

          // FullCalendar: end exclusivo em allDay
          var endExclusive = (eTime === '00:00:00') ? eDate : addDaysYMD(eDate, 1);

          // ✅ EVENTO NORMAL (vermelho) para aparecer no mês
          events.push({
            id: 'indisp-' + String(r.id),
            start: s,
            end: endExclusive,
            allDay: true,
            display: 'block',
            title: label.toUpperCase(),

            backgroundColor: 'rgba(231, 76, 60, 0.90)',
            borderColor: 'rgba(231, 76, 60, 1)',
            textColor: '#FBFBFA',

            classNames: ['evt-indisp-label'],
            extendedProps: { tipo: 'indisponivel', motivo: motivo, indisp_id: String(r.id) }
          });

          return;
        }

        // Semana/Dia: mantém background para bloquear slots
        events.push({
          id: String(r.id),
          start: String(r.inicio || '').replace(' ', 'T'),
          end: String(r.fim || '').replace(' ', 'T'),
          display: 'background',
          classNames: ['evt-indisp-bg'],
          title: '',
          extendedProps: { tipo: 'indisponivel', motivo: motivo, indisp_id: String(r.id) }
        });
      });

      successCallback(events);
    })
    .fail(function (xhr, status, err) {
      console.error('Erro AJAX indisponibilidade:', status, err, xhr && xhr.responseText);
      if (failureCallback) failureCallback(err || status);
    });
}


  function sourceDisponibilidade(info, successCallback, failureCallback) {
    var isMonth = (CURRENT_VIEW_TYPE === 'dayGridMonth');

    var pHorario = postJSON('src/controller/controllerAgenda_pt.php', { op: 4 });
    var pIndisp = postJSON('src/controller/controllerAgenda_pt.php', {
      op: 1,
      start: toSqlDateTime(info.start),
      end: toSqlDateTime(info.end)
    });

    $.when(pHorario, pIndisp)
      .done(function (horResp, indResp) {
        var horarioPayload = horResp && horResp[0] ? horResp[0] : {};
        var indPayload = indResp && indResp[0] ? indResp[0] : {};

        var horarioRows = (horarioPayload && horarioPayload.ok && Array.isArray(horarioPayload.rows)) ? horarioPayload.rows : [];
        var indRows = (indPayload && indPayload.ok && Array.isArray(indPayload.rows)) ? indPayload.rows : [];

        if (!horarioRows.length) return successCallback([]);

        var base = buildBaseDisponibilidade(info.start, info.end, horarioRows);
        var cuts = normalizeIndispRows(indRows);

        var finalBlocks = subtractIntervals(base, cuts);
        var events = [];

        if (isMonth) {
          var dayHas = {};
          finalBlocks.forEach(function (b) { dayHas[ymd(b.start)] = true; });

          Object.keys(dayHas).forEach(function (day) {
            events.push({
              start: day,
              end: addDaysYMD(day, 1),
              allDay: true,
              display: 'background',
              backgroundColor: 'rgba(170, 202, 28, 0.22)',
              borderColor: 'transparent',
              classNames: ['evt-disp-bg'],
              title: '',
              extendedProps: { tipo: 'disponivel' }
            });
          });

          return successCallback(events);
        }

        finalBlocks.forEach(function (b) {
          events.push({
            start: toLocalIso(b.start),
            end: toLocalIso(b.end),
            display: 'background',
            classNames: ['evt-disp-bg'],
            title: '',
            extendedProps: { tipo: 'disponivel' }
          });
        });

        successCallback(events);
      })
      .fail(function (xhr) {
        console.error('Erro AJAX disponibilidade:', xhr && xhr.responseText);
        if (failureCallback) failureCallback(xhr);
      });
  }

  // -------------------------
  // Init calendar
  // -------------------------

  document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('calendar-full');
    if (!el) return;

    var params = new URLSearchParams(window.location.search);
    var now = new Date();
    var year = parseInt(params.get('year'), 10) || now.getFullYear();
    var month = parseInt(params.get('month'), 10) || (now.getMonth() + 1);
    var day = params.get('day') || '01';

    var initialDate = year + '-' + pad(month) + '-' + day;

    var calendar = new FullCalendar.Calendar(el, {
      locale: 'pt',
      initialDate: initialDate,
      initialView: 'dayGridMonth',
      fixedWeekCount: false,
      height: 750,

      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'myMonth,myWeek,myDay'
      },

      customButtons: {
        myMonth: { text: 'Mês', click: function () { calendar.changeView('dayGridMonth'); } },
        myWeek: { text: 'Semana', click: function () { calendar.changeView('timeGridWeek'); } },
        myDay: { text: 'Dia', click: function () { calendar.changeView('timeGridDay'); } }
      },

      buttonText: { today: 'Hoje' },

      dayMaxEventRows: 2,
      eventDisplay: 'block',
      moreLinkClick: 'popover',
      moreLinkText: function (n) { return '+' + n + ' mais'; },
      moreLinkDidMount: function (arg) {
        if (arg && arg.el) arg.el.removeAttribute('title');
      },

      datesSet: function (arg) {
        CURRENT_VIEW_TYPE = (arg && arg.view && arg.view.type) ? arg.view.type : CURRENT_VIEW_TYPE;
        killTooltips();
      },

      eventDidMount: function (info) {
        killTooltips();

        // permitir clique nos backgrounds (semana/dia)
        var ext = info.event.extendedProps || {};
        if (info.event.display === 'background') {
          info.el.style.pointerEvents = 'auto';
          info.el.style.cursor = (ext.tipo === 'indisponivel') ? 'pointer' : 'default';

          info.el.addEventListener('mousedown', function (ev) { ev.stopPropagation(); });

          if (ext.tipo === 'indisponivel') {
            info.el.addEventListener('click', function (ev) {
              ev.preventDefault();
              ev.stopPropagation();
              Swal.fire({
                title: 'Indisponível',
                text: ext.motivo ? ('Motivo: ' + ext.motivo) : 'Sem motivo.',
                icon: 'info'
              });
            });
          }
        }
      },

      selectable: true,
      selectMirror: false,

      // Criar bloqueio (indisponibilidade) / evento extra
      select: function (info) {
        document.querySelectorAll('.fc-popover').forEach(function (p) { p.remove(); });

        var inicio = info.start;
        var fim = info.end;

        Swal.fire({
          title: 'Adicionar',
          html:
            '<div class="text-start">' +
            '  <label class="form-label">O que queres adicionar?</label>' +
            '  <select id="tipoAdd" class="form-select mb-3">' +
            '    <option value="bloqueio">Ausência</option>' +
            '    <option value="evento">Evento</option>' +
            '  </select>' +
            '  <div id="boxBloq">' +
            '    <label class="form-label">Motivo (opcional)</label>' +
            '    <input id="motivoIndisp" class="form-control" placeholder="Ex: férias, folga, almoço">' +
            '  </div>' +
            '  <div id="boxEvento" style="display:none;">' +
            '    <label class="form-label">Título</label>' +
            '    <input id="tituloEvt" class="form-control" placeholder="Ex: Reunião">' +
            '    <label class="form-label mt-2">Descrição (opcional)</label>' +
            '    <input id="descEvt" class="form-control" placeholder="Ex: discutir alguns pontos">' +
            '  </div>' +
            '  <div class="form-text mt-3" style="opacity:.75;">' +
            '    De <b>' + inicio.toLocaleString('pt-PT') + '</b> a <b>' + fim.toLocaleString('pt-PT') + '</b>' +
            '  </div>' +
            '</div>',
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          didOpen: function () {
            var sel = document.getElementById('tipoAdd');
            function toggleBoxes() {
              var isEvt = (sel.value === 'evento');
              document.getElementById('boxBloq').style.display = isEvt ? 'none' : 'block';
              document.getElementById('boxEvento').style.display = isEvt ? 'block' : 'none';
            }
            sel.addEventListener('change', toggleBoxes);
            toggleBoxes();
          },
          preConfirm: function () {
            var tipo = document.getElementById('tipoAdd').value;

            if (tipo === 'evento') {
              var t = (document.getElementById('tituloEvt').value || '').trim();
              var d = (document.getElementById('descEvt').value || '').trim();
              if (!t) { Swal.showValidationMessage('Título é obrigatório no evento.'); return false; }
              return { tipo: 'evento', titulo: t, descricao: d };
            }

            return { tipo: 'bloqueio', motivo: document.getElementById('motivoIndisp').value || '' };
          }
        }).then(function (res) {
          calendar.unselect();
          if (!res.isConfirmed) return;

          if (res.value.tipo === 'evento') {
            postJSON('src/controller/controllerAgenda_pt.php', {
              op: 7,
              titulo: res.value.titulo,
              descricao: res.value.descricao,
              inicio: toSqlDateTime(inicio),
              fim: toSqlDateTime(fim)
            }).done(function (r) {
              if (r && r.ok) {
                calendar.refetchEvents();
                Swal.fire({ icon: 'success', title: 'Guardado', timer: 900, showConfirmButton: false });
              } else {
                Swal.fire({ icon: 'error', title: 'Erro', text: (r && r.msg) ? r.msg : 'Não foi possível guardar.' });
              }
            });
            return;
          }

          postJSON('src/controller/controllerAgenda_pt.php', {
            op: 2,
            inicio: toSqlDateTime(inicio),
            fim: toSqlDateTime(fim),
            motivo: res.value.motivo
          }).done(function (resp) {
            if (resp && resp.ok) {
              calendar.refetchEvents();
              Swal.fire({ icon: 'success', title: 'Guardado', timer: 1000, showConfirmButton: false });
            } else {
              Swal.fire({ icon: 'error', title: 'Erro', text: (resp && resp.msg) ? resp.msg : 'Não foi possível guardar.' });
            }
          });
        });
      },

      // Click: indisponibilidade -> apagar; extra -> apagar; consulta -> detalhes
      eventClick: function (info) {
        document.querySelectorAll('.fc-popover').forEach(function (p) { p.remove(); });

        var e = info.event;
        var ext = e.extendedProps || {};

        if (ext.tipo === 'indisponivel') {
          Swal.fire({
            title: 'Remover ausência?',
            text: ext.motivo ? ('Motivo: ' + ext.motivo) : 'Ausência',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Remover',
            cancelButtonText: 'Cancelar'
          }).then(function (res) {
            if (!res.isConfirmed) return;

            var realId = ext.indisp_id || String(e.id).replace('indisp-', '');

            postJSON('src/controller/controllerAgenda_pt.php', { op: 3, id: realId })
              .done(function (resp) {
                if (resp && resp.ok) {
                  calendar.refetchEvents();
                  Swal.fire({ icon: 'success', title: 'Removido', timer: 900, showConfirmButton: false });
                } else {
                  Swal.fire({ icon: 'error', title: 'Erro', text: (resp && resp.msg) ? resp.msg : 'Não foi possível remover.' });
                }
              })
              .fail(function (xhr) {
                Swal.fire({ icon: 'error', title: 'Erro', text: 'Falha no pedido.' });
                console.error('Erro AJAX apagar indisponibilidade:', xhr && xhr.responseText);
              });
          });
          return;
        }

        if (ext.tipo === 'extra') {
          Swal.fire({
            title: e.title || 'Evento',
            html: (ext.descricao ? ('<b>Descrição:</b> ' + ext.descricao) : 'Sem descrição.'),
            showCancelButton: true,
            confirmButtonText: 'Remover',
            cancelButtonText: 'Fechar',
            icon: 'info'
          }).then(function (res) {
            if (!res.isConfirmed) return;

            postJSON('src/controller/controllerAgenda_pt.php', { op: 8, id: e.id })
              .done(function (r) {
                if (r && r.ok) {
                  calendar.refetchEvents();
                  Swal.fire({ icon: 'success', title: 'Removido', timer: 900, showConfirmButton: false });
                } else {
                  Swal.fire({ icon: 'error', title: 'Erro', text: 'Não foi possível remover.' });
                }
              });
          });
          return;
        }

        // Consulta
        var dt = '';
        if (e.start) {
          dt = e.start.toLocaleString('pt-PT', {
            year: 'numeric', month: '2-digit', day: '2-digit',
            hour: '2-digit', minute: '2-digit'
          });
        }

        var html = '';
        if (dt) html += '<b>Data/Hora:</b> ' + dt + '<br>';
        if (ext.cliente) html += '<b>Cliente:</b> ' + ext.cliente + '<br>';
        if (ext.estado) html += '<b>Estado:</b> ' + ext.estado + '<br>';
        if (ext.preco != null) html += '<b>Preço:</b> € ' + Number(ext.preco).toFixed(2);

        Swal.fire({
          title: e.title || 'Consulta',
          html: html || 'Sem detalhes adicionais.',
          icon: 'info'
        });
      },

      eventSources: [
        sourceEventosExtra,
        sourceConsultas,
        sourceDisponibilidade,
        sourceIndisponibilidade
      ]
    });

    // -------------------------
    // Horário semanal (o teu bloco já estava ok, deixei o teu)
    // -------------------------

    function horarioToMap(rows) {
      var map = {};
      rows.forEach(function (r) {
        if (!map[r.dia_semana]) map[r.dia_semana] = [];
        map[r.dia_semana].push(r);
      });

      Object.keys(map).forEach(function (k) {
        map[k].sort(function (a, b) {
          return String(a.hora_inicio).localeCompare(String(b.hora_inicio));
        });
      });

      return map;
    }

    function hm(t) { return String(t || '').slice(0, 5); }
    function hms(t) { return (String(t || '').length === 5) ? (t + ':00') : String(t || ''); }

    function defaultIntervalos(dia) {
      var isWeekend = (dia === 'Sábado' || dia === 'Domingo');
      if (isWeekend) return { ativo: 0, intervalos: [{ hi: '09:00', hf: '18:00' }] };
      if (dia === 'Segunda') return { ativo: 1, intervalos: [{ hi: '08:00', hf: '12:00' }, { hi: '14:00', hf: '18:00' }] };
      return { ativo: 1, intervalos: [{ hi: '09:00', hf: '12:00' }, { hi: '14:00', hf: '18:00' }] };
    }

    function intervaloRowHtml(hi, hf) {
      return ''
        + '<div class="hs-row" style="display:flex; gap:10px; align-items:center; margin-bottom:8px;">'
        + '  <input type="time" class="form-control hs-hi" value="' + (hi || '') + '" style="flex:1; height:38px; border-radius:8px;">'
        + '  <input type="time" class="form-control hs-hf" value="' + (hf || '') + '" style="flex:1; height:38px; border-radius:8px;">'
        + '  <button type="button" class="btn btn-sm hs-del" style="width:42px; height:38px; border-radius:8px; border:1px solid rgba(255,255,255,.16); background:rgba(0,0,0,.25); color:#FBFBFA;">✕</button>'
        + '</div>';
    }

    function buildHorarioHtmlMulti(map) {
      var dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];

      var html = ''
        + '<div class="text-start" style="color:#cfcfcf; font-size:13px; margin-bottom:10px;">'
        + 'Define os dias e os intervalos em que estás <b>disponível</b> para marcações.'
        + '</div>';

      dias.forEach(function (dia) {
        var rows = (map[dia] || []);
        var ativo = 0;
        var intervalos = [];

        if (rows.length) {
          ativo = rows.some(function (r) { return Number(r.ativo) === 1; }) ? 1 : 0;
          intervalos = rows.map(function (r) { return { hi: hm(r.hora_inicio), hf: hm(r.hora_fim) }; });
        } else {
          var def = defaultIntervalos(dia);
          ativo = def.ativo;
          intervalos = def.intervalos;
        }

        html += ''
          + '<div class="hs-day border rounded p-2 mb-2" data-dia="' + dia + '" style="border-color: rgba(255,255,255,.14)!important; background: rgba(0,0,0,.12); border-radius:10px;">'
          + '  <div class="d-flex align-items-center justify-content-between">'
          + '    <div class="fw-bold mb-1" style="color:#FBFBFA;">' + dia + '</div>'
          + '    <label class="d-flex align-items-center gap-2" style="color:#e7e7e7; font-size:13px; user-select:none;">'
          + '      <input type="checkbox" class="form-check-input hs-ativo" ' + (ativo ? 'checked' : '') + '>'
          + '      <span>Disponível</span>'
          + '    </label>'
          + '  </div>'
          + '  <div class="hs-intervalos mt-2">'
          + intervalos.map(function (it) { return intervaloRowHtml(it.hi, it.hf); }).join('')
          + '  </div>'
          + '  <div class="d-flex justify-content-end mt-2">'
          + '    <button type="button" class="btn btn-sm hs-add" style="background:rgba(170,202,28,.18); border:1px solid rgba(170,202,28,.35); color:#FBFBFA; border-radius:8px; font-weight:700;">+ intervalo</button>'
          + '  </div>'
          + '  <div class="hs-warn mt-2" style="display:none; color:#ffb3b3; font-size:12px;">'
          + '    Corrige horas (fim > início) e remove sobreposições.'
          + '  </div>'
          + '</div>';
      });

      return '<div class="text-start">' + html + '</div>';
    }

    function syncDiaState(dayEl) {
      var on = dayEl.querySelector('.hs-ativo').checked;
      dayEl.style.opacity = on ? '1' : '0.55';
      dayEl.querySelectorAll('input[type="time"], .hs-add, .hs-del').forEach(function (el) { el.disabled = !on; });
    }

    function validateDia(dayEl) {
      var on = dayEl.querySelector('.hs-ativo').checked;
      var warn = dayEl.querySelector('.hs-warn');
      warn.style.display = 'none';
      if (!on) return true;

      var rows = Array.from(dayEl.querySelectorAll('.hs-row')).map(function (r) {
        return { hi: r.querySelector('.hs-hi').value, hf: r.querySelector('.hs-hf').value };
      }).filter(function (x) { return x.hi && x.hf; })
        .sort(function (a, b) { return a.hi.localeCompare(b.hi); });

      for (var i = 0; i < rows.length; i++) {
        if (rows[i].hf <= rows[i].hi) { warn.style.display = 'block'; return false; }
        if (i > 0 && rows[i].hi < rows[i - 1].hf) { warn.style.display = 'block'; return false; }
      }
      return true;
    }

    function collectHorarioPayload(root) {
      var out = [];
      root.querySelectorAll('.hs-day').forEach(function (dayEl) {
        var dia = dayEl.getAttribute('data-dia');
        var on = dayEl.querySelector('.hs-ativo').checked;
        if (!on) return;

        dayEl.querySelectorAll('.hs-row').forEach(function (r) {
          var hi = r.querySelector('.hs-hi').value;
          var hf = r.querySelector('.hs-hf').value;
          if (!hi || !hf) return;

          out.push({ dia_semana: dia, hora_inicio: hms(hi), hora_fim: hms(hf), ativo: 1 });
        });
      });
      return out;
    }

    var btnHorario = document.getElementById('btnHorarioSemanal');
    if (btnHorario) {
      btnHorario.addEventListener('click', function () {
        postJSON('src/controller/controllerAgenda_pt.php', { op: 4 })
          .done(function (resp) {
            var rows = (resp && resp.ok && Array.isArray(resp.rows)) ? resp.rows : [];
            var map = horarioToMap(rows);

            Swal.fire({
              title: 'Horário semanal',
              html: buildHorarioHtmlMulti(map),
              width: 820,
              showCancelButton: true,
              confirmButtonText: 'Guardar',
              cancelButtonText: 'Cancelar',
              didOpen: function () {
                var root = Swal.getHtmlContainer();
                root.querySelectorAll('.hs-day').forEach(function (dayEl) { syncDiaState(dayEl); });

                root.addEventListener('change', function (ev) {
                  if (!ev.target.classList.contains('hs-ativo')) return;
                  var dayEl = ev.target.closest('.hs-day');
                  syncDiaState(dayEl);

                  if (!ev.target.checked) {
                    var rows2 = dayEl.querySelectorAll('.hs-row');
                    rows2.forEach(function (r, i) { if (i > 0) r.remove(); });
                  }
                });

                root.addEventListener('click', function (ev) {
                  if (ev.target.classList.contains('hs-add')) {
                    var dayEl = ev.target.closest('.hs-day');
                    dayEl.querySelector('.hs-intervalos').insertAdjacentHTML('beforeend', intervaloRowHtml('09:00', '18:00'));
                    syncDiaState(dayEl);
                  }
                  if (ev.target.classList.contains('hs-del')) {
                    var dayEl2 = ev.target.closest('.hs-day');
                    ev.target.closest('.hs-row').remove();
                    syncDiaState(dayEl2);
                  }
                });
              },
              preConfirm: function () {
                var root = Swal.getHtmlContainer();
                var ok = true;

                root.querySelectorAll('.hs-day').forEach(function (dayEl) {
                  if (!validateDia(dayEl)) ok = false;
                });

                if (!ok) { Swal.showValidationMessage('Há intervalos inválidos (horas ou sobreposições).'); return false; }
                return collectHorarioPayload(root);
              }
            }).then(function (res) {
              if (!res.isConfirmed) return;

              postJSON('src/controller/controllerAgenda_pt.php', { op: 5, items: JSON.stringify(res.value) })
                .done(function (r2) {
                  if (r2 && r2.ok) {
                    calendar.refetchEvents();
                    Swal.fire({ icon: 'success', title: 'Guardado', timer: 800, showConfirmButton: false });
                  } else {
                    Swal.fire({ icon: 'error', title: 'Erro ao guardar' });
                  }
                });
            });
          })
          .fail(function (xhr) {
            console.error('Erro AJAX horario semanal:', xhr && xhr.responseText);
            Swal.fire({ icon: 'error', title: 'Erro', text: 'Não consegui carregar o horário.' });
          });
      });
    }

    calendar.render();
    killTooltips();
  });

})();
