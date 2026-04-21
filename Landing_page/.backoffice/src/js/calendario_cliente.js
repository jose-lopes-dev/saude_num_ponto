$(function () {

  const ENDPOINT = 'src/controller/calendario_cliente.php'
  const modal = new bootstrap.Modal($('#event-modal')[0])
  const form = $('#form-event')[0]

  const f = {
    id: $('#event-id')[0],
    name: $('#event-name')[0],
    cat: $('#event-category')[0],
    start: $('#event-date')[0],
    end: $('#event-date-end')[0],
    desc: $('#event-description')[0],
    loc: $('#event-location')[0]
  }

  function post(p) {
    return fetch(ENDPOINT, {
      method: 'POST',
      body: new URLSearchParams(p)
    }).then(r => r.json())
  }

  function cor(cat) {
    if (cat === 'Consulta') return '#0d6efd'
    if (cat === 'Evento Próprio') return '#6c757d'
    return '#3788d8'
  }

  const cal = new FullCalendar.Calendar($('#calendar')[0], {
    locale: 'pt',
    initialView: 'dayGridMonth',
    editable: true,

    events: (_, success) => {
      post({ op: 'listar' }).then(success)
    },

    eventDidMount: i => {
      const p = i.event.extendedProps
      const c = cor(p.categoria)
      i.el.style.backgroundColor = c
      i.el.style.borderColor = c

      if (p.readonly) {
        i.el.style.cursor = 'default'
      }
    },

    dateClick: i => {
      form.reset()
      f.id.value = ''
      f.cat.value = 'Evento Próprio'
      f.start.value = i.dateStr + 'T09:00'
      f.end.value = i.dateStr + 'T10:00'
      $('#btn-delete-event').hide()
      modal.show()
    },

    eventClick: i => {

      const p = i.event.extendedProps

      if (p.readonly) {
        Swal.fire({
          title: 'Consulta',
          html: `
            <b>Profissional:</b> ${p.profissional}<br>
            <b>Data:</b> ${i.event.start.toLocaleDateString('pt-PT')}<br>
            <b>Hora:</b> ${i.event.start.toLocaleTimeString('pt-PT',{
              hour:'2-digit',minute:'2-digit'
            })}
          `,
          icon: 'info'
        })
        return
      }

      const e = i.event
      f.id.value = e.id.replace('e_', '')
      f.name.value = e.title
      f.cat.value = p.categoria
      f.start.value = e.startStr.slice(0,16)
      f.end.value = e.endStr.slice(0,16)
      f.desc.value = p.descricao || ''
      f.loc.value = p.localizacao || ''

      $('#btn-delete-event').show()
      modal.show()
    },

    eventDrop: i => {
      if (i.event.extendedProps.readonly) return
      saveMove(i.event)
    },

    eventResize: i => {
      if (i.event.extendedProps.readonly) return
      saveMove(i.event)
    }
  })

  cal.render()

  $('#form-event').submit(async e => {
    e.preventDefault()

    await post({
      op: f.id.value ? 'editar' : 'guardar',
      id: f.id.value,
      titulo: f.name.value,
      categoria: f.cat.value,
      inicio: f.start.value,
      fim: f.end.value,
      descricao: f.desc.value,
      localizacao: f.loc.value
    })

    modal.hide()
    cal.refetchEvents()
  })

  $('#btn-delete-event').click(async () => {
    await post({ op: 'remover', id: f.id.value })
    modal.hide()
    cal.refetchEvents()
  })

  function saveMove(e) {
    post({
      op: 'editar',
      id: e.id.replace('e_', ''),
      inicio: e.startStr,
      fim: e.endStr || e.startStr
    })
  }

})
