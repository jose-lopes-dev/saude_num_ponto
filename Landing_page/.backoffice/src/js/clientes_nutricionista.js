function formatDate(dateStr) {
  if (!dateStr) return '-';
  const date = new Date(dateStr);
  return date.toLocaleDateString('pt-PT');
}

function esc(str) {
  return str ? String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;') : '';
}

function badgeEstado(txt) {
  const t = (txt || "").toLowerCase();
  if (t === "ativo") return { cls: "bg-success-subtle text-success", txt: "Ativo" };
  if (t === "inativo") return { cls: "bg-danger-subtle text-danger", txt: "Inativo" };
  if (t === "aguardando confirmação") return { cls: "bg-warning-subtle text-warning", txt: "Pendente" };
  return { cls: "bg-secondary-subtle text-secondary", txt: (txt || "—") };
}

function getListaClientesNutri() {
  $.ajax({
    url: "src/controller/controllerCliente_nutri.php",
    method: "POST",
    dataType: "json",
    data: { op: 2, idNutri: $("#idNutri").val() },
    success: function (res) {
      if (!res.flag) {
        Swal.fire("Clientes", res.msg || "Erro ao carregar lista de clientes.", "error");
        return;
      }

      const rows = res.dados || [];
      let html = "";

      if (rows.length === 0) {
        html += `<tr><td colspan="8" class="text-center">Sem clientes associados</td></tr>`;
      } else {
        rows.forEach(r => {
  const ultima = r.ultima_consulta ? formatDate(r.ultima_consulta) : "-";
  const badge = badgeEstado(r.estado_desc);

  html += `
    <tr>
      <td>${esc(r.nome_completo)}</td>
      <td>${esc(r.email || "-")}</td>
      <td>${esc(r.contacto || "-")}</td>
      <td>${esc(r.objetivo || "-")}</td>
      <td class="text-center">${r.num_consultas}</td>
      <td class="text-center">${ultima}</td>
      <td class="text-center">
        <span class="estado-badge ${badge.cls}">${badge.txt}</span>
      </td>
      <td class="text-center">
        <button class="btn btn-sm btn-warning me-1"
          onclick="abrirModalClienteNutri(${r.codigo})">
          <i class="ri-edit-2-line"></i>
        </button>
        <button class="btn btn-sm ${r.id_estado == 2 ? "btn-danger" : "btn-success"}"
          onclick="confirmarToggleEstadoNutri(${r.codigo}, ${r.id_estado})">
          <i class="${r.id_estado == 2 ? "ri-user-unfollow-line" : "ri-user-follow-line"}"></i>
        </button>
      </td>
    </tr>
  `;
});

      }

      $("#listaClientesNutri").html(html);
      $("#labelTotalClientesNutri").text("Total: " + rows.length);
    },
    error: function () {
      Swal.fire("Clientes", "Erro ao carregar lista de clientes.", "error");
    }
  });
}

function abrirModalClienteNutri(idCliente) {
  $.ajax({
    url: "src/controller/controllerCliente_nutri.php",
    type: "POST",
    dataType: "json",
    data: { op: 4, idCliente: idCliente }
  })
  .done(function(res){
    if(!res || res.flag !== true) return;

    const c = res.dados;

    $("#idClienteNutriEdit").val(c.codigo);
    $("#nomeClienteNutriEdit").val(c.nome_completo ?? "");
    $("#telefoneClienteNutriEdit").val(c.contacto ?? "");
    $("#emailClienteNutriEdit").val(c.email ?? "");
    $("#dataNascClienteNutriEdit").val(c.data_nascimento ?? "");
    $("#estadoClienteNutriEdit").val(c.id_estado);
    $("#objetivoClienteNutriEdit").val(c.objetivo ?? "");
    $("#notasClienteNutriEdit").val(c.notas ?? "");

    const modal = bootstrap.Modal.getOrCreateInstance(
      document.getElementById("modalEditarClienteNutri")
    );
    modal.show();
  });
}

function guardarClienteNutri() {
  let dados = new FormData();
  dados.append("op", 5);
  dados.append("idCliente", $("#idClienteNutriEdit").val());
  dados.append("nome", $("#nomeClienteNutriEdit").val());
  dados.append("telefone", $("#telefoneClienteNutriEdit").val());
  dados.append("email", $("#emailClienteNutriEdit").val());
  dados.append("dataNasc", $("#dataNascClienteNutriEdit").val());
  dados.append("estado", $("#estadoClienteNutriEdit").val());
  dados.append("objetivo", $("#objetivoClienteNutriEdit").val());

  $.ajax({
    url: "src/controller/controllerCliente_nutri.php",
    method: "POST",
    data: dados,
    dataType: "json",
    contentType: false,
    processData: false
  })
  .done(function(res){
    if(res.flag){
      Swal.fire("Clientes", res.msg, "success");
      $("#modalEditarClienteNutri").modal("hide");
      getListaClientesNutri();
    } else {
      Swal.fire("Erro", res.msg, "error");
    }
  });
}

function confirmarToggleEstadoNutri(idCliente, estadoAtual) {
  const novo = (parseInt(estadoAtual) === 2) ? 11 : 2;

  Swal.fire({
    title: "Confirmar",
    text: novo === 11 ? "Desativar este cliente?" : "Ativar este cliente?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sim",
    cancelButtonText: "Cancelar"
  }).then(r => {
    if (r.isConfirmed) {
      alteraEstadoClienteNutri(idCliente, novo);
    }
  });
}

function alteraEstadoClienteNutri(idCliente, novoEstado) {
  let dados = new FormData();
  dados.append("op", 6);
  dados.append("idCliente", idCliente);
  dados.append("novoEstado", novoEstado);

  $.ajax({
    url: "src/controller/controllerCliente_nutri.php",
    method: "POST",
    data: dados,
    dataType: "json",
    contentType: false,
    processData: false
  })
  .done(function(res){
    if(res.flag){
      Swal.fire("Clientes", res.msg, "success");
      getListaClientesNutri();
      getStatsClientesNutri();
    } else {
      Swal.fire("Erro", res.msg, "error");
    }
  });
}

function getStatsClientesNutri() {
  $.ajax({
    url: "src/controller/controllerCliente_nutri.php",
    type: "POST",
    dataType: "json",
    data: { op: 1, idNutri: $("#idNutri").val() }
  })
  .done(function(res){
    if(!res || !res.flag) return;

    const d = res.dados || {};
    $("#statTotalClientesNutri").text(d.total_clientes ?? 0);
    $("#statConsultasSemanaNutri").text(d.consultas_semana ?? 0);
    $("#statConsultasFuturasNutri").text(d.consultas_futuras ?? 0);
    $("#statTaxaConclusaoNutri").text((d.taxa_conclusao ?? 0) + "%");
  });
}

$(function(){
  getListaClientesNutri();
  getStatsClientesNutri();
});
