$(document).ready(function () {

    $("#id_cliente").select2({ width: "100%" })
    $("#id_consulta").select2({ width: "100%" })

    carregarClientes()
    carregarVendas()

    if (!$("#data_venda").val()) {
        $("#data_venda").val(new Date().toISOString().slice(0, 10))
    }

    $(document).on("change", "#id_cliente", function () {
        carregarSessoes($(this).val())
    })

    $(document).on("change", "#id_consulta", function () {
        atualizarValor()
    })
})

function registarVenda() {

    let dados = new FormData()
    dados.append("op", "guardar")
    dados.append("id_cliente", $("#id_cliente").val())
    dados.append("id_consulta", $("#id_consulta").val())
    dados.append("valor", $("#valor").val())
    dados.append("data_venda", $("#data_venda").val())
    dados.append("metodo_pagamento", $("#metodo_pagamento").val())
    dados.append("id_estado", $("#id_estado").val())

    $.ajax({
        url: "src/controller/controllerVendas_psicologo.php",
        method: "POST",
        data: dados,
        dataType: "json",
        contentType: false,
        processData: false
    }).done(r => {
        if (r.flag) {
            Swal.fire("Vendas", r.msg, "success")
            limparForm()
            carregarVendas()
        } else {
            Swal.fire("Vendas", r.msg, "error")
        }
    })
}

function carregarClientes() {

    $.post(
        "src/controller/controllerVendas_psicologo.php",
        { op: "clientes" },
        html => $("#id_cliente").html(html).trigger("change")
    )
}

function carregarSessoes(idCliente) {

    if (!idCliente) {
        $("#id_consulta").html('<option value="">Seleciona...</option>')
        $("#valor").val("0.00")
        return
    }

    $.post(
        "src/controller/controllerVendas_psicologo.php",
        { op: "sessoes", id_cliente: idCliente },
        html => $("#id_consulta").html(html).trigger("change")
    )
}

function atualizarValor() {

    let preco = $("#id_consulta option:selected").data("preco")
    if (!preco) preco = 0

    $("#valor").val(parseFloat(preco).toFixed(2))
}

function carregarVendas() {

    $.post(
        "src/controller/controllerVendas_psicologo.php",
        { op: "listar" },
        html => $("#listaVendas").html(html)
    )
}

function removerVenda(id) {

    Swal.fire({
        title: "Remover venda?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim",
        cancelButtonText: "Não"
    }).then(r => {

        if (!r.isConfirmed) return

        $.post(
            "src/controller/controllerVendas_psicologo.php",
            { op: "remover", id },
            res => {
                if (res.flag) {
                    Swal.fire("Vendas", res.msg, "success")
                    carregarVendas()
                } else {
                    Swal.fire("Vendas", res.msg, "error")
                }
            },
            "json"
        )
    })
}

function limparForm() {

    $("#id_cliente").val("").trigger("change")
    $("#id_consulta").html('<option value="">Seleciona...</option>').trigger("change")
    $("#valor").val("0.00")
    $("#data_venda").val(new Date().toISOString().slice(0, 10))
    $("#metodo_pagamento").val("cartao")
    $("#id_estado").val("13")
}
