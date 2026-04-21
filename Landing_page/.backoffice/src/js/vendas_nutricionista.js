$(document).ready(function () {

    $("#id_cliente").select2({ width: "100%" })
    $("#id_consulta").select2({ width: "100%" })

    getClientesVenda()
    getTabelaVendas()

    if (!$("#data_venda").val()) {
        const hoje = new Date().toISOString().slice(0, 10)
        $("#data_venda").val(hoje)
    }

    $(document).on("change", "#id_cliente", function () {
        getConsultasVenda($(this).val())
    })

    $(document).on("change", "#id_consulta", function () {
        atualizaPrecoConsulta()
    })
})

function registarVenda() {

    let dados = new FormData()
    dados.append("op", 3)
    dados.append("id_cliente", $("#id_cliente").val())
    dados.append("id_consulta", $("#id_consulta").val())
    dados.append("valor", $("#valor").val())
    dados.append("data_venda", $("#data_venda").val())
    dados.append("metodo_pagamento", $("#metodo_pagamento").val())
    dados.append("id_estado", $("#id_estado").val())

    $.ajax({
        url: "src/controller/controllerVendas_nutricionista.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    }).done(function (obj) {

        if (obj.flag) {
            Swal.fire({
                icon: "success",
                title: "Vendas",
                text: obj.msg
            })
            limparFormVenda()
            getTabelaVendas()
        } else {
            Swal.fire({
                icon: "error",
                title: "Vendas",
                text: obj.msg
            })
        }
    })
}

function getClientesVenda() {

    let dados = new FormData()
    dados.append("op", 1)

    $.ajax({
        url: "src/controller/controllerVendas_nutricionista.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    }).done(function (msg) {
        $("#id_cliente").html(msg).trigger("change")
    })
}

function getConsultasVenda(idCliente) {

    if (!idCliente) {
        $("#id_consulta").html('<option value="">Seleciona...</option>')
        $("#valor").val("0.00")
        return
    }

    let dados = new FormData()
    dados.append("op", 6)
    dados.append("id_cliente", idCliente)

    $.ajax({
        url: "src/controller/controllerVendas_nutricionista.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    }).done(function (msg) {
        $("#id_consulta").html(msg).trigger("change")
    })
}

function atualizaPrecoConsulta() {

    let preco = $("#id_consulta option:selected").data("preco")
    if (!preco || isNaN(preco)) preco = 0

    $("#valor").val(parseFloat(preco).toFixed(2))
}

function getTabelaVendas() {

    let dados = new FormData()
    dados.append("op", 4)

    $.ajax({
        url: "src/controller/controllerVendas_nutricionista.php",
        method: "POST",
        data: dados,
        cache: false,
        contentType: false,
        processData: false
    }).done(function (msg) {
        $("#listaVendas").html(msg)
    })
}

function removerVenda(id) {

    Swal.fire({
        title: "Remover venda?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim",
        cancelButtonText: "Não"
    }).then((result) => {

        if (!result.isConfirmed) return

        let dados = new FormData()
        dados.append("op", 5)
        dados.append("id", id)

        $.ajax({
            url: "src/controller/controllerVendas_nutricionista.php",
            method: "POST",
            data: dados,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false
        }).done(function (obj) {

            if (obj.flag) {
                Swal.fire({
                    icon: "success",
                    title: "Vendas",
                    text: obj.msg
                })
                getTabelaVendas()
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Vendas",
                    text: obj.msg
                })
            }
        })
    })
}

function limparFormVenda() {

    $("#id_cliente").val("").trigger("change")
    $("#id_consulta").html('<option value="">Seleciona...</option>').trigger("change")
    $("#valor").val("0.00")

    const hoje = new Date().toISOString().slice(0, 10)
    $("#data_venda").val(hoje)

    $("#metodo_pagamento").val("cartao")
    $("#id_estado").val("13")
}
