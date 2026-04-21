(function ($) {
  'use strict';

  $(function () {
    console.log("tarefa.js final carregado");

    carregarTiposObrigacao();
    carregarEstados();
    listarTarefas();

    $('#formTarefa').on('submit', function (e) {
      e.preventDefault();
      guardarTarefa();
    });

    $('#btnFiltrar').on('click', filtrarTarefas);

    $('#modalTarefa').on('show.bs.modal', function () {
      if (!$('#taskId').val()) {
        $('#formTarefa')[0].reset();
        carregarTiposObrigacao();
        carregarEstados();
      }
    });

    $('#modalTarefa').on('hidden.bs.modal', function () {
      $('#formTarefa')[0].reset();
      $('#taskId').val('');
    });
  });

  // ========================
  // LISTAR
  // ========================
 function listarTarefas() {
  $.ajax({
    url: 'src/controller/controllerTarefa.php',
    type: 'POST',
    data: { op: 'listar' },
    success: function (html) {
      $('#listagemTarefas').html(html);

      // 🔽 Invertemos a ordem das linhas
      const $tbody = $('#tasksTable tbody');
      $tbody.html($tbody.find('tr').get().reverse());

      aplicarClassesEstado();
      tratarLinhasConcluidas();
      aplicarPaginacao();
    },
    error: function () {
      Swal.fire('Erro', 'Falha ao listar tarefas.', 'error');
    }
  });
}


  // ========================
  // ESTADOS
  // ========================
  function carregarEstados() {
    $.post('src/controller/controllerTarefa.php', { op: 'estados' }, function (html) {
      $('#estado').html(html);
      const pendente = $('#estado option').filter(function () {
        return $(this).text().trim().toLowerCase() === 'pendente';
      }).first();
      if (pendente.length) $('#estado').val(pendente.val());
    });
  }

  // ========================
  // TIPOS
  // ========================
  function carregarTiposObrigacao() {
    $.post('src/controller/controllerTarefa.php', { op: 'tipos' }, function (html) {
      $('#tipoObrigacao').html(html);
      const fornecedor = $('#tipoObrigacao option').filter(function () {
        return $(this).text().trim().toLowerCase() === 'fornecedor';
      }).first();
      if (fornecedor.length) $('#tipoObrigacao').val(fornecedor.val());
    });
  }

  // ========================
  // GUARDAR
  // ========================
  function guardarTarefa() {
    if (
      !$('#tipoObrigacao').val() ||
      !$('#descricao').val() ||
      !$('#valor').val() ||
      !$('#dataVencimento').val() ||
      !$('#dataPagamento').val() ||
      !$('#estado').val()
    ) {
      Swal.fire('Aviso', 'Preencha todos os campos.', 'warning');
      return;
    }

    const dados = {
      op: 'guardar',
      id: $('#taskId').val(),
      id_tipo_obrigacao: $('#tipoObrigacao').val(),
      descricao: $('#descricao').val(),
      valor: $('#valor').val(),
      data_vencimento: $('#dataVencimento').val(),
      data_pagamento: $('#dataPagamento').val(),
      id_estado: $('#estado').val()
    };

    $.ajax({
      url: 'src/controller/controllerTarefa.php',
      type: 'POST',
      data: dados,
      success: function (resposta) {
        let r;
        try { r = JSON.parse(resposta); } catch { r = { flag: false, msg: resposta }; }

        Swal.fire({
          title: 'Tarefa',
          text: r.msg,
          icon: r.flag ? 'success' : 'error',
          confirmButtonText: 'OK',
          confirmButtonColor: r.flag ? '#198754' : '#dc3545'
        });

        if (r.flag) {
          $('#modalTarefa').modal('hide');
          listarTarefas();
        }
      },
      error: function () {
        Swal.fire('Erro', 'Falha ao guardar tarefa.', 'error');
      }
    });
  }

  // ========================
  // EDITAR
  // ========================
  function editarTarefa(id) {
    $.ajax({
      url: 'src/controller/controllerTarefa.php',
      type: 'POST',
      data: { op: 'editar', id: id },
      success: function (resposta) {
        let t;
        try { t = JSON.parse(resposta); } catch { return Swal.fire('Erro', 'Falha ao carregar tarefa.', 'error'); }

        $('#taskId').val(t.id);
        $('#tipoObrigacao').val(t.id_tipo_obrigacao);
        $('#descricao').val(t.descricao);
        $('#valor').val(t.valor);
        $('#dataVencimento').val(t.data_vencimento);
        $('#dataPagamento').val(t.data_pagamento);
        $('#estado').val(t.id_estado);

        $('#modalTarefa').modal('show');
      },
      error: function () {
        Swal.fire('Erro', 'Falha ao carregar dados para edição.', 'error');
      }
    });
  }

  // ========================
  // CONCLUIR
  // ========================
  function concluirTarefa(id) {
    Swal.fire({
      title: 'Concluir tarefa?',
      text: 'Após concluir, a tarefa ficará marcada como concluída.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sim, concluir',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#198754'
    }).then((res) => {
      if (res.isConfirmed) {
        $.ajax({
          url: 'src/controller/controllerTarefa.php',
          type: 'POST',
          data: { op: 'concluir', id: id },
          success: function (resposta) {
            let r;
            try { r = JSON.parse(resposta); } catch { r = { flag: false, msg: resposta }; }

            if (r.flag) {
              Swal.fire('Concluído', 'Tarefa marcada como concluída.', 'success');
              listarTarefas();
            } else {
              Swal.fire('Erro', r.msg || 'Não foi possível concluir.', 'error');
            }
          },
          error: function () {
            Swal.fire('Erro', 'Falha ao concluir tarefa.', 'error');
          }
        });
      }
    });
  }

  // ========================
  // FILTRAR
  // ========================
  function filtrarTarefas() {
    const texto = $('#pesquisaTexto').val().trim();
    const intervalo = $('#date-range').val().trim();

    $.ajax({
      url: 'src/controller/controllerTarefa.php',
      type: 'POST',
      data: { op: 'filtrar', texto: texto, intervalo: intervalo },
      success: function (html) {
        $('#listagemTarefas').html(html);
        aplicarClassesEstado();
        tratarLinhasConcluidas();
        aplicarPaginacao();
      }
    });
  }

  // ========================
  // CORES DOS ESTADOS
  // ========================
  function aplicarClassesEstado() {
    const estadoIndex = $('#tasksTable thead th').filter(function () {
      return $(this).text().trim().toLowerCase() === 'estado';
    }).index();

    $('#listagemTarefas tr').each(function () {
      const $tds = $(this).find('td');
      if (estadoIndex === -1 || $tds.length <= estadoIndex) return;

      const $estadoTd = $tds.eq(estadoIndex);
      const texto = $estadoTd.text().trim();
      const t = texto.toLowerCase();

      let classe = 'badge bg-secondary';
      if (/pago/i.test(t)) classe = 'badge badge-pago';
      else if (/pendente|pedente/i.test(t)) classe = 'badge badge-pendente';
      else if (/atrasad|em atraso/i.test(t)) classe = 'badge badge-atrasado';
      else if (/conclu/i.test(t)) classe = 'badge badge-concluido';

      $estadoTd.html(`<span class="${classe}">${texto}</span>`);
    });
  }

  // ========================
  // ESCONDER ESTADO + BOTÕES SE CONCLUÍDO
  // ========================
  function tratarLinhasConcluidas() {
    $('#tasksTable tbody tr').each(function () {
      const $tds = $(this).find('td');
      const estadoTd = $tds.eq(6); // coluna estado
      const texto = estadoTd.text().trim().toLowerCase();

      // verifica se é concluído
      if (texto.includes('conclu') || texto.includes('bloquead') || texto.includes('16')) {
        // remove estado e botões
        $(this).find('td:nth-child(7), td:nth-child(8), td:nth-child(9)').remove();
        $(this).append(`<td colspan="3" class="text-center"><span class="badge badge-concluido">CONCLUÍDO</span></td>`);
      }
    });
  }

  // ========================
  // PAGINAÇÃO
  // ========================
  function aplicarPaginacao() {
    const linhas = $('#tasksTable tbody tr');
    const max = 10;
    if (linhas.length <= max) return;
    let pagina = 1;
    const total = Math.ceil(linhas.length / max);

    function mostrar(p) {
      linhas.hide().slice((p - 1) * max, p * max).show();
    }

    mostrar(pagina);
    $('#pagination-controls').remove();
    $('#tasksTable').after(`
      <div id="pagination-controls" style="text-align:right;margin-top:8px;">
        <button id="prev" class="btn btn-sm btn-outline-light">&lt; Anterior</button>
        <span class="badge bg-dark">Página ${pagina} de ${total}</span>
        <button id="next" class="btn btn-sm btn-outline-light">Próximo &gt;</button>
      </div>
    `);

    $('#prev').on('click', function () {
      if (pagina > 1) { pagina--; mostrar(pagina); }
    });
    $('#next').on('click', function () {
      if (pagina < total) { pagina++; mostrar(pagina); }
    });
  }

  // ========================
  // EXPORTAR
  // ========================
  window.listarTarefas = listarTarefas;
  window.editarTarefa = editarTarefa;
  window.concluirTarefa = concluirTarefa;
  window.filtrarTarefas = filtrarTarefas;
})(jQuery);
