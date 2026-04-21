$(document).ready(function () {
  getClientesVenda();
  getServicosVenda();
  getTabelaVendas();

  $('#metodo_pagamento').select2({ placeholder: "Seleciona método", allowClear: false, width: "100%" });
  $('#id_estado').select2({ placeholder: "Seleciona estado", allowClear: false, width: "100%" });

  if (!$("#data_venda").val()) {
    const hoje = new Date().toISOString().slice(0, 10);
    $("#data_venda").val(hoje);
  }

  $(document).on("change", "#id_servico", function () {
    atualizaPrecoServico();
  });
});

function registaVenda() {
  let dados = new FormData();
  dados.append("op", 3);
  dados.append("idPt", $("#idPt").val()); 
  dados.append("id_cliente", $("#id_cliente").val());
  dados.append("id_servico", $("#id_servico").val());
  dados.append("valor", $("#valor").val());
  dados.append("data_venda", $("#data_venda").val());
  dados.append("metodo_pagamento", $("#metodo_pagamento").val());
  dados.append("id_estado", $("#id_estado").val());

  $.ajax({
    url: "src/controller/controllerVendas_pt.php",
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false
  })
  .done(function (msg) {
    let obj = JSON.parse(msg);

    if (obj.flag) {
      alerta("Vendas", obj.msg, "success");
      limparFormVenda();
      getTabelaVendas();
    } else {
      alerta("Vendas", obj.msg, "error");
    }
  })
  .fail(function (jqXHR, textStatus) {
    alert("Request failed: " + textStatus);
  });
}

function getClientesVenda() {
  let dados = new FormData();
  dados.append("op", 1); 
  dados.append("idPt", $("#idPt").val());

  $.ajax({
    url: "src/controller/controllerVendas_pt.php",
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false
  })
  .done(function (msg) {
    $("#id_cliente").html(msg);
    $("#id_cliente").select2({ width: "100%" });
  });
}

function getServicosVenda() {
  let dados = new FormData();
  dados.append("op", 2); 
  dados.append("idPt", $("#idPt").val());

  $.ajax({
    url: "src/controller/controllerVendas_pt.php",
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false
  })
  .done(function (msg) {
    $("#id_servico").html(msg);
    $("#id_servico").select2({ width: "100%" });
    atualizaPrecoServico();
  });
}

function atualizaPrecoServico() {
  let preco = $("#id_servico option:selected").data("preco");
  if (preco === undefined || preco === null || preco === "") preco = 0;
  $("#valor").val(parseFloat(preco).toFixed(2));
}

function getTabelaVendas() {
  if ($.fn.DataTable.isDataTable("#tableVendas")) {
    $("#tableVendas").DataTable().destroy();
  }

  let dados = new FormData();
  dados.append("op", 4);
  dados.append("idPt", $("#idPt").val());

  $.ajax({
    url: "src/controller/controllerVendas_pt.php",
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false
  })
  .done(function (msg) {
    $("#listaVendas").html(msg);
    $("#tableVendas").DataTable();
  });
}

function removerVenda(id) {
  let dados = new FormData();
  dados.append("op", 5);
  dados.append("id", id);
  dados.append("idPt", $("#idPt").val());

  $.ajax({
    url: "src/controller/controllerVendas_pt.php",
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false
  })
  .done(function (msg) {
    let obj = JSON.parse(msg);

    if (obj.flag) {
      alerta("Vendas", obj.msg, "success");
      getTabelaVendas();
    } else {
      alerta("Vendas", obj.msg, "error");
    }
  });
}

function limparFormVenda() {
  $("#id_cliente").val("").trigger("change");
  $("#id_servico").val("").trigger("change");
  $("#valor").val("0.00");

  const hoje = new Date().toISOString().slice(0, 10);
  $("#data_venda").val(hoje);

  $("#metodo_pagamento").val("cartao");
  $("#id_estado").val("12");
}