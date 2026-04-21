function getListaClientesPt() {
  $.ajax({
    url: "src/controller/controllerCliente_pt.php",
    method: "POST",
    dataType: "json",
    data: { op: 2, idPt: $("#idPt").val() }, 
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
              <td class="text-wrap">${esc(r.objetivo || "-")}</td>
              <td class="text-center">${r.num_consultas || 0}</td>
              <td class="text-center">${ultima}</td>
              <td class="text-center"><span class="estado-badge ${badge.cls}">${esc(badge.txt)}</span></td>
              <td class="text-center">
                <button class="btn btn-sm btn-warning me-1" title="Editar" onclick="abrirModalCliente(${r.codigo})">
                  <i class="ri-edit-2-line"></i>
                </button>
                <button class="btn btn-sm ${r.id_estado == 2 ? "btn-danger" : "btn-success"}" 
                        onclick="confirmarToggleEstado(${r.codigo}, ${r.id_estado})">
                  <i class="${r.id_estado == 2 ? "ri-user-unfollow-line" : "ri-user-follow-line"}"></i>
                </button>
              </td>
            </tr>
          `;
        });
      }

      $("#listaClientesPt").html(html);
      $("#totalClientesTop").text(rows.length);
      
      // Reiniciar DataTables com paginação de 10 registos por página
      if ($.fn.dataTable.isDataTable('#tabelaClientesPt')) {
        $('#tabelaClientesPt').DataTable().destroy();
      }
      $('#tabelaClientesPt').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        paging: true,
        info: true,
        language: {
          "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json"
        }
      });
    },
    error: function (xhr) {
      console.log(xhr.responseText);
      Swal.fire("Clientes", "Erro ao carregar lista de clientes.", "error");
    }
  });
}

function badgeEstado(txt) {
  const t = (txt || "").toLowerCase();
  if (t === "ativo") return { cls: "bg-success-subtle text-success", txt: "Ativo" };
  if (t === "inativo") return { cls: "bg-danger-subtle text-danger", txt: "Inativo" };
  if (t === "aguardando confirmação") return { cls: "bg-warning-subtle text-warning", txt: "Pendente" };
  return { cls: "bg-secondary-subtle text-secondary", txt: (txt || "—") };
}

function esc(s){ return (s ?? "").toString().replaceAll("&","&amp;").replaceAll("<","&lt;").replaceAll(">","&gt;"); }
function formatDate(d){ return new Date(d).toLocaleDateString("pt-PT"); }

function removerClientePt(idCliente){

    Swal.fire({
        title: 'Desativar cliente?',
        text: 'O cliente ficará Inativo e deixará de aparecer como Ativo.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, desativar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#AACA1C'
    }).then((result) => {

        if(result.isConfirmed){

            let dados = new FormData();
            dados.append("op", 3); 
            dados.append("idCliente", idCliente);

            $.ajax({
                url: "src/controller/controllerCliente_pt.php",
                method: "POST",
                data: dados,
                dataType: "html",
                cache: false,
                contentType: false,
                processData: false
            }).done(function(msg){

                let obj = JSON.parse(msg);

                if(obj.flag){
                    Swal.fire({
                        title: 'Clientes',
                        text: obj.msg,
                        icon: 'success',
                        confirmButtonColor: '#AACA1C'
                    });

                    getListaClientesPt();
                    getStatsClientesPt();
                } else {
                    Swal.fire('Erro', obj.msg, 'error');
                }
            });
        }
    });
}

function abrirModalCliente(idCliente){
  $.ajax({
    url: "src/controller/controllerCliente_pt.php",
    type: "POST",
    dataType: "json",
    data: { op: 4, idCliente: idCliente }
  })
  .done(function(res){
    if(!res || res.flag !== true) return;

    const c = res.dados;

    $("#idClientePtEdit").val(c.codigo);
    $("#nomeClienteEdit").val(c.nome_completo ?? "");
    $("#telefoneClienteEdit").val(c.contacto ?? "");
    $("#emailClienteEdit").val(c.email ?? "");
    $("#dataNascClienteEdit").val(c.data_nascimento ?? "");

    // estado na tua BD: Ativo=2, Inativo=11
    $("#estadoClienteEdit").val(c.id_estado);

    // objetivo: se vier por descrição, mete direto
    $("#objetivoClienteEdit").val(c.objetivo ?? "");

    // notas: só se existir coluna
    $("#notasClienteEdit").val(c.notas ?? "");

    const el = document.getElementById("modalEditarClientePt");
    const modal = bootstrap.Modal.getOrCreateInstance(el);
    modal.show();
  });
}

function guardaEditClientePt(idCliente) {

    let dados = new FormData();
    dados.append("op", 5);
    dados.append("idCliente", idCliente);
    dados.append("nome", $('#nomeClienteEdit').val());
    dados.append("telefone", $('#telefoneClienteEdit').val()); 
    dados.append("email", $('#emailClienteEdit').val());
    dados.append("dataNasc", $('#dataNascClienteEdit').val());
    dados.append("estado", $('#estadoClienteEdit').val());
    dados.append("objetivo", $('#objetivoClienteEdit').val());

    $.ajax({
        url: "src/controller/controllerCliente_pt.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(resp) {
        if(resp.flag){
            alertaClientesPt("Clientes", resp.msg, "success");
            $('#modalEditarClientePt').modal('hide');
            getListaClientesPt();
        } else {
            alertaClientesPt("Clientes", resp.msg, "error");
        }
    });
}

function confirmarToggleEstado(idCliente, idEstadoAtual){

    const ATIVO = 2;
    const INATIVO = 11;

    const vaiPara = (parseInt(idEstadoAtual) === ATIVO) ? INATIVO : ATIVO;

    Swal.fire({
        title: 'Confirmar',
        text: (vaiPara === INATIVO) ? 'Queres mesmo desativar este cliente?' : 'Queres reativar este cliente?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if(result.isConfirmed){
            alteraEstadoClientePt(idCliente, vaiPara);
        }
    });
}

function btnEstadoHTML(idCliente, idEstado){
    if(parseInt(idEstado) === 2){
        return `<button class="btn btn-sm btn-danger"
                    onclick="confirmarToggleEstado(${idCliente}, ${idEstado})">
                    <i class="ri-user-unfollow-line"></i>
                </button>`;
    } else {
        return `<button class="btn btn-sm btn-success"
                    onclick="confirmarToggleEstado(${idCliente}, ${idEstado})">
                    <i class="ri-user-follow-line"></i>
                </button>`;
    }
}

function confirmarEstadoCliente(idCliente, novoEstado){
  const texto = (novoEstado === 11)
    ? "Queres desativar este cliente?"
    : "Queres ativar este cliente?";

  Swal.fire({
    title: "Clientes",
    text: texto,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sim",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#AACA1C"
  }).then((r) => {
    if(r.isConfirmed){
      alteraEstadoCliente(idCliente, novoEstado);
    }
  });
}

function alteraEstadoClientePt(idCliente, novoEstado){

    let dados = new FormData();
    dados.append("op", 6);
    dados.append("idCliente", idCliente);
    dados.append("novoEstado", novoEstado); 
    $.ajax({
        url: "src/controller/controllerCliente_pt.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    }).done(function(msg){
        let obj = JSON.parse(msg);
        if(obj.flag){
            alertaClientesPt("Clientes", obj.msg, "success");
            getListaClientesPt();
            getStatsClientesPt();
        } else {
            alertaClientesPt("Clientes", obj.msg, "error");
        }
    });
}

function limparFormularioClientePt() {
    $('#nomeCliente').val('');
    $('#telefoneCliente').val('');
    $('#emailCliente').val('');
    $('#dataNascCliente').val('');
    $('#estadoCliente').val('A');
    $('#objetivoCliente').val('');
    $('#notasCliente').val('');
}

function alertaClientesPt(titulo, msg, icon) {
    Swal.fire({
        position: 'center',
        icon: icon,
        title: titulo,
        text: msg,
        showConfirmButton: true
    });
}

function getStatsClientesPt() {
  $.ajax({
    url: "src/controller/controllerCliente_pt.php",
    type: "POST",
    dataType: "json",
    data: { op: 1, idPt: $("#idPt").val() }
  })
  .done(function(res){
    if(!res || res.flag !== true) return;

    const d = res.dados || {};
    $("#statTotalClientes").text(d.total_clientes ?? 0);
    $("#statConsultasSemana").text(d.consultas_semana ?? 0);
    $("#statConsultasFuturas").text(d.consultas_futuras ?? 0);
    $("#statTaxaConclusao").text((d.taxa_conclusao ?? 0) + "%");
  });
}

$(function() {
    getListaClientesPt();
    getStatsClientesPt();
});

