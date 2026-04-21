$(function () {

    const APPLICATION_ID = "sandbox-sq0idb-8Nefsr4GYKKK5ZBuqsExyQ";
    const LOCATION_ID    = "L4E7K292JPS2A";

    let payments = null;
    let card = null;

    let cupao = null;     
    let IVA_RATE = 0.23;  

    /* =========================
       HELPERS (carrinho)
    ========================= */
    function getCarrinho(){
        return JSON.parse(localStorage.getItem("carrinho")) || [];
    }

    function setCarrinho(carrinho){
        localStorage.setItem("carrinho", JSON.stringify(carrinho));
    }

    function limparCarrinho(){
        localStorage.removeItem("carrinho");
    }

    function normalizarImg(path){
        if(!path) return "assets/images/Logo.png";

        path = String(path).trim();

        if(path.startsWith(".backoffice/")){
            path = path.replace(".backoffice/", "");
        }

        if(path.startsWith("/.backoffice/")){
            path = path.replace("/.backoffice/", "/");
        }

        return path || "assets/images/Logo.png";
    }

    function toEUR(v){
        return Number(v).toFixed(2);
    }

    function validarEmail(email){
        return email && email.includes("@") && email.includes(".");
    }

    function validarNif(nif){
        if(!nif) return true;
        return /^\d{9}$/.test(nif);
    }

    function setCamposReadonly(flag){
        $("#buyerNome").prop("readonly", flag);
        $("#buyerEmail").prop("readonly", flag);
        $("#buyerNif").prop("readonly", flag);
        $("#buyerTel").prop("readonly", flag);
    }

    function setMsgCupao(texto, ok){
        $("#couponMsg").text(texto || "");
        $("#couponMsg").css("color", ok ? "#AACA1C" : "#e74c3c");
    }

    function descontoCalculado(subtotal){
        if(!cupao) return 0;

        let val = 0;

        if(cupao.tipo === "percent"){
            val = subtotal * (Number(cupao.valor) / 100);
        }else{
            val = Number(cupao.valor);
        }

        if(val < 0) val = 0;
        if(val > subtotal) val = subtotal;

        return val;
    }

    /* =========================
       UI: RESUMO + BREAKDOWN
    ========================= */
    function emptyState(){
        $("#resumoItens").html('<p style="opacity:.65;margin:0;">Carrinho vazio.</p>');

        $("#subtotalCheckout").text("0.00");
        $("#descontoCheckout").text("0.00");
        $("#ivaCheckout").text("0.00");
        $("#totalCheckout").text("0.00");

        $("#btnPagar").prop("disabled", true);
        return { carrinho: [], subtotal: 0, desconto: 0, iva: 0, total: 0 };
    }

    function renderResumo(){
        let carrinho = getCarrinho();

        if(carrinho.length === 0){
            return emptyState();
        }

        let html = "";
        let subtotal = 0;

        carrinho.forEach(function(it){

            let qtd = parseInt(it.qtd) || 0;
            let preco = parseFloat(it.preco) || 0;

            let sub = preco * qtd;
            subtotal += sub;

            let img = normalizarImg(it.imagem);

            html += `
                <div class="resumo-item">
                    <img class="resumo-img" src="${img}" onerror="this.src='assets/images/Logo.png'" alt="${it.nome}">
                    <div class="resumo-mid">
                        <div class="resumo-nome">${it.nome}</div>
                        <div class="resumo-qtd">x${qtd}</div>
                    </div>
                    <div class="resumo-preco">${toEUR(sub)} €</div>
                    <button class="btn-remove" data-id="${it.id}" title="Remover">✕</button>
                </div>
            `;
        });

        $("#resumoItens").html(html);

        let desconto = descontoCalculado(subtotal);
        let iva = (subtotal - desconto) * IVA_RATE;
        let total = (subtotal - desconto) + iva;

        $("#subtotalCheckout").text(toEUR(subtotal));
        $("#descontoCheckout").text(toEUR(desconto));
        $("#ivaCheckout").text(toEUR(iva));
        $("#totalCheckout").text(toEUR(total));

        $("#btnPagar").prop("disabled", false);

        return { carrinho: carrinho, subtotal: subtotal, desconto: desconto, iva: iva, total: total };
    }

    /* =========================
       SQUARE INIT (sem async wrapper)
    ========================= */
    function initSquare(){
        if(!window.Square){
            $("#card-errors").text("Square SDK não carregou.");
            return $.Deferred().reject().promise();
        }

        try{
            payments = window.Square.payments(APPLICATION_ID, LOCATION_ID);

            return payments.card()
                .then(function(cardInstance){
                    card = cardInstance;
                    return card.attach("#card-container");
                })
                .then(function(){
                    return true;
                })
                .catch(function(e){
                    console.error(e);
                    $("#card-errors").text(e?.message || "Erro ao iniciar o formulário do cartão.");
                    return $.Deferred().reject().promise();
                });

        }catch(e){
            console.error(e);
            $("#card-errors").text(e?.message || "Erro ao iniciar o formulário do cartão.");
            return $.Deferred().reject().promise();
        }
    }

    /* =========================
       CUPÃO (op=11) - opcional
    ========================= */
    function aplicarCupao(){
        let codigo = ($("#couponCode").val() || "").trim();

        if(!codigo){
            cupao = null;
            setMsgCupao("Sem cupão aplicado.", false);
            renderResumo();
            return;
        }

        $("#btnAplicarCupao").prop("disabled", true);

        let dados = new FormData();
        dados.append("op", 11);
        dados.append("codigo", codigo);

        $.ajax({
            url: "src/controller/controllerProduto.php",
            method: "POST",
            data: dados,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(obj){

            if(obj && obj.flag){
                cupao = { codigo: codigo, tipo: obj.tipo, valor: obj.valor };
                setMsgCupao("Cupão aplicado: " + codigo, true);
            }else{
                cupao = null;
                setMsgCupao((obj && obj.msg) ? obj.msg : "Cupão inválido.", false);
            }

            renderResumo();
        })
        .fail(function(){
            cupao = null;
            setMsgCupao("Não foi possível validar o cupão (backend).", false);
            renderResumo();
        })
        .always(function(){
            $("#btnAplicarCupao").prop("disabled", false);
        });
    }

    /* =========================
       PAGAMENTO (op=9)
    ========================= */
    function pagar(){
        $("#card-errors").text("");

        let estado = renderResumo();
        if(estado.carrinho.length === 0){
            return;
        }

        if(!card){
            $("#card-errors").text("Formulário do cartão não está pronto. Recarrega a página.");
            return;
        }

        let nome  = ($("#buyerNome").val() || "").trim();
        let email = ($("#buyerEmail").val() || "").trim();
        let nif   = ($("#buyerNif").val() || "").trim();
        let tel   = ($("#buyerTel").val() || "").trim();

        if(!nome){
            $("#card-errors").text("Indica o nome do comprador.");
            return;
        }

        if(!validarEmail(email)){
            $("#card-errors").text("Indica um email válido.");
            return;
        }

        if(!validarNif(nif)){
            $("#card-errors").text("NIF inválido (9 dígitos).");
            return;
        }

        if(!$("#chkTerms").is(":checked")){
            $("#card-errors").text("Tens de aceitar os Termos e Condições.");
            return;
        }

        $("#btnPagar").prop("disabled", true).text("A processar...");

        card.tokenize()
            .then(function(result){

                if(result.status !== "OK"){
                    console.log(result);
                    $("#card-errors").text("Dados do cartão inválidos (sandbox).");
                    $("#btnPagar").prop("disabled", false).text("Pagar agora");
                    return;
                }

                let dados = new FormData();
                dados.append("op", 9);
                dados.append("nonce", result.token);

                dados.append("items", JSON.stringify(estado.carrinho));

                dados.append("subtotal", toEUR(estado.subtotal));
                dados.append("desconto", toEUR(estado.desconto));
                dados.append("iva", toEUR(estado.iva));
                dados.append("amount", toEUR(estado.total));

                dados.append("nome", nome);
                dados.append("email", email);
                dados.append("nif", nif);
                dados.append("telefone", tel);

                dados.append("alt_dados", $("#chkAltDados").is(":checked") ? 1 : 0);
                dados.append("termos", 1);

                dados.append("cupao_codigo", cupao ? cupao.codigo : "");

                return $.ajax({
                    url: "src/controller/controllerProduto.php",
                    method: "POST",
                    data: dados,
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false
                })
                .done(function(obj){

                    if(obj && obj.flag){
                        Swal.fire({
                            icon: "success",
                            title: "Pagamento concluído",
                            text: "Compra registada com sucesso."
                        }).then(function(){
                            limparCarrinho();
                            window.location.href = "marketplace_cliente.php?paid=1";
                        });

                    }else{
                        Swal.fire({ icon:"error", title:"Pagamento", text: (obj && obj.msg) ? obj.msg : "Erro no pagamento." });
                        $("#btnPagar").prop("disabled", false).text("Pagar agora");
                    }
                })
                .fail(function(){
                    Swal.fire({ icon:"error", title:"Servidor", text:"Erro na ligação." });
                    $("#btnPagar").prop("disabled", false).text("Pagar agora");
                });

            })
            .catch(function(e){
                console.error(e);
                $("#card-errors").text(e?.message || "Erro ao tokenizar cartão.");
                $("#btnPagar").prop("disabled", false).text("Pagar agora");
            });
    }

    function carregarDadosComprador(){

        let dados = new FormData();
        dados.append("op", 12);

        $.ajax({
            url: "src/controller/controllerProduto.php",
            method: "POST",
            data: dados,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(obj){

            if(obj && obj.flag){

                $("#buyerNome").val(obj.nome);
                $("#buyerEmail").val(obj.email);
                $("#buyerNif").val(obj.nif);
                $("#buyerTel").val(obj.tel);

            }

        })
        .fail(function(){
            console.log("Erro a carregar dados do comprador.");
        });
    }

    /* =========================
       EVENTS
    ========================= */
    $(document).on("click", ".btn-remove", function(){
        let id = parseInt($(this).data("id"));

        let carrinho = getCarrinho();
        carrinho = carrinho.filter(function(it){
            return parseInt(it.id) !== id;
        });

        setCarrinho(carrinho);
        renderResumo();
    });

    $("#btn-limpar-carrinho").on("click", function(){
        limparCarrinho();
        renderResumo();
    });

    $("#chkAltDados").on("change", function(){
        setCamposReadonly(!$("#chkAltDados").is(":checked"));
    });

    $("#btnAplicarCupao").on("click", aplicarCupao);

    $("#btnPagar").on("click", pagar);

    /* =========================
       BOOT
    ========================= */
    setCamposReadonly(true);
    carregarDadosComprador();
    renderResumo();

    $("#btnPagar").prop("disabled", true);

    initSquare()
        .then(function(){
            if(getCarrinho().length > 0){
                $("#btnPagar").prop("disabled", false);
            }
        })
        .catch(function(){
            $("#btnPagar").prop("disabled", true);
        });

});
