const CHAT_API = 'src/controller/controllerChat.php'

let USER_ID = null
let OUTRO_ID = null
let LAST_ID = 0

let TIMER_MSG = null
let TIMER_CONV = null

function esc(s){
  return $('<div>').text(s ?? '').html()
}

function json(r){
  try{ return JSON.parse(r) }catch{ return [] }
}

/* ================= CONVERSAS ================= */

function carregarConversas(autoAbrir = false){
  if(!USER_ID) return

  $.post(CHAT_API,{ op:1, userId:USER_ID }, r=>{
    let d = r
    const ul = $('#users-conversation')

    let ativa = OUTRO_ID
    ul.empty()

    if(d.length === 0){
      ul.html('<li class="p-3 text-muted">Sem conversas</li>')
      return
    }

    d.forEach(c=>{
      const li = $(`
        <li class="conversation-item p-3 ${c.id==ativa?'active':''}"
            data-id="${c.id}"
            data-nome="${esc(c.nome)}">
          ${esc(c.nome)}
          ${c.nao_lidas>0 ? `<span class="badge bg-danger ms-2">${c.nao_lidas}</span>`:''}
        </li>
      `)
      ul.append(li)
    })
  })
}

/* ================= MENSAGENS ================= */

function renderMensagem(m){
  LAST_ID = Math.max(LAST_ID,m.id)

  let dt = new Date(m.data_envio.replace(' ','T'))

  $('#chat-messages').append(`
    <li class="${m.id_remetente==USER_ID?'right':'left'}">
      <div>${esc(m.mensagem)}</div>
      <small class="text-muted">
        ${dt.toLocaleDateString()} ${dt.toLocaleTimeString()}
      </small>
    </li>
  `)

  $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight)
}

function carregarMensagens(){
  if(!USER_ID || !OUTRO_ID) return

  $.post(CHAT_API,{
    op:2,
    userId:USER_ID,
    outroId:OUTRO_ID,
    afterId:LAST_ID
  }, r=>{
    r.forEach(renderMensagem)
  })
}


/* ================= ABRIR ================= */

function abrirConversa(id,nome){
  if(!id) return

  OUTRO_ID = id
  LAST_ID = 0

  $('.conversation-item').removeClass('active')
  $(`.conversation-item[data-id="${id}"]`).addClass('active')

  $('#chat-messages').empty()
  $('.username').text(nome)

  $.post(CHAT_API,{ op:4, userId:USER_ID, outroId:id })

  carregarMensagens()
  iniciarPollingMensagens()
}

/* ================= POLLING ================= */

function iniciarPollingMensagens(){
  clearInterval(TIMER_MSG)
  TIMER_MSG = setInterval(carregarMensagens,2000)
}

function iniciarPollingConversas(){
  clearInterval(TIMER_CONV)
  TIMER_CONV = setInterval(()=>{
    carregarConversas(false)
  },3000)
}

/* ================= EVENTOS ================= */

$(document).on('click','.conversation-item',function(){
  abrirConversa(
    parseInt($(this).data('id')),
    $(this).data('nome')
  )
})

$('#chat-input').on('keydown',function(e){
  if(e.key==='Enter' && !e.shiftKey){
    e.preventDefault()
    $('#chat-form').submit()
  }
})

$('#chat-form').on('submit',function(e){
  e.preventDefault()

  let msg = $('#chat-input').val().trim()
  if(!msg || !OUTRO_ID) return

  $('#chat-input').val('')

  renderMensagem({
    id: Date.now(),
    id_remetente: USER_ID,
    mensagem: msg,
    data_envio: new Date().toISOString().slice(0,19).replace('T',' ')
  })

  $.post(CHAT_API,{
    op:3,
    userId:USER_ID,
    outroId:OUTRO_ID,
    msg:msg
  },()=>{
    carregarConversas(false)
  })
})

/* ================= NOVA CONVERSA ================= */

$('#btnNovaConversa').on('click',()=>{
  $('#modalNovaConversa').modal('show')
})

$('#novo-destinatario').select2({
  dropdownParent:$('#modalNovaConversa'),
  ajax:{
    url:CHAT_API,
    type:'POST',
    dataType:'json',
    data:p=>({ op:6, q:p.term||'' }),
    processResults:r=>({ results:r||[] })
  }
})

$('#confirmNovaConversa').on('click',()=>{
  let id = parseInt($('#novo-destinatario').val())
  let nome = $('#novo-destinatario option:selected').text()
  if(!id) return

  $('#modalNovaConversa').modal('hide')
  abrirConversa(id,nome)
})

/* ================= INIT ================= */

$(function(){
  USER_ID = parseInt($('#userId').val())
  if(!USER_ID) return

  carregarConversas(true)
  iniciarPollingConversas()
})
