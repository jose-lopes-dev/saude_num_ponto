function resetDataTable(tableId){
  const $t = $(tableId);

  if ($.fn.DataTable.isDataTable($t)) {
    $t.DataTable().clear(); 
    $t.DataTable().destroy(); 
  }

  $t.find('colgroup').remove();
}

function alerta(titulo,msg,icon){
    Swal.fire({ position:'center', icon, title: titulo, text: msg, showConfirmButton:true });
}
let dtClientesPt = null;

function initDataTableClientesPt() {
  if (dtClientesPt) return;

  dtClientesPt = $("#tabelaClientesPt").DataTable({
    responsive: true,
    pageLength: 10,
    autoWidth: false,
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json"
    },
    columns: [
      { data: "nome_completo" },
      { data: "email", defaultContent: "-" },
      { data: "contacto", defaultContent: "-" },
      {
        data: "objetivo",
        defaultContent: "-",
        render: function (data) {
          const txt = data || "-";
          return `<div class="td-objetivo">${esc(txt)}</div>`;
        }
      },
      { data: "num_consultas", className: "text-center", defaultContent: 0 },
      {
        data: "ultima_consulta",
        className: "text-center",
        render: function (data) {
          return data ? formatDateTime(data) : "-";
        }
      },
      {
        data: null,
        className: "text-center",
        render: function (row) {
          const b = badgeEstado(row.estado_desc);
          return `<span class="estado-badge ${b.cls}">${esc(b.txt)}</span>`;
        }
      },
      {
        data: null,
        className: "text-center",
        orderable: false,
        searchable: false,
        render: function (row) {
          const btnEdit = `
            <button class="btn btn-sm btn-warning me-1" title="Editar"
              onclick="abrirModalCliente(${row.codigo})">
              <i class="ri-edit-2-line"></i>
            </button>`;

          const isAtivo = parseInt(row.id_estado) === 2;
          const btnEstado = `
            <button class="btn btn-sm ${isAtivo ? "btn-danger" : "btn-success"}"
              title="${isAtivo ? "Desativar" : "Ativar"}"
              onclick="confirmarToggleEstado(${row.codigo}, ${row.id_estado})">
              <i class="${isAtivo ? "ri-user-unfollow-line" : "ri-user-follow-line"}"></i>
            </button>`;

          return btnEdit + btnEstado;
        }
      }
    ]
  });
}

function getPlanosSistema(){
    let dados = new FormData();
    dados.append("op", 1);
    dados.append("idPt", $('#idPt').val());

    $.ajax({
        url: "src/controller/controllerServicos_pt.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache:false,
        contentType:false,
        processData:false
    }).done(function(msg){
        resetDataTable('#tblPlanosSistema');
        $('#listagemPlanosSistema').html(msg);
        $('#tblPlanosSistema').DataTable({
            language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json" },
            pageLength: 10,
            autoWidth: false,
            responsive: true
        });
    });
}

function getSelectServicosCatalogo(){
  let dados = new FormData();
  dados.append("op", 2);

  $.ajax({
    url: "src/controller/controllerServicos_pt.php",
    method: "POST",
    data: dados,
    dataType: "html",
    cache:false,
    contentType:false,
    processData:false
  }).done(function(msg){
    $('#selectServicosCatalogo').html(msg).trigger('change');
  });
}

function getMeusServicos(){
  resetDataTable('#tblMeusServicos');

  let estado = -1;
  if ($('#filtroEstadoServicos').length) {
    estado = parseInt($('#filtroEstadoServicos').val());
    if (isNaN(estado)) estado = -1;
  }

  let dados = new FormData();
  dados.append("op", 3);
  dados.append("idPt", $('#idPt').val());
  dados.append("estado", estado);

  $.ajax({
    url: "src/controller/controllerServicos_pt.php",
    method: "POST",
    data: dados,
    dataType: "html",
    cache:false,
    contentType:false,
    processData:false
  }).done(function(msg){

    $('#listagemMeusServicos').html(msg);

    $('#tblMeusServicos').DataTable({
      responsive: true,
      pageLength: 10,
      autoWidth: false,
      language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json" },
      columnDefs: [{ orderable: false, targets: [3] }]
    });
  });
}

function adicionaServicoAoPt(){
    let dados = new FormData();
    dados.append("op", 4);
    dados.append("idServico", $('#selectServicosCatalogo').val());
    dados.append("idPt", $('#idPt').val());

    $.ajax({
        url: "src/controller/controllerServicos_pt.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache:false,
        contentType:false,
        processData:false
    }).done(function(msg){
        let obj = JSON.parse(msg);
        alerta("Serviços", obj.msg, obj.flag ? "success" : "error");
        if (obj.flag) {
        $('#selectServicosCatalogo').val('').trigger('change');
        getMeusServicos();
        }
    });
}

function toggleServicoPt(idServico){
    let dados = new FormData();
    dados.append("op", 5);
    dados.append("idServico", idServico);
    dados.append("idPt", $('#idPt').val());

    $.ajax({
        url: "src/controller/controllerServicos_pt.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache:false,
        contentType:false,
        processData:false
    }).done(function(msg){
        let obj = JSON.parse(msg);
        alerta("Serviços", obj.msg, obj.flag ? "success" : "error");
        if (obj.flag) getMeusServicos();
    });
}

function getSelectTiposAulaGrupo(){
    let dados = new FormData();
    dados.append("op", 7);
    dados.append("idPt", $('#idPt').val());

    $.ajax({
        url: "src/controller/controllerServicos_pt.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache:false,
        contentType:false,
        processData:false
    }).done(function(msg){
        $('#selectTiposAulaGrupo').html(msg).trigger('change');
    });
}

function getMeusTiposAulaGrupo(){
  let estado = -1;
  if ($('#filtroEstadoTiposAula').length) {
    estado = parseInt($('#filtroEstadoTiposAula').val());
    if (isNaN(estado)) estado = -1;
  }

  let dados = new FormData();
  dados.append("op", 8);
  dados.append("idPt", $('#idPt').val());
  dados.append("estado", estado);

  $.ajax({
    url: "src/controller/controllerServicos_pt.php",
    method: "POST",
    data: dados,
    dataType: "html",
    cache:false,
    contentType:false,
    processData:false
  }).done(function(msg){
    resetDataTable('#tblTiposAulaPt');
    $('#listagemTiposAulaPt').html(msg);
    $('#tblTiposAulaPt').DataTable({
      language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json" },
      pageLength: 10,
      autoWidth: false,
      responsive: true,
      columnDefs: [{ orderable: false, targets: [2] }]
    });
  });
}

function adicionaTipoAulaAoPt(){
    let dados = new FormData();
    dados.append("op", 9);
    dados.append("idPt", $('#idPt').val());
    dados.append("idTipo", $('#selectTiposAulaGrupo').val());

    $.ajax({
        url: "src/controller/controllerServicos_pt.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache:false,
        contentType:false,
        processData:false
    }).done(function(msg){
        let obj = JSON.parse(msg);
        alerta("Tipos de Aula", obj.msg, obj.flag ? "success" : "error");
        if (obj.flag) {
            $('#selectTiposAulaGrupo').val('').trigger('change');
            getMeusTiposAulaGrupo();
        }
    });
}

function toggleTipoAulaPt(idTipo){
    let dados = new FormData();
    dados.append("op", 10);
    dados.append("idPt", $('#idPt').val());
    dados.append("idTipo", idTipo);

    $.ajax({
        url: "src/controller/controllerServicos_pt.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache:false,
        contentType:false,
        processData:false
    }).done(function(msg){
        let obj = JSON.parse(msg);
        alerta("Tipos de Aula", obj.msg, obj.flag ? "success" : "error");
        if (obj.flag) getMeusTiposAulaGrupo();
    });
}

$(function(){
    $('#selectServicosCatalogo').select2();
    $('#selectTiposAulaGrupo').select2();
    $('#filtroEstadoServicos').select2({ placeholder: "Filtrar estado", allowClear: false, width: "100%" });
    $('#filtroEstadoTiposAula').select2({ placeholder: "Filtrar estado", allowClear: false, width: "100%" });
    
    getPlanosSistema();
    getMeusServicos();
    getSelectServicosCatalogo();
    getSelectTiposAulaGrupo();
    getMeusTiposAulaGrupo();

    $(document).on('change', '#filtroEstadoTiposAula', function(){
        getMeusTiposAulaGrupo();
    });

    $(document).on('change', '#filtroEstadoServicos', function(){
        getMeusServicos();
    });

    $(document).on('click', '#btnAddTipoAulaGrupo', function () {
        adicionaTipoAulaAoPt();
    });

});
