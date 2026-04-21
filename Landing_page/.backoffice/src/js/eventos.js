// eventos.js
// ==========================
//  DECLARATIVAS (Eventos)
// ==========================

// Escapar HTML (proteção XSS simples)
function escapeHtml(unsafe) {
  return String(unsafe || '').replace(/[&<>"'`=\/]/g, s => (
    {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'}[s]
  ));
}

/* =========================
   LISTAR EVENTOS (APENAS Obrigações Declarativas)
   ========================= */
function carregarEventos() {
  const dados = new FormData();
  dados.append("op", "listar");

  $.ajax({
    url: "src/controller/controllerEventos.php",
    method: "POST",
    data: dados,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (response) {
      if (!Array.isArray(response)) {
        $("#listagemEventos").html('<tr><td colspan="5" class="text-center">Resposta inválida</td></tr>');
        return;
      }

      let html = "";

      response.forEach(ev => {
        // Ignora eventos concluídos
        if (parseInt(ev.concluido) === 1) return;

        // Só mostrar eventos cuja categoria seja exatamente "Obrigações Declarativas" (case-insensitive)
        const categoria = (ev.categoria || '').toString().trim().toLowerCase();
        if (categoria !== 'obrigações declarativas' && categoria !== 'obrigações declarativas'.toLowerCase()) {
          return;
        }

        // Normalizar display da data (aceita "YYYY-MM-DDTHH:MM" ou "YYYY-MM-DD HH:MM:SS")
        let dataLimiteRaw = ev.data_fim || ev.data_inicio || '';
        // se veio com T -> troca para espaço
        dataLimiteRaw = dataLimiteRaw.replace('T', ' ');
        const dataLimite = dataLimiteRaw.substring(0, 16);

        html += `
          <tr data-id="${ev.id}">
            <td>${escapeHtml(ev.titulo)}</td>
            <td>${escapeHtml(ev.descricao)}</td>
            <td>${escapeHtml(dataLimite)}</td>
            <td class="text-center">
              <button class="btn btn-sm btn-primary" onclick="abrirEditar(${ev.id})" title="Editar">
                <i class="ri-edit-line"></i>
              </button>
            </td>
            <td class="text-center">
              <button class="btn btn-sm btn-success" onclick="confirmConcluir(${ev.id})" title="Concluir">
                <i class="ri-check-line"></i>
              </button>
            </td>
          </tr>`;
      });

      if (!html) html = '<tr><td colspan="5" class="text-center">Sem obrigações declarativas registadas</td></tr>';
      $("#listagemEventos").html(html);
    },
    error: function () {
      $("#listagemEventos").html('<tr><td colspan="5" class="text-center text-danger">Erro ao carregar eventos</td></tr>');
    }
  });
}

/* =========================
   REGISTAR NOVO EVENTO (pelo index)
   ========================= */
function registaEvento(e) {
  e.preventDefault();

  // obtém valores do formulário do modal do index
  const titulo = $("#evt_titulo").val();
  const descricao = $("#evt_descricao").val();
  const data_fim_raw = $("#evt_data_fim").val(); // datetime-local -> "YYYY-MM-DDTHH:MM"

  // validações simples
  if (!titulo || !data_fim_raw) {
    return Swal.fire("Atenção", "Preenche o título e a data limite.", "warning");
  }

  // converter para formato que o backend aceita (opcionalmente com segundos)
  let data_fim = data_fim_raw.replace('T', ' ');
  if (data_fim.length === 16) data_fim += ':00';

  const dados = {
    op: "inserir",
    titulo,
    descricao,
    data_fim,
    // como este modal é especificamente para Obrigações, definimos categoria explicitamente
    categoria: "Obrigações Declarativas",
    // opcional: enviar também data_inicio igual a data_fim
    data_inicio: data_fim
  };

  $.post("src/controller/controllerEventos.php", dados, resp => {
    if (resp && resp.success) {
      Swal.fire("Sucesso", "Evento registado com sucesso!", "success");
      bootstrap.Modal.getInstance(document.getElementById("modalNovoEvento")).hide();
      $("#formNovoEvento")[0].reset();
      carregarEventos();

      // notificar outras abas (ex: calendário) — utiliza localStorage (listener storage)
      try {
        localStorage.setItem('evento_atualizado', JSON.stringify({ tipo: 'inserir', categoria: 'Obrigações Declarativas', ts: Date.now() }));
      } catch (e) {}
    } else {
      Swal.fire("Erro", "Não foi possível registar o evento.", "error");
    }
  }, "json");
}

/* =========================
   ABRIR MODAL DE EDIÇÃO
   ========================= */
function abrirEditar(id) {
  // Pede a lista completa e procura o evento pelo id
  $.post("src/controller/controllerEventos.php", { op: "listar" }, lista => {
    const ev = (lista || []).find(x => Number(x.id) === Number(id));
    if (!ev) return Swal.fire("Erro", "Evento não encontrado.", "error");

    $("#edit_id").val(ev.id);
    $("#edit_titulo").val(ev.titulo);
    $("#edit_descricao").val(ev.descricao);

    // data_fim pode vir "YYYY-MM-DD HH:MM:SS" -> transformar para "YYYY-MM-DDTHH:MM" para datetime-local
    let df = ev.data_fim || ev.data_inicio || '';
    df = df.replace(' ', 'T').substring(0, 16);
    $("#edit_data_fim").val(df);

    new bootstrap.Modal(document.getElementById("modalEditarEvento")).show();
  }, "json");
}

/* =========================
   ATUALIZAR EVENTO (pelo index)
   ========================= */
function atualizarEvento(e) {
  e.preventDefault();

  const id = $("#edit_id").val();
  const titulo = $("#edit_titulo").val();
  const descricao = $("#edit_descricao").val();
  let data_fim_raw = $("#edit_data_fim").val(); // datetime-local
  if (!id || !titulo || !data_fim_raw) {
    return Swal.fire("Atenção", "Campos obrigatórios em falta.", "warning");
  }
  let data_fim = data_fim_raw.replace('T', ' ');
  if (data_fim.length === 16) data_fim += ':00';

  const dados = {
    op: "atualizar",
    id: id,
    titulo: titulo,
    descricao: descricao,
    data_fim: data_fim,
    data_inicio: data_fim,
    categoria: "Obrigações Declarativas" // manter categoria de obrigações
  };

  $.post("src/controller/controllerEventos.php", dados, resp => {
    if (resp && resp.success) {
      Swal.fire("Sucesso", "Evento atualizado com sucesso!", "success");
      bootstrap.Modal.getInstance(document.getElementById("modalEditarEvento")).hide();
      carregarEventos();

      // notificar outras abas sobre a edição
      try {
        localStorage.setItem('evento_atualizado', JSON.stringify({ tipo: 'editar', id: id, categoria: 'Obrigações Declarativas', ts: Date.now() }));
      } catch (e) {}
    } else {
      Swal.fire("Erro", "Não foi possível atualizar o evento.", "error");
    }
  }, "json");
}

/* =========================
   CONFIRMAR CONCLUSÃO
   ========================= */
function confirmConcluir(id) {
  Swal.fire({
    title: "Concluir evento?",
    text: "Após concluir, não será mais possível editar este evento.",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sim, concluir",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#198754"
  }).then(res => {
    if (res.isConfirmed) concluirEvento(id);
  });
}

/* =========================
   CONCLUIR EVENTO
   ========================= */
function concluirEvento(id) {
  $.post("src/controller/controllerEventos.php", { op: "concluir", id }, resp => {
    if (resp && resp.success) {
      Swal.fire({
        title: "Concluído!",
        text: "Evento marcado como concluído.",
        icon: "success",
        timer: 1500,
        showConfirmButton: false
      });

      const linha = $(`#listagemEventos tr[data-id='${id}']`);
      linha.find("td:nth-last-child(2)").remove(); // remove botão Editar
      linha.find("td:last").replaceWith(`
        <td class="text-center" colspan="2">
          <span class="badge badge-concluido">CONCLUÍDO</span>
        </td>
      `);

      setTimeout(() => {
        linha.fadeOut(600, function () {
          $(this).remove();
        });
      }, 5000);

      setTimeout(() => {
        carregarEventos();
      }, 5600);

      // notificar outras abas
      try {
        localStorage.setItem('evento_atualizado', JSON.stringify({ tipo: 'concluir', id: id, ts: Date.now() }));
      } catch (e) {}
    } else {
      Swal.fire("Erro", "Não foi possível concluir o evento.", "error");
    }
  }, "json");
}

/* =========================
   INICIALIZAÇÃO
   ========================= */
$(function () {
  carregarEventos();
  $("#formNovoEvento").on("submit", registaEvento);
  $("#formEditarEvento").on("submit", atualizarEvento);
});

/* =========================
   Listener cross-tab: atualiza a listagem quando outra aba grava em localStorage
   ========================= */
window.addEventListener('storage', function (e) {
  if (!e.key) return;
  if (e.key === 'evento_atualizado') {
    // actualiza a tabela imediatamente (não precisamos verificar payload aqui)
    carregarEventos();
  }
});
