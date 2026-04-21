function alerta(titulo, msg, icon) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({ position: "center", icon, title: titulo, text: msg });
    } else {
        alert(titulo + "\n\n" + msg);
    }
}

function carregarAssuntos() {
    $.ajax({
        url: "src/controller/controllerSuporte.php",
        method: "POST",
        data: { op: 3 },
        dataType: "json"
    }).done(function(data) {
        const select = $("#suporteAssunto");
        if (!select.length) return;
        select.empty();
        select.append('<option value="">Selecione um assunto...</option>');
        data.forEach(function(a) {
            select.append(`<option value="${a.id}">${a.nome}</option>`);
        });
    }).fail(function() {
        alerta("Erro", "Erro ao carregar assuntos.", "error");
    });
}

function enviarPedidoSuporte() {
    const assunto = $("#suporteAssunto").val();
    const mensagem = $("#suporteMensagem").val().trim();
    const imagem = $("#suporteImagem")[0]?.files[0] || null;

    if (!assunto) return alerta("Atenção", "Selecione um assunto.", "warning");
    if (!mensagem) return alerta("Atenção", "Escreva uma mensagem.", "warning");

    const fd = new FormData();
    fd.append("op", 1);
    fd.append("assunto", assunto);
    fd.append("mensagem", mensagem);
    if (imagem) fd.append("imagem", imagem);

    $.ajax({
        url: "src/controller/controllerSuporte.php",
        method: "POST",
        data: fd,
        processData: false,
        contentType: false,
        dataType: "json"
    }).done(function(obj) {
        if (!obj || !obj.flag) return alerta("Erro", obj.msg || "Erro desconhecido", "error");
        alerta("Sucesso", obj.msg, "success");
        $("#suporteAssunto").val("");
        $("#suporteMensagem").val("");
        $("#suporteImagem").val("");
    }).fail(function() {
        alerta("Erro", "Erro ao enviar pedido.", "error");
    });
}

let DT_suporte = null;
function dtInitSuporte() {
    if (DT_suporte) return DT_suporte;
    DT_suporte = $("#tabelaSuporte").DataTable({
        pageLength: 10,
        ordering: true,
        retrieve: true,
        columnDefs: [{ orderable: false, targets: [-1] }]
    });
    return DT_suporte;
}

function carregarListaSuporte() {
    $.ajax({
        url: "src/controller/controllerSuporte.php",
        method: "POST",
        data: { op: 2 }
    }).done(function(html) {
        $("#tabelaSuporte tbody").html(html);
        const dt = dtInitSuporte();
        dt.clear();
        dt.rows.add($("#tabelaSuporte tbody tr")).draw();
    }).fail(function() {
        alerta("Erro", "Erro ao carregar pedidos.", "error");
    });
}

function verPedido(id) {
    $.ajax({
        url: "src/controller/controllerSuporte.php",
        method: "POST",
        data: { op: 4, id },
        dataType: "json"
    }).done(function(data) {

        if (!data) {
            alerta("Erro", "Pedido não encontrado.", "error");
            return;
        }

        $("#modalPedidoId").text(data.id);
        $("#modalPedidoUser").text(data.username || data.user_email || "Utilizador");
        $("#modalPedidoEmail").text(data.user_email || "");
        $("#modalPedidoRole").text(data.role || "—"); // ✅ NOVO

        $("#modalPedidoAssunto").text(data.assunto);
        $("#modalPedidoMensagem").text(data.mensagem);
        $("#modalPedidoEstado").text(data.estado || "");
        $("#modalPedidoData").text(data.criado_em || "");

        $("#modalVerPedido").modal("show");
    });
}


$(document).ready(function() {
    carregarAssuntos();

    $("#btnEnviarSuporte").on("click", enviarPedidoSuporte);

    if ($("#tabelaSuporte").length) {
        carregarListaSuporte();
    }
});
