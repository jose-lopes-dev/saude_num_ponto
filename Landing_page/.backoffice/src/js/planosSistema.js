let vendaAtual = 0;
let precoAtual = 0;

(function () {

    const applicationId = "sandbox-sq0idb-8Nefsr4GYKKK5ZBuqsExyQ";
    const locationId    = "L4E7K292JPS2A";

    let payments = null;
    let card = null;
    let squareReady = false;

    window.initSquareCard = async function (containerSelector) {
        try {
            if (!window.Square) {
                console.log("Square SDK não carregou.");
                squareReady = false;
                return false;
            }

            payments = window.Square.payments(applicationId, locationId);
            card = await payments.card();
            await card.attach(containerSelector);

            squareReady = true;
            return true;

        } catch (e) {
            console.error("Erro initSquareCard:", e);
            squareReady = false;
            return false;
        }
    };

    window.pagarSquare = async function (amount) {
        if (!squareReady || !card) throw new Error("Formulário do cartão não está pronto.");

        const result = await card.tokenize();

        if (!result || result.status !== "OK") {
            console.log("Tokenize result:", result);
            throw new Error("Dados do cartão inválidos.");
        }

        let dados = new FormData();
        dados.append("op", 3);
        dados.append("nonce", result.token);
        dados.append("amount", parseFloat(amount).toFixed(2));

        return $.ajax({
            url: "src/controller/controllerPlanoSistema.php",
            method: "POST",
            data: dados,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false
        });
    };

})();


$(document).ready(function () {

    $(document).on("click", ".btnComprarPlano", function (e) {
        e.preventDefault();

        let idServico = parseInt($(this).data("idservico") || 0);

        if (!idServico || idServico <= 0) {
            Swal.fire({ title: "Erro", text: "Plano inválido.", icon: "error", heightAuto: false });
            return;
        }

        iniciarCompra(idServico);
    });

    $("#modalPagamentoPlano").on("shown.bs.modal", async function () {

        if (window.__square_init_ok__) return;

        const ok = await initSquareCard("#card-container");
        window.__square_init_ok__ = ok ? true : false;

        if (!ok) $("#card-errors").text("Erro ao carregar o formulário de pagamento.");
    });

    $(document).on("click", "#btnConfirmarPagamento", async function () {

        if (!vendaAtual || vendaAtual <= 0 || precoAtual <= 0) {
            Swal.fire({ title: "Erro", text: "Nenhum plano pago selecionado.", icon: "error", heightAuto: false });
            return;
        }

        $("#btnConfirmarPagamento").prop("disabled", true);
        $("#card-errors").text("");

        try {
            const res = await pagarSquare(precoAtual);

            if (!res || !res.flag) {
                Swal.fire({ title: "Pagamento falhou", text: (res && res.msg) ? res.msg : "Erro no pagamento.", icon: "error", heightAuto: false });
                return;
            }

            await confirmarPagamento(vendaAtual, res.square_payment_id);

        } catch (e) {
            console.error(e);
            Swal.fire({ title: "Pagamento falhou", text: e?.message || "Erro no pagamento.", icon: "error", heightAuto: false });
        } finally {
            $("#btnConfirmarPagamento").prop("disabled", false);
        }

    });

});

function iniciarCompra(idServico) {

    let dados = new FormData();
    dados.append("op", 1);
    dados.append("id_servico", idServico);

    $.ajax({
        url: "src/controller/controllerPlanoSistema.php",
        method: "POST",
        data: dados,
        contentType: false,
        processData: false
    })
    .done(function (msg) {

        let obj = parseJsonSafe(msg);

        if (!obj || obj.flag !== true) {
            Swal.fire({ title: "Erro", text: (obj && obj.msg) ? obj.msg : "Erro ao iniciar compra.", icon: "error", heightAuto: false });
            return;
        }

        let preco = (obj.plano && obj.plano.preco != null) ? parseFloat(obj.plano.preco) : 0;
        let desc  = (obj.plano && obj.plano.descricao) ? obj.plano.descricao : "Plano";

        if (preco <= 0) {
            Swal.fire({ title: "Sucesso", text: obj.msg || "Plano ativado (FREE).", icon: "success", heightAuto: false })
                .then(() => window.location.reload());
            return;
        }

        vendaAtual = parseInt(obj.id_venda || 0);
        precoAtual = preco;

        $("#resumoPlanoPagamento").html(
            `Vais comprar: <b>${desc}</b><br>Valor: <b class="text-success">${preco.toFixed(2)}€</b> / mês`
        );

        $("#modalPagamentoPlano").modal("show");

    })
    .fail(function () {
        Swal.fire({ title: "Erro", text: "Falha na comunicação com o servidor.", icon: "error", heightAuto: false });
    });

}

function confirmarPagamento(idVenda, squarePaymentId) {

    let dados = new FormData();
    dados.append("op", 2);
    dados.append("id_venda", idVenda);
    dados.append("square_payment_id", squarePaymentId);

    $.ajax({
        url: "src/controller/controllerPlanoSistema.php",
        method: "POST",
        data: dados,
        contentType: false,
        processData: false
    })
    .done(function (msg) {

        let obj = parseJsonSafe(msg);

        if (!obj || obj.flag !== true) {
            Swal.fire({ title: "Erro", text: (obj && obj.msg) ? obj.msg : "Erro ao confirmar pagamento.", icon: "error", heightAuto: false });
            return;
        }

        $("#modalPagamentoPlano").modal("hide");

        Swal.fire("Sucesso", obj.msg || "Plano ativado!", "success")
        .then(()=> window.location.href = "dashboard_cliente.php?plano=1");

    })
    .fail(function () {
        Swal.fire({ title: "Erro", text: "Falha na comunicação com o servidor.", icon: "error", heightAuto: false });
    });

}

function carregarPlanoAtual(){
    let dados = new FormData();
    dados.append("op", 4);

    $.ajax({
        url: "src/controller/controllerPlanoSistema.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(obj){

        if(!obj || !obj.flag){
            $("#planoAtualInfo").html(`<span class="text-muted">Sem plano ativo.</span>`);
            return;
        }

        if(!obj.plano){
            $("#planoAtualInfo").html(`<span class="text-muted">Sem plano ativo.</span>`);
            return;
        }

        const p = obj.plano;
        const id = parseInt(p.id_servico || 0);

        $("#planoAtualInfo").html(
            `<b>Plano Atual:</b> ${p.descricao} <span class="text-muted">(até ${p.data_fim})</span>`
        );

        if (obj.restantes) {
            $("#planoAtualInfo").append(
                `<br><small class="text-muted">Grátis este mês: Nutri <b>${obj.restantes.nutri}</b> | PT <b>${obj.restantes.pt}</b></small>`
            );
        }

        $(`.btnComprarPlano[data-idservico='${id}']`)
            .prop("disabled", true)
            .text("ATIVO");
    });
}

$(function(){ carregarPlanoAtual(); });

function parseJsonSafe(msg) {
    try {
        if (typeof msg === "object") return msg;
        return JSON.parse(msg);
    } catch (e) {
        console.log("Resposta não-JSON:", msg);
        return null;
    }
}
