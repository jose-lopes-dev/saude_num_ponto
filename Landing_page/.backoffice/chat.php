<?php require_once __DIR__ . '/src/auth/auth_admin.php'; ?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-bs-theme="dark" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Chat | Saúde Num Ponto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/Logo.png ">

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="src/css/sweetalertFinalVersion.css">

    <link rel="stylesheet" href="src/css/sidebar.css">
    <!-- Chat Css (separado) -->
    <link href="src/css/chat.css" rel="stylesheet" type="text/css" />
</head>

<body>


    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header snp-topbar">
                    <div class="d-flex align-items-center">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="index.php" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="assets/images/logo_saude_num_ponto.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="assets/images/logo_saude_num_ponto.png" alt="" height="17">
                                </span>
                            </a>

                            <a href="index.php" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="assets/images/logo_saude_num_ponto.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="assets/images/logo_saude_num_ponto.png" alt="" height="17">
                                </span>
                            </a>
                        </div>



                        <!-- App Search -->
                        <form class="app-search d-none d-md-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Pesquisar..." autocomplete="off"
                                    id="search-options" value="">
                                <span class="mdi mdi-magnify search-widget-icon"></span>
                                <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                                    id="search-close-options"></span>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex align-items-center">

                        <!-- Search (mobile) -->
                        <div class="dropdown d-md-none topbar-head-dropdown header-item">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="bx bx-search fs-22"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-search-dropdown">
                                <form class="p-3">
                                    <div class="form-group m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Pesquisar..."
                                                aria-label="Recipient's username">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="mdi mdi-magnify"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        

                        <!-- Notifications (icon only, sem bolinha, sem fundo) -->
                        <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
    <button
        type="button"
        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
        id="page-header-notifications-dropdown"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside"
        aria-haspopup="true"
        aria-expanded="false">

        <i class="bx bx-bell fs-22"></i>

        <span
            id="notification-count"
            class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger d-none">
        </span>
    </button>

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
         aria-labelledby="page-header-notifications-dropdown">

        <div class="dropdown-head bg-primary bg-pattern rounded-top">
            <div class="p-3">
                <h6 class="m-0 fs-16 fw-semibold text-white">Notificações</h6>
            </div>
        </div>

        <div class="py-2 ps-2">
            <div data-simplebar style="max-height: 300px;" class="pe-2">
                <div id="notification-list"></div>
            </div>
        </div>

        <div class="p-3 border-top">
            <button
                id="btn-clear-notifications"
                type="button"
                class="btn btn-soft-danger w-100">
                Limpar notificações
            </button>
        </div>

    </div>
</div>


                        <!-- user -->
                         <div class="dropdown ms-sm-3 header-item topbar-user">
    <button
        type="button"
        class="btn"
        id="page-header-user-dropdown"
        data-bs-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
    >
        <span class="d-flex align-items-center">

            <img
                id="user-avatar"
                class="rounded-circle header-profile-user"
                src=""
                alt="Header Avatar"
            />

            <span class="text-start ms-xl-2">
                <span
                    id="user-name"
                    class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text">
                </span>

                <span
                    id="user-role"
                    class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">
                </span>
            </span>

        </span>
    </button>



   <div class="dropdown-menu dropdown-menu-end">
        <h6 class="dropdown-header">
            Bem-Vindo <span id="welcome-username"></span>!
        </h6>
                <a class="dropdown-item" href="perfil_admin.php"><i
                    class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i>
                  <span class="align-middle">Perfil</span></a>
                <a class="dropdown-item" href="suporte_admin.php"><i
                    class="ri-customer-service-2-line me-2"></i>
                  <span class="align-middle">Suporte</span></a>
                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="#" onclick="logout(); return false;">
            <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
            <span class="align-middle">Logout</span>
        </a>

              </div>
            </div>
            <!-- end user -->
                    </div>
                </div>
            </div>

        </header>
    </div>
    <!-- End page -->

    <!-- SIDEBAR (copiada do ativos.html) -->
    <div class="app-menu navbar-menu">
        <div class="navbar-brand-box">
            <!-- Logo -->
            <a href="dashboard.html" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="assets/images/logo_bw_recolor_precisa.png" alt="" height="30">
                </span>
                <span class="logo-lg">
                    <img src="assets/images/logo_bw_recolor_precisa.png" alt="" height="30">
                </span>
            </a>
            <a href="index.php" class="logo logo-light">
                <span class="logo-sm">
                    <img src="assets/images/logo_bw_recolor_precisa.png" alt="" height="30">
                </span>
                <span class="logo-lg">
                    <img src="assets/images/logo_bw_recolor_precisa.png" alt="" height="31">
                </span>
            </a>
            <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>

        <div id="scrollbar">
            <div class="container-fluid">
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link menu-link">
                            <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="vendas.php" class="nav-link menu-link">
                            <i class="ri-shopping-cart-line"></i> <span>Vendas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="consultas.php" class="nav-link menu-link">
                            <i class="ri-file-list-3-line"></i> <span>Consultas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="custos.php" class="nav-link menu-link">
                            <i class="ri-money-dollar-circle-line"></i> <span>Custos e Rendimentos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="rh.php" class="nav-link menu-link">
                            <i class="ri-team-line"></i> <span>Recursos Humanos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="clientes.php" class="nav-link menu-link">
                            <i class="ri-user-3-line"></i> <span>Clientes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="financas.php" class="nav-link menu-link">
                            <i class="ri-bank-line"></i> <span>Finanças</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="comissoes.php" class="nav-link menu-link">
                            <i class="ri-cash-line"></i> <span>Vencimento</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="fornecedores.php" class="nav-link menu-link">
                            <i class="ri-truck-line"></i> <span>Fornecedores</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="ativos.php" class="nav-link menu-link">
                            <i class="ri-archive-line"></i> <span>Ativos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="calendario.php" class="nav-link menu-link">
                            <i class="ri-calendar-line"></i> <span>Calendário</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="chat.php" class="nav-link menu-link active">
                            <i class="ri-chat-3-line"></i> <span>Chat</span>
                        </a>
                    <li class="nav-item">
                        <a href="admin_aulas.php" class="nav-link menu-link">
                            <i class="ri-video-chat-line"></i> <span>Gestão de Aulas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="treinos_admin.php" class="nav-link menu-link">
                            <i class="ri-body-scan-line"></i> <span>Treinos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="produto_admin.php" class="nav-link menu-link">
                            <i class="ri-shopping-bag-3-line"></i> <span>Marketplace</span>
                        </a>
                    </li>

                    </li>
                </ul>
            </div>
        </div>
        main
    </div>
    </div>
    </div>
    <!-- /page title -->

    <!-- CONTEÚDO DO CHAT -->
 <div class="main-content">

  <!-- ID DO UTILIZADOR LOGADO (OBRIGATÓRIO PARA O JS) -->
  <input type="hidden" id="userId" value="<?= $_SESSION['id'] ?>">

  <div class="page-content chat-page">
    <div class="container-fluid">

      <!-- page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
            <h4 class="mb-sm-0">Chat</h4>
          </div>
        </div>
      </div>
      <!-- /page title -->

      <!-- CONTEÚDO DO CHAT -->
      <div class="row">

        <!-- Lista de conversas -->
        <div class="col-lg-3 col-md-5">
          <div class="card chat-leftsidebar h-100">

            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Conversas</h5>
              <button type="button" class="btn btn-sm btn-primary" id="btnNovaConversa">
                <i class="ri-add-line me-1"></i> Nova
              </button>
            </div>

            <div class="card-body p-0">
              <ul id="users-conversation" class="list-unstyled mb-0"></ul>
            </div>

          </div>
        </div>

        <!-- Janela de conversa -->
        <div class="col-lg-9 col-md-7">
          <div class="card user-chat h-100">

            <div class="card-header">
              <h5 class="mb-0 username">Seleciona uma conversa…</h5>
            </div>

            <div class="card-body chat-conversation pane-scroll" id="chat-box">
              <ul id="chat-messages"
                  class="list-unstyled chat-conversation-list p-3 m-0"></ul>
            </div>

            <div class="card-footer">
              <form id="chat-form" autocomplete="off" class="d-flex gap-2">
                <input
                  type="text"
                  id="chat-input"
                  class="form-control"
                  placeholder="Escreve uma mensagem..."
                >
                <button type="submit" class="btn btn-success chat-send">
                  <i class="ri-send-plane-2-fill"></i>
                </button>
              </form>
              <div class="chat-input-feedback d-none">
                Não podes enviar uma mensagem vazia.
              </div>
            </div>

          </div>
        </div>

      </div>
      <!-- /CONTEÚDO DO CHAT -->

    </div>
  </div>

  <!-- Modal Nova Conversa -->
  <div class="modal fade" id="modalNovaConversa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Nova conversa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <label class="form-label">Pesquisar utilizador</label>
          <select id="novo-destinatario" class="form-control"></select>
          <small class="text-muted d-block mt-1">
            Começa a escrever o nome ou email.
          </small>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-ghost-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="button" class="btn btn-primary" id="confirmNovaConversa">
            Iniciar
          </button>
        </div>

      </div>
    </div>
  </div>

</div>

    <!-- Footer (mantido) -->
    <footer class="footer border-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>document.write(new Date().getFullYear())</script> © Saude Num Ponto.
                </div>
            </div>
        </div>
    </footer>
    <!-- end main content-->
    </div>
    <!-- /MAIN CONTENT -->

    <!-- END layout-wrapper -->

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-primary btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!-- jQuery (igual ao ativos.html) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script src="src/js/utilizador.js"></script>

    <script src="src/js/notificacoesGlobal.js"></script>

    <script src="src/js/header-user.js"></script>

    <!-- JS do Chat -->
    <script src="src/js/chat.js"></script>

    <script src="src/js/login.js"></script>

    </div>

</body>

</html>