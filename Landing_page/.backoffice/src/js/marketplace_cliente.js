const imgDefault = 'assets/images/Logo.png';

function normalizarImg(path){
  if(!path) return imgDefault;

  path = path.trim();

  if(path.startsWith('.backoffice/')){
    path = path.replace('.backoffice/', '');
  }

  if(path.startsWith('/.backoffice/')){
    path = path.replace('/.backoffice/', '/');
  }

  // EXTRA: este é o caso que te está a dar ".backoffice/backoffice/..."
  if(path.startsWith('backoffice/')){
    path = path.replace('backoffice/', '');
  }

  return path || imgDefault;
}

function getCart() {
  return JSON.parse(localStorage.getItem("carrinho") || "[]");
}

function setCart(cart) {
  localStorage.setItem("carrinho", JSON.stringify(cart));
}

/* ---------- UI ---------- */
function renderCartCount() {
  const carrinho = getCart();
  const total = carrinho.reduce((acc, it) => acc + (parseInt(it.qtd) || 0), 0);
  $('#cartCount').text(total);
}

function renderSkeletons() {
  let sk = '';
  for (let i = 0; i < 8; i++) {
    sk += '<div class="col-12 col-sm-6 col-lg-4 col-xxl-3"><div class="mp-skeleton"></div></div>';
  }
  $('#listaProdutos').html(sk);
}

/* ---------- Data (AJAX) ---------- */
function carregarProdutos() {
  let dados = new FormData();
  dados.append('op', 8);

  $.ajax({
    url: 'src/controller/controllerProduto.php',
    method: 'POST',
    data: dados,
    contentType: false,
    processData: false,
    dataType: 'html'
  })
    .done(function (resp) {
      $('#listaProdutos').html(resp);
      sortGrid($('#sortProdutos').val());
    })
    .fail(function () {
      $('#listaProdutos').html('<div class="alert alert-danger">Erro ao carregar produtos</div>');
    });
}

function carregarParceiros() {
  let dados = new FormData();
  dados.append('op', 10);

  $.ajax({
    url: 'src/controller/controllerProduto.php',
    method: 'POST',
    data: dados,
    contentType: false,
    processData: false,
    dataType: 'html'
  })
    .done(function (resp) {
      $('#listaParceiros').html(resp);
    })
    .fail(function () {
      $('#listaParceiros').html('');
    });
}

/* ---------- Produtos: sort + filter ---------- */
function sortGrid(mode) {
  const $grid = $('#listaProdutos');
  const items = $grid.children().get();

  const getCard = (col) => $(col).find('.produto-card').first();

  items.sort((a, b) => {
    const $ca = getCard(a);
    const $cb = getCard(b);

    const nomeA = ($ca.data('nome') || '').toString().toLowerCase();
    const nomeB = ($cb.data('nome') || '').toString().toLowerCase();
    const precoA = parseFloat($ca.data('preco') || 0);
    const precoB = parseFloat($cb.data('preco') || 0);

    switch (mode) {
      case 'nome_asc': return nomeA.localeCompare(nomeB);
      case 'preco_asc': return precoA - precoB;
      case 'preco_desc': return precoB - precoA;
      case 'recent':
      default: return 0;
    }
  });

  $grid.append(items);
}

function bindSearch() {
  $('#searchProdutos').on('input', function () {
    const q = $(this).val().toLowerCase();

    $('#listaProdutos .produto-card').each(function () {
      const nome = ($(this).data('nome') || '').toString().toLowerCase();
      const desc = $(this).find('.mp-desc, .card-text').text().toLowerCase();
      $(this).parent().toggle(nome.includes(q) || desc.includes(q));
    });
  });
}

function bindSort() {
  $(document).on('click', '#sortDropdown .dropdown-item', function(){
    const v = $(this).data('value');
    const label = $(this).text().trim();

    $('#sortProdutos').val(v).trigger('change');
    $('#sortDropdown .mp-dd-btn').html(`${label} <i class="ri-arrow-down-s-line"></i>`);
  });
}

/* ---------- Carrinho ---------- */
function bindAddToCart() {
  $(document).on("click", ".btn-add-cart", function () {
    let id = parseInt($(this).data("id"));
    let nome = $(this).data("nome");
    let preco = parseFloat($(this).data("preco"));
    let imagem = $(this).data("imagem") || "";

    imagem = normalizarImg(imagem);

    let carrinho = getCart();

    let idx = carrinho.findIndex(x => parseInt(x.id) === id);
    if (idx >= 0) {
      carrinho[idx].qtd = (parseInt(carrinho[idx].qtd) || 0) + 1;
      if (!carrinho[idx].imagem && imagem) carrinho[idx].imagem = imagem;
    } else {
      carrinho.push({ id, nome, preco, qtd: 1, imagem });
    }

    setCart(carrinho);
    renderCartCount();

    Swal.fire({
      icon: 'success',
      title: 'Adicionado!',
      text: 'Produto adicionado ao carrinho'
    });
  });

  if (localStorage.getItem('mp_reset_cart') === '1') localStorage.removeItem("carrinho");
}

function formatEUR(v){
  try{
    return new Intl.NumberFormat('pt-PT', { style:'currency', currency:'EUR' }).format(v);
  }catch(e){
    return (v.toFixed(2) + ' €');
  }
}

function renderCartDrawer(){
  const cart = getCart();
  const $box = $('#cartItems');

  if(cart.length === 0){
    $box.html('<p style="color:rgba(251,251,250,.7);margin:10px 0;">Carrinho vazio.</p>');
    $('#cartTotal').text('0,00 €');
    return;
  }

  let total = 0;
  let html = '';

  cart.forEach((it) => {
    const qtd = parseInt(it.qtd) || 0;
    const preco = parseFloat(it.preco) || 0;
    total += qtd * preco;

    const img = it.imagem ? `<img src="${normalizarImg(it.imagem)}" alt="">` : '';

    html += `
      <div class="mp-cart-item" data-id="${it.id}">
        <div class="mp-cart-thumb">${img}</div>
        <div class="mp-cart-info">
          <p class="mp-cart-name">${it.nome || 'Produto'}</p>
          <p class="mp-cart-meta">${formatEUR(preco)} · ${qtd} un.</p>
          <div class="mp-cart-actions">
            <button class="mp-qty-btn btn-qty-minus" type="button">−</button>
            <button class="mp-qty-btn btn-qty-plus" type="button">+</button>
          </div>
        </div>
      </div>
    `;
  });

  $box.html(html);
  $('#cartTotal').text(formatEUR(total));
}

function openCart(){
  renderCartDrawer();
  $('#cartDrawer').addClass('is-open').attr('aria-hidden','false');
}

function closeCart(){
  $('#cartDrawer').removeClass('is-open').attr('aria-hidden','true');
}

/* ---------- Hero Slider ---------- */
function initHeroSlider() {
  const $slides = $('.mp-hero-slide');
  const $dots = $('.mp-hero-dots .dot');
  if ($slides.length <= 1) return;

  let i = 0;
  let timer = null;

  const go = (idx) => {
    i = (idx + $slides.length) % $slides.length;
    $slides.removeClass('is-active').eq(i).addClass('is-active');
    $dots.removeClass('is-active').eq(i).addClass('is-active');
  };

  const stop = () => { if (timer) clearInterval(timer); };

  const start = () => {
    stop();
    timer = setInterval(() => go(i + 1), 5500);
  };

  $('.mp-hero-nav.prev').on('click', () => { go(i - 1); start(); });
  $('.mp-hero-nav.next').on('click', () => { go(i + 1); start(); });

  $dots.each(function (idx) {
    $(this).on('click', () => { go(idx); start(); });
  });

  $('.mp-hero').on('mouseenter', stop).on('mouseleave', start);

  start();
}

function bindCategories() {
  $('.mp-cat').on('click', function (e) {
    e.preventDefault();

    const cat = ($(this).data('cat') || '').toString().toLowerCase();
    $('#searchProdutos').val(cat).trigger('input');
    document.querySelector('#produtos')?.scrollIntoView({ behavior: 'smooth' });
  });
}

/* ---------- Shop Mode ---------- */
function setShopMode(on) {
  if (on) {
    $('body').addClass('mp-shopmode');
    localStorage.setItem('mp_shopmode', '1');
  } else {
    $('body').removeClass('mp-shopmode');
    localStorage.setItem('mp_shopmode', '0');
  }
}

function toggleShopMode() {
  setShopMode(!$('body').hasClass('mp-shopmode'));
}

function initShopMode() {
  const saved = localStorage.getItem('mp_shopmode');
  if (saved === '1') setShopMode(true);

  $('#btnShopMode').on('click', function () {
    toggleShopMode();
  });
}

/* ---------- INIT (1 só) ---------- */
$(function () {

  $('#btnOpenCart').on('click', openCart);
  $('#btnCloseCart').on('click', closeCart);

  $('#cartDrawer').on('click', function(e){
    if(e.target === this) closeCart();
  });

  $(document).on('click', '.btn-qty-plus', function(){
    const id = parseInt($(this).closest('.mp-cart-item').data('id'));
    const cart = getCart();
    const idx = cart.findIndex(x => parseInt(x.id) === id);
    if(idx >= 0){ cart[idx].qtd = (parseInt(cart[idx].qtd)||0) + 1; }
    setCart(cart);
    renderCartCount();
    renderCartDrawer();
  });

  $(document).on('click', '.btn-qty-minus', function(){
    const id = parseInt($(this).closest('.mp-cart-item').data('id'));
    let cart = getCart();
    const idx = cart.findIndex(x => parseInt(x.id) === id);
    if(idx >= 0){
      cart[idx].qtd = (parseInt(cart[idx].qtd)||0) - 1;
      if(cart[idx].qtd <= 0) cart.splice(idx, 1);
    }
    setCart(cart);
    renderCartCount();
    renderCartDrawer();
  });

  $(document).ready(function () {

  // aplicar Select2 no dropdown de ordenação
    $('#sortSelect').select2({
      minimumResultsForSearch: Infinity, // sem barra de pesquisa
       width: '160px'
    });

  });

  // UI base
  renderCartCount();
  renderSkeletons();

  // Data
  carregarProdutos();
  carregarParceiros();

  // Binds
  bindSearch();
  bindSort();
  bindAddToCart();
  bindCategories();

  // Features
  initHeroSlider();
  initShopMode();

});
