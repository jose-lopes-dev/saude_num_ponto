$(function () {

  $('#openChat').on('click', function () {
    $('#chatContainer').removeClass('hidden')
  })

  $('#closeChat').on('click', function () {
    $('#chatContainer').addClass('hidden')
  })

  let passo = 0
  let respostas = {}

  function enviarPergunta(texto, info = null, extraClass = "") {

    let infoHtml = ""

    if (info) {
      infoHtml = `<span class="info-icon" data-info="${info}">ⓘ</span>`
    }

    $('#chat').append(`
      <div class="bot msg ${extraClass}">
        <p>${texto} ${infoHtml}</p>
      </div>
    `)

    scroll()
  }

  function enviarResposta(texto) {
    $('#chat').append('<div class="user msg"><p>' + texto + '</p></div>')
    scroll()
  }

  function scroll() {
    let div = document.getElementById('chat')
    if (div) div.scrollTop = div.scrollHeight
  }

  const perguntas = [
    {
      tipo: "input",
      texto: "Qual é o teu peso atual? (kg)",
      campo: "peso",
      info: "Peso atual do teu corpo em quilogramas"
    },
    {
      tipo: "input",
      texto: "E a tua altura? (m)",
      campo: "altura",
      info: "A tua altura em metros (ex: 1.75)"
    },
    {
      tipo: "input",
      texto: "Qual é o teu peso pretendido? (kg)",
      campo: "peso_pretendido",
      info: "Peso que gostarias de atingir"
    },
    {
      tipo: "bd",
      texto: "Qual é o teu objetivo?",
      campo: "id_objetivo",
      op: 10,
      info: "Perder gordura, ganhar massa muscular ou manter forma"
    },
    {
      tipo: "bd",
      texto: "Qual é o teu nível de atividade?",
      campo: "id_nivel",
      op: 11,
      info: "Sedentário, moderado ou muito ativo"
    },
    {
      tipo: "bd",
      texto: "Que atividades fazes normalmente?",
      campo: "id_atividades",
      op: 12,
      info: "Exercícios ou desportos que praticas com mais frequência"
    },
    {
      tipo: "bd",
      texto: "Qual é o teu tipo de corpo?",
      campo: "id_tipo_corpo",
      op: 13,
      info: `
Ectomorfo – corpo magro, dificuldade em ganhar peso

Mesomorfo – corpo atlético, ganha músculo facilmente

Endomorfo – tendência a ganhar gordura
`
    },
    {
      tipo: "bd",
      texto: "Como é o teu dia-a-dia?",
      campo: "id_habito_diario",
      op: 14,
      info: "Trabalho sedentário, ativo ou muito ativo"
    },
    {
      tipo: "bd",
      texto: "Qual é a área do corpo que mais queres melhorar?",
      campo: "id_area_corpo",
      op: 15,
      info: "Zona do corpo onde queres ver mais resultados"
    },
    {
      tipo: "bd",
      texto: "Qual tipo de dieta preferes?",
      campo: "id_tipo_dieta",
      op: 16,
      info: "Ex: equilibrada, low carb, vegetariana, etc"
    },
    {
      tipo: "bd",
      texto: "Qual é o teu género?",
      campo: "genero",
      op: 17,
      info: "Usado apenas para personalizar cálculos"
    },
    {
      tipo: "bd",
      texto: "Tens alguma condição de saúde?",
      campo: "id_condicao_saude",
      op: 99,
      info: "Ex: diabetes, hipertensão, problemas articulares"
    }
  ]

  function proximaPergunta() {

    if (passo >= perguntas.length) {
      enviarPergunta("Obrigado! A gerar o teu plano...")
      enviarParaBD()
      return
    }

    let p = perguntas[passo]

    let classeExtra = ""
    if (p.campo === "id_tipo_corpo") {
      classeExtra = "pergunta-tipo-corpo"
    }

    enviarPergunta(p.texto, p.info || null, classeExtra)

    if (p.tipo === "input") return

    if (p.tipo === "bd") {

      $.post("./src/controller/chatbotController.php", { op: p.op })
        .done(function (res) {

          if (typeof res === "string" && res.startsWith("{")) {
            res = JSON.parse(res)
          }

          if (!res || !res.data || !Array.isArray(res.data)) {
            enviarPergunta("Erro ao carregar opções.")
            return
          }

          let html = "<div class='opcoes-inline'>"

          res.data.forEach(row => {
  if (!row || !row.nome) return
  html += `<button class="opcao" data-v="${row.id}">${row.nome}</button>`
})

          html += "</div>"

          enviarPergunta(html)
        })
    }
  }

  $('#enviar').on('click', function () {

    let txt = $('#msg').val().trim()
    if (!txt) return

    let p = perguntas[passo]

    if (p.tipo === "input") {
      enviarResposta(txt)
      respostas[p.campo] = txt
      $('#msg').val('')
      passo++
      proximaPergunta()
    }
  })

  $('#msg').on('keypress', function (e) {
    if (e.which === 13) {
      e.preventDefault()
      $('#enviar').click()
    }
  })

  $(document).on('click', '.opcao', function () {

    let v = $(this).data('v')
    let label = $(this).text()

    enviarResposta(label)

    let p = perguntas[passo]
    respostas[p.campo] = v

    passo++
    proximaPergunta()
  })

  function enviarParaBD() {

    let payload = { ...respostas, op: 8 }

    if (payload.altura) {
      let h = payload.altura.replace(",", ".")
      let n = parseFloat(h)
      if (n > 10) n /= 100
      payload.altura = n.toFixed(2)
    }

    enviarPergunta("Guardando...")

    $.post('./src/controller/chatbotController.php', payload)
      .done(function (res) {

        if (typeof res === "string" && res.startsWith("{")) {
          res = JSON.parse(res)
        }

        if (res && res.flag) {
          enviarPergunta("Plano gerado! A redirecionar...")
          setTimeout(() => {
            window.location.href = '../.backoffice/dashboard_cliente.php'
          }, 1500)
        } else {
          enviarPergunta("Erro ao guardar dados.")
        }
      })
  }

  proximaPergunta()

let tooltip = $('<div id="tooltip-info"></div>').appendTo('body')

tooltip.css({
  position: 'fixed',
  background: '#1b1b1b',
  color: '#fff',
  padding: '14px 16px',
  borderRadius: '12px',
  fontSize: '0.8rem',
  maxWidth: '260px',
  lineHeight: '1.6',
  boxShadow: '0 12px 24px rgba(0,0,0,.6)',
  display: 'none',
  zIndex: 99999,
  whiteSpace: 'pre-line'
})

$(document).on('mouseenter', '.info-icon', function (e) {
  tooltip.text($(this).data('info')).fadeIn(150)
})

$(document).on('mousemove', '.info-icon', function (e) {
  let x = e.clientX + 16
  let y = e.clientY + 16

  if (x + tooltip.outerWidth() > window.innerWidth) {
    x = e.clientX - tooltip.outerWidth() - 16
  }

  if (y + tooltip.outerHeight() > window.innerHeight) {
    y = e.clientY - tooltip.outerHeight() - 16
  }

  tooltip.css({ left: x, top: y })
})

$(document).on('mouseleave', '.info-icon', function () {
  tooltip.hide()
})


})
