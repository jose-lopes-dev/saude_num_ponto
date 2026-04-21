<?php require_once __DIR__ . '/src/auth/auth_cliente.php'; ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/Logo.png ">
    <!-- CSS do teu tema -->
    <link rel="stylesheet" href="src/css/bootstrap.css">
    <link rel="stylesheet" href="src/css/style.css">
    <link rel="stylesheet" href="src/css/checkout_cliente.css">
    <link rel="stylesheet" href="src/css/global.css">
    <link rel="stylesheet" href="src/css/sweetalertFinalVersion.css">

</head>

    <body class="checkout-page">

        <header class="checkout-topbar">
            <div class="checkout-topbar-inner">
                <h1 class="checkout-title">Checkout</h1>

                <a href="marketplace_cliente.php" class="btn-ghost">
                    <i class="ri-shopping-cart-line"></i>Voltar ao marketplace
                </a>
            </div>
        </header>

        <div class="checkout-wrap">
            <div class="checkout-grid">

                <div class="card-dark">
                    <div class="card-head">
                        <h5 class="mb-0 card-title">Pagamento 💳</h5>
                        <small class="text-muted card-subtitle-1">Introduz os dados do cartão para concluir a compra.</small>
                    </div>

                    <div class="card-body">
                        <!-- DADOS DO COMPRADOR (prefill + opcional alterar) -->
                        <div class="buyer-box mb-3">
                            <div class="buyer-head">
                                <h6 class="buyer-title mb-0">Dados do comprador</h6>

                                <label class="buyer-alt mb-0">
                                    <input id="chkAltDados" type="checkbox">
                                    <span>Usar outros dados nesta compra</span>
                                </label>
                            </div>

                            <div class="buyer-grid">
                                <div class="buyer-field">
                                    <label>Nome</label>
                                    <input id="buyerNome" type="text" value="" readonly>
                                </div>

                                <div class="buyer-field">
                                    <label>Email</label>
                                    <input id="buyerEmail" type="email" value="" readonly>
                                </div>

                                <div class="buyer-field">
                                    <label>NIF (opcional)</label>
                                    <input id="buyerNif" type="text" maxlength="9" value="" readonly>
                                </div>

                                <div class="buyer-field">
                                    <label>Telefone (opcional)</label>
                                    <input id="buyerTel" type="text" value="" readonly>
                                </div>
                            </div>

                            <div class="buyer-terms">
                                <label class="terms-line">
                                    <input id="chkTerms" type="checkbox">
                                    <span>Aceito os Termos e Condições e a Política de Privacidade</span>
                                </label>
                            </div>

                            <div class="buyer-coupon">
                                <div class="buyer-head">
                                    <h6 class="buyer-title mb-0 mt-2">Código promocional</h6>
                                </div>
                                <div class="coupon-row mt-2">
                                    <input id="couponCode" type="text" placeholder="EX: AIO10">
                                    <button id="btnAplicarCupao" type="button" class="btn-coupon">Aplicar</button>
                                </div>
                                <div id="couponMsg" class="coupon-msg"></div>
                            </div>
                        </div>
                        <div class="buyer-box mb-3 mt-3">
                            <div class="buyer-head">
                                <div class="buyer-title">Cartão</div>
                            </div>

                            <div id="card-container"></div>
                            <div id="card-errors"></div>
                        </div>

                        <button type="button" class="btn-pay" id="btnPagar">
                            <i class="ri-bank-card-line"></i> Pagar agora
                        </button>
                    </div>
                </div>

                <div class="card-dark">
                    <div class="card-head">
                        <h6 class="mb-0 card-title">Resumo 🛒</h6>
                        <div class="resumo-actions">
                            <button id="btn-limpar-carrinho" class="btn-clear">Limpar carrinho</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="resumoItens" class="small"></div>
                        <div class="total-row total-row-mini">
                            <div class="total-label-mini">Subtotal</div>
                            <div class="total-value-mini"><span id="subtotalCheckout">0.00</span> €</div>
                        </div>

                        <div class="total-row total-row-mini">
                            <div class="total-label-mini">Desconto</div>
                            <div class="total-value-mini">- <span id="descontoCheckout">0.00</span> €</div>
                        </div>

                        <div class="total-row total-row-mini">
                            <div class="total-label-mini">IVA</div>
                            <div class="total-value-mini"><span id="ivaCheckout">0.00</span> €</div>
                        </div>

                        <div class="total-row total-row-final">
                            <div class="total-label">Total</div>
                            <div class="total-value"><span id="totalCheckout">0.00</span> €</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script>
            const USER_SESSION = {
                id: <?php echo json_encode($_SESSION['id']); ?>,
                tipo: <?php echo json_encode($_SESSION['tipo']); ?>,
                cliente_id: <?php echo json_encode($_SESSION['cliente_id']); ?>
                };
        </script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
        <script src="src/js/lib/bootstrap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>   
        <script src="src/js/checkout_cliente.js"></script>
    </body>
</html>
