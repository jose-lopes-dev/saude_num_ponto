/* fornecedor.js — robusto no filtro e sem reinit da DataTable */

function alerta(titulo, msg, icon) {
  Swal.fire({ position: "center", icon, title: titulo, text: msg });
}

function limparModal() {
  $("#fornecedor, #descricao, #totaldebito, #total_credito, #saldo, #data").val("");
}

/* ===================== DataTables ===================== */

let DT = null;

function dtInit() {
  if (DT) return DT;
  DT = $("#tabelaFornecedores").DataTable({
    pageLength: 10,
    ordering: true,
    retrieve: true,   // se chamarem outra vez, reaproveita
    destroy: false,   // não destruímos; reutilizamos
    columnDefs: [{ orderable: false, targets: [-1, -2] }],
  });
  return DT;
}

// extrai <tr> de várias formas de resposta (só linhas ou tabela inteira)
function extractRows(htmlString) {
  const $frag = $("<div>").html(htmlString);
  let $rows = $frag.find("tbody tr");
  if ($rows.length) return $rows;          // veio tabela completa
  $rows = $frag.find("tr");
  if ($rows.length) return $rows;          // vieram <tr> “soltos”
  return $frag.contents().filter("tr");    // último recurso
}

/* ===================== CRUD ===================== */

function registaFornecedor() {
  const dados = new FormData();
  dados.append("op", 1);
  dados.append("fornecedor", $("#fornecedor").val());
  dados.append("descricao", $("#descricao").val());
  dados.append("total_debito", $("#totaldebito").val());
  dados.append("total_credito", $("#total_credito").val());
  dados.append("saldo", $("#saldo").val());
  dados.append("data", $("#data").val());

  const page = DT ? DT.page() : 0;

  $.ajax({
    url: "src/controller/controllerFornecedor.php",
    method: "POST",
    data: dados,
    contentType: false,
    processData: false,
  })
    .done((msg) => {
      const obj = JSON.parse(msg);
      if (!obj.flag) return alerta("Fornecedor", obj.msg, "error");
      alerta("Fornecedor", obj.msg, "success");
      limparModal();
      $("#showModal").modal("hide");
      getListaFornecedores(undefined, page);
    })
    .fail(() => alerta("Fornecedor", "Erro no servidor", "error"));
}

function getListaFornecedores(mes = "", manterPagina = 0) {
  const dados = new FormData();
  dados.append("op", 2);
  dados.append("mes", mes ?? ""); // envia sempre a chave

  $.ajax({
    url: "src/controller/controllerFornecedor.php",
    method: "POST",
    data: dados,
    contentType: false,
    processData: false,
  })
    .done((html) => {
      // Atualiza linhas via API (sem destruir a tabela)
      const dt = dtInit();
      const $rows = extractRows(html);

      dt.clear();
      if ($rows && $rows.length) {
        // DataTables aceita um array de arrays, mas também jQuery TRs
        dt.rows.add($rows);
      } else {
        // fallback: mostra uma linha “sem dados”
        dt.rows.add(
          $( "<tr><td colspan='8' class='text-center text-muted'>Sem dados para o período selecionado.</td></tr>" )
        );
      }
      dt.draw(false);
      if (manterPagina != null) dt.page(manterPagina).draw("page");
    })
    .fail(() => {
      const dt = dtInit();
      dt.clear().rows.add(
        $( "<tr><td colspan='8' class='text-center text-danger'>Erro ao carregar dados.</td></tr>" )
      ).draw(false);
    });
}

function filtrarPorMes() {
  const mes = $("#filtroMes").val();
  const page = DT ? DT.page() : 0;
  getListaFornecedores(mes, page);
}

function getDadosFornecedor(id) {
  const dados = new FormData();
  dados.append("op", 4);
  dados.append("id", id);

  $.ajax({
    url: "src/controller/controllerFornecedor.php",
    method: "POST",
    data: dados,
    contentType: false,
    processData: false,
  })
    .done((msg) => {
      const obj = JSON.parse(msg);
      $("#editFornecedor").val(obj.fornecedor);
      $("#editDescricao").val(obj.descricao);
      $("#editTotalDebito").val(obj.total_debito);
      $("#editTotalCredito").val(obj.total_credito);
      $("#editSaldo").val(obj.saldo);
      $("#editData").val(obj.data);
      $("#btnGuardar").attr("onclick", "guardaEditFornecedor(" + id + ")");
      $("#modalEditar").modal("show");
    })
    .fail(() => alerta("Fornecedor", "Erro ao obter dados", "error"));
}

function guardaEditFornecedor(id) {
  const dados = new FormData();
  dados.append("op", 5);
  dados.append("id", id);
  dados.append("fornecedor", $("#editFornecedor").val());
  dados.append("descricao", $("#editDescricao").val());
  dados.append("total_debito", $("#editTotalDebito").val());
  dados.append("total_credito", $("#editTotalCredito").val());
  dados.append("saldo", $("#editSaldo").val());
  dados.append("data", $("#editData").val());

  const page = DT ? DT.page() : 0;

  $.ajax({
    url: "src/controller/controllerFornecedor.php",
    method: "POST",
    data: dados,
    contentType: false,
    processData: false,
  })
    .done((msg) => {
      const obj = JSON.parse(msg);
      if (!obj.flag) return alerta("Fornecedor", obj.msg, "error");
      alerta("Fornecedor", obj.msg, "success");
      $("#modalEditar").modal("hide");
      getListaFornecedores(undefined, page);
    })
    .fail(() => alerta("Fornecedor", "Erro ao guardar", "error"));
}

function concluirFornecedor(id) {
  Swal.fire({
    title: "Concluir fornecedor?",
    text: "Após concluir, o registo será marcado como concluído.",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sim, concluir",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#0d6efd",
  }).then((res) => {
    if (!res.isConfirmed) return;

    const dados = new FormData();
    dados.append("op", 3);
    dados.append("id", id);

    const page = DT ? DT.page() : 0;

    $.ajax({
      url: "src/controller/controllerFornecedor.php",
      method: "POST",
      data: dados,
      contentType: false,
      processData: false,
    })
      .done((msg) => {
        const obj = JSON.parse(msg);
        if (!obj.flag) return alerta("Fornecedor", obj.msg, "error");
        Swal.fire("Concluído!", obj.msg, "success");
        getListaFornecedores(undefined, page);
      })
      .fail(() => alerta("Fornecedor", "Erro ao concluir", "error"));
  });
}

/* ===================== boot ===================== */

$(document).ready(() => {
  dtInit();
  getListaFornecedores();

  // evita binds duplicados
  $("#filtroMes").off("change.fornecedor").on("change.fornecedor", filtrarPorMes);
});
