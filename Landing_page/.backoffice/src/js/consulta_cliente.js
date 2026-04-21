/* ============================================================
   consulta_cliente.js
   - Cliente: marcar consultas + listar próximas confirmadas
   - Select2 nos selects (para aplicar o teu estilo do global.css)
   ============================================================ */

$(document).ready(function () {

  /* -----------------------------
     0) Helpers
  ----------------------------- */

  function resetPrestador(mensagem) {
    const msg = mensagem || "Selecione o serviço primeiro";
    $("#id_prestador")
      .html(`<option value="">${msg}</option>`)
      .val(null)
      .trigger("change.select2");
  }

  function setPrestadorLoading() {
    $("#id_prestador")
      .html('<option value="">A carregar...</option>')
      .val(null)
      .trigger("change.select2");
  }

  function initSelect2() {
    // Nota: isto assume que já tens select2 carregado no HTML (CSS+JS) e jQuery antes deste ficheiro
    $("#id_servico").select2({
      width: "100%",
      placeholder: "Selecione",
      allowClear: false,
      minimumResultsForSearch: Infinity
    });

    $("#id_prestador").select2({
      width: "100%",
      placeholder: "Selecione o serviço primeiro",
      allowClear: false
    });
  }

  function initCalendar() {
    $("#data_hora").flatpickr({
      enableTime: true,
      dateFormat: "Y-m-d H:i",
      time_24hr: true,
      minDate: "today"
    });
  }

  /* -----------------------------
     1) Tabela: próximas consultas
  ----------------------------- */
  function carregarProximasConsultas() {

    const tbody = $("#tabela-proximas-consultas");

    tbody.html(`
      <tr>
        <td colspan="5" class="text-center text-muted">
          A carregar...
        </td>
      </tr>
    `);

    $.ajax({
      url: "./src/controller/consultaController.php",
      type: "POST",
      dataType: "json",
      data: { acao: "proximasConsultasCliente" },

      success: function (dados) {

        tbody.empty();

        if (!Array.isArray(dados) || dados.length === 0) {
          tbody.append(`
            <tr>
              <td colspan="5" class="text-center text-muted">
                Sem consultas pendentes ou confirmadas.
              </td>
            </tr>
          `);
          return;
        }

        dados.forEach(c => {
          tbody.append(`
            <tr>
              <td>${c.servico}</td>
              <td>${c.profissional}</td>
              <td>${c.data}</td>
              <td>${c.hora}</td>
              <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-success">
                  Entrar na consulta
                </button>
              </td>
            </tr>
          `);
        });
      },

      error: function () {
        tbody.html(`
          <tr>
            <td colspan="5" class="text-center text-danger">
              Erro ao carregar consultas.
            </td>
          </tr>
        `);
      }
    });
  }

  /* -----------------------------
     2) Sessão (antes de tudo)
  ----------------------------- */
  function validarSessaoCliente() {
    $.ajax({
      url: "./src/controller/consultaController.php",
      type: "POST",
      dataType: "json",
      data: { acao: "sessionCliente" },

      success: function (r) {
        if (!r.id || r.id == 0) {
          Swal.fire("Sessão Expirada", "Por favor volta a iniciar sessão.", "error");
          return;
        }

        $("#codigo_cliente").val(r.id);

        // Só depois de sessão válida
        carregarProximasConsultas();
      },

      error: function () {
        Swal.fire("Erro", "Falha ao validar sessão.", "error");
      }
    });
  }

  /* -----------------------------
     3) Carregar profissionais (dependente do serviço)
  ----------------------------- */
  function bindServicoChange() {

    $("#id_servico").on("change", function () {

      const servico = $(this).val();

      // Se estiver vazio, não faz AJAX (evita “deixar de funcionar tudo”)
      if (!servico) {
        resetPrestador("Selecione o serviço primeiro");
        return;
      }

      setPrestadorLoading();

      $.ajax({
        url: "./src/controller/consultaController.php",
        type: "POST",
        dataType: "json",
        data: {
          acao: "prestadores",
          id_servico: servico
        },

        success: function (dados) {

          if (!Array.isArray(dados)) {
            Swal.fire("Erro", "Erro ao carregar profissionais.", "error");
            resetPrestador("Selecione o serviço primeiro");
            return;
          }

          $("#id_prestador").html('<option value="">Selecione</option>');

          dados.forEach(p => {
            $("#id_prestador").append(
              `<option value="${p.id}">${p.nome}</option>`
            );
          });

          // refresh do select2 para reconhecer as novas options
          $("#id_prestador").val(null).trigger("change.select2");
        },

        error: function () {
          Swal.fire("Erro", "Falha ao comunicar com o servidor.", "error");
          resetPrestador("Selecione o serviço primeiro");
        }
      });
    });
  }

  /* -----------------------------
     4) Marcar consulta
  ----------------------------- */
  function bindFormSubmit() {

    $("#form-consulta").on("submit", function (e) {
      e.preventDefault();

      $.ajax({
        url: "./src/controller/consultaController.php",
        type: "POST",
        dataType: "json",
        data: {
          acao: "marcar",
          id_cliente: $("#codigo_cliente").val(),
          id_servico: $("#id_servico").val(),
          id_prestador: $("#id_prestador").val(),
          data_hora: $("#data_hora").val()
        },

        success: function (r) {

          if (r.status === "success") {
            Swal.fire("Sucesso", "Pedido de consulta enviado. Aguarda confirmação do profissional.", "success");

            // Reset do form (sem disparar AJAX do serviço)
            $("#form-consulta")[0].reset();

            // Reset "silencioso" do Select2 (não uses trigger("change") aqui)
            $("#id_servico").val(null).trigger("change.select2");
            resetPrestador("Selecione o serviço primeiro");

            carregarProximasConsultas();
          } else {
            Swal.fire("Erro", r.msg || "Ocorreu um erro.", "error");
          }
        },

        error: function () {
          Swal.fire("Erro", "Falha ao comunicar com o servidor.", "error");
        }
      });
    });
  }

  /* -----------------------------
     5) Init
  ----------------------------- */
  initSelect2();
  initCalendar();
  validarSessaoCliente();
  bindServicoChange();
  bindFormSubmit();

});
