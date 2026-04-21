// calendario.js
document.addEventListener('DOMContentLoaded', () => {
  const ENDPOINT = './src/controller/calendario.php';
  const modal = new bootstrap.Modal(document.getElementById('event-modal'));
  const form = document.getElementById('form-event');

  const fields = {
    id: document.getElementById('event-id'),
    name: document.getElementById('event-name'),
    category: document.getElementById('event-category'),
    dateStart: document.getElementById('event-date'),
    dateEnd: document.getElementById('event-date-end'),
    description: document.getElementById('event-description'),
    location: document.getElementById('event-location')
  };

  let mode = 'create';

  async function postData(params) {
    const resp = await fetch(ENDPOINT, { method: 'POST', body: new URLSearchParams(params) });
    const text = await resp.text();
    try {
      return JSON.parse(text);
    } catch {
      alert('Erro: resposta inválida do servidor.');
      console.error(text);
      return { status: 'error', msg: 'Erro inesperado.' };
    }
  }

  // Permite arrastar eventos externos
  if (document.getElementById('external-events')) {
    new FullCalendar.Draggable(document.getElementById('external-events'), {
      itemSelector: '.external-event',
      eventData: el => ({
        title: el.innerText.trim(),
        categoria: el.dataset.categoria,
        className: el.dataset.class
      })
    });
  }

  // === CALENDÁRIO ===
  const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
    locale: 'pt',
    initialView: 'dayGridMonth',
    initialDate: '2025-09-01',
    selectable: true,
    editable: true,
    droppable: true,

    events(fetchInfo, success, fail) {
      postData({ acao: 'listar' })
        .then(events => {
          events.forEach(ev => {
            if (ev.extendedProps?.concluido == 1 || ev.extendedProps?.categoria === "Concluído") {
              ev.classNames = ["bg-success-subtle"];
            }
          });
          success(events);
        })
        .catch(fail);
    },

    dateClick(info) {
      openModal('create', { data_inicio: info.dateStr, data_fim: info.dateStr });
    },

    eventClick(info) {
      openModal('edit', {
        id: info.event.id,
        titulo: info.event.title,
        data_inicio: info.event.startStr,
        data_fim: info.event.end ? info.event.endStr : info.event.startStr,
        categoria: info.event.extendedProps?.categoria || '',
        descricao: info.event.extendedProps?.descricao || '',
        localizacao: info.event.extendedProps?.localizacao || ''
      });
    },

    eventReceive(info) {
      info.event.remove();
      openModal('create', {
        titulo: info.event.title,
        categoria: info.draggedEl?.dataset?.categoria || '',
        data_inicio: info.event.startStr,
        data_fim: info.event.startStr
      });
    },

    eventDrop: info => updateEvent(info.event),
    eventResize: info => updateEvent(info.event)
  });

  calendar.render();

  // === BOTÕES ===
  document.getElementById('btn-new-event').onclick = () => openModal('create');

  document.getElementById('btn-delete-event').onclick = async () => {
    if (!fields.id.value) return;
    const res = await Swal.fire({ title: 'Tem a certeza?', text: 'Este evento será removido', icon: 'warning', showCancelButton: true });
    if (!res.isConfirmed) return;

    const resp = await postData({ acao: 'remover', id: fields.id.value });
    Swal.fire(resp.status === 'success' ? 'Sucesso' : 'Erro', resp.msg, resp.status);
    modal.hide();
    calendar.refetchEvents();

    localStorage.setItem('evento_atualizado', JSON.stringify({ tipo: 'remover', id: fields.id.value, ts: Date.now() }));
  };

  document.getElementById('btn-conclude-event').onclick = async () => {
    if (!fields.id.value) return Swal.fire('Aviso', 'Nenhum evento selecionado.', 'info');

    const res = await Swal.fire({
      title: 'Concluir evento?',
      text: 'Após concluir, o evento ficará marcado como concluído.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sim, concluir',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#198754'
    });

    if (!res.isConfirmed) return;

    const params = {
      acao: 'editar',
      id: fields.id.value,
      nome_evento: fields.name.value,
      categoria: fields.category.value,
      data_inicio: `${fields.dateStart.value} 00:00:00`,
      data_fim: `${fields.dateEnd.value} 00:00:00`,
      descricao: fields.description.value,
      localizacao: fields.location.value,
      concluido: 1
    };

    const resp = await postData(params);
    if (resp.status === 'success') {
      Swal.fire('Concluído!', 'O evento foi marcado como concluído.', 'success');
      modal.hide();
      calendar.refetchEvents();

      if (params.categoria.trim().toLowerCase() === 'obrigações declarativas') {
        localStorage.setItem('evento_atualizado', JSON.stringify({ tipo: 'concluir', id: params.id, categoria: params.categoria, ts: Date.now() }));
      }
    } else {
      Swal.fire('Erro', 'Não foi possível concluir o evento.', 'error');
    }
  };

  // === SUBMETER FORMULÁRIO ===
  form.onsubmit = async e => {
    e.preventDefault();
    if (!fields.name.value || !fields.dateStart.value || !fields.dateEnd.value) {
      return Swal.fire('Atenção', 'Preenche o nome e as datas.', 'warning');
    }

    const acao = mode === 'create' ? 'adicionar' : 'editar';
    const params = {
      acao,
      id: fields.id.value,
      nome_evento: fields.name.value,
      categoria: fields.category.value,
      data_inicio: `${fields.dateStart.value} 00:00:00`,
      data_fim: `${fields.dateEnd.value} 00:00:00`,
      descricao: fields.description.value,
      localizacao: fields.location.value
    };

    const resp = await postData(params);
    Swal.fire(resp.status === 'success' ? 'Sucesso' : 'Erro', resp.msg, resp.status);
    modal.hide();
    calendar.refetchEvents();

    if (params.categoria.trim().toLowerCase() === 'obrigações declarativas') {
      localStorage.setItem('evento_atualizado', JSON.stringify({ tipo: acao, categoria: params.categoria, titulo: params.nome_evento, ts: Date.now() }));
    }
  };

  // === FUNÇÕES AUXILIARES ===
  function openModal(m, data = {}) {
    mode = m;
    form.reset();

    fields.id.value = data.id || '';
    fields.name.value = data.titulo || '';
    fields.category.value = data.categoria || 'Obrigações Declarativas';
    fields.dateStart.value = (data.data_inicio || '').slice(0, 10) || new Date().toISOString().slice(0, 10);
    fields.dateEnd.value = (data.data_fim || '').slice(0, 10) || fields.dateStart.value;
    fields.description.value = data.descricao || '';
    fields.location.value = data.localizacao || '';

    document.getElementById('btn-delete-event').style.display = m === 'edit' ? 'inline-block' : 'none';
    document.getElementById('btn-conclude-event').style.display = m === 'edit' ? 'inline-block' : 'none';
    document.getElementById('btn-save-event').textContent = m === 'create' ? 'Adicionar' : 'Guardar';
    modal.show();
  }

  async function updateEvent(event) {
    await postData({
      acao: 'editar',
      id: event.id,
      nome_evento: event.title || '',
      categoria: event.extendedProps?.categoria || '',
      data_inicio: event.start ? event.start.toISOString().slice(0, 19).replace('T', ' ') : '',
      data_fim: event.end ? event.end.toISOString().slice(0, 19).replace('T', ' ') : '',
      concluido: event.extendedProps?.concluido || 0
    });
    calendar.refetchEvents();

    localStorage.setItem('evento_atualizado', JSON.stringify({ tipo: 'editar', id: event.id, ts: Date.now() }));
  }
});
