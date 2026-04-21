<?php require_once __DIR__ . '/src/auth/auth_admin.php'; ?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-bs-theme="dark" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Vendas | Saúde Num Ponto</title>
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

    <link rel="stylesheet" href="src/css/sidebar.css">

    <link rel="stylesheet" href="src/css/sweetalertFinalVersion.css">

    <link rel="stylesheet" href="src/css/vendas.css">

    <link rel="stylesheet" href="src/css/style.css">
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />


    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

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

    <!-- ========== App Menu ========== -->
    <div class="app-menu navbar-menu">
        <div class="navbar-brand-box">
            <!-- Logo -->
            <a href="index.php" class="logo logo-dark">
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
                        <a href="vendas.php" class="nav-link menu-link active">
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
                        <a href="chat.php" class="nav-link menu-link">
                            <i class="ri-chat-3-line"></i> <span>Chat</span>
                        </a>
                    </li>
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

                </ul>
            </div>
        </div>

        <div class="sidebar-background"></div>
    </div>
    <!-- Left Sidebar End -->
    <div class="vertical-overlay"></div>
    <!-- /Sidebar -->

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                            <h4 class="mb-sm-0">Vendas</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div id="vendas-page" class="container-fluid">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-packs"
                                type="button" role="tab">
                                Packs
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-indiv-grupo"
                                type="button" role="tab">
                                Individuais & Aulas de Grupo
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-faturacao" type="button"
                                role="tab">
                                Faturação
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- ===================== PACKS ===================== -->
                        <div class="tab-pane fade show active" id="tab-packs" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="card shadow-sm card-tight">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span class="fw-semibold">Vendas por Pack Trimestral</span>
                                        </div>
                                        <div class="card-body">
                                            <!-- Filtro de trimestre -->
                                            <div class="d-flex align-items-center mb-3">
                                                <label for="sel-tri" class="me-2">Trimestre:</label>
                                                <select id="sel-tri" class="form-select form-select-sm d-inline-block"
                                                    style="width:auto;min-width:160px"></select>
                                            </div>

                                            <!-- Gráfico -->
                                            <div id="chart-packs"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card shadow-sm card-tight">
                                        <div class="card-header"><span class="fw-semibold">Interações (nº pessoas)
                                                por mês</span></div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Mês</th>
                                                            <th class="text-end">Medio</th>
                                                            <th class="text-end">Pro</th>
                                                            <th class="text-end">Duo</th>
                                                            <th class="text-end">Lar</th>
                                                            <th class="text-end">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbl-packs"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer text-muted small">* Interações = nº de
                                            pessoas/assinaturas por pack.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===================== INDIVIDUAIS & GRUPO ===================== -->
                        <div class="tab-pane fade" id="tab-indiv-grupo" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-2 month-switch">
                                        <label for="sel-mes" class="me-2">Mês:</label>
                                        <select id="sel-mes" class="form-select form-select-sm d-inline-block"
                                            style="width:auto; min-width: 140px;"></select>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-6">
                                    <div class="card shadow-sm card-tight h-100">
                                        <div class="card-header"><span class="fw-semibold">Consultas Individuais</span>
                                        </div>
                                        <div class="card-body">
                                            <div id="chart-individuais"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-6">
                                    <div class="card shadow-sm card-tight h-100">
                                        <div class="card-header"><span class="fw-semibold">Aulas de Grupo</span></div>
                                        <div class="card-body">
                                            <div id="chart-grupo"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="card">
                                        <div class="card-header"><span class="fw-semibold">Consultas Individuais
                                                (€)</span></div>
                                        <div class="card-body">
                                            <div id="chart-individuais-valor"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="card">
                                        <div class="card-header"><span class="fw-semibold">Aulas de Grupo (€)</span>
                                        </div>
                                        <div class="card-body">
                                            <div id="chart-grupo-valor"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-0">
                                <div class="col-12">
                                    <div class="card shadow-sm card-tight">
                                        <div class="card-header"><span class="fw-semibold">Interações por mês
                                                (resumo)</span></div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="tbl-resumo" class="table table-sm align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Mês</th>
                                                            <th class="text-end">Individuais</th>
                                                            <th class="text-end">Grupo</th>
                                                            <th class="text-end">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbl-indiv-grupo"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===================== FATURAÇÃO ===================== -->
                        <div class="tab-pane fade" id="tab-faturacao" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="card shadow-sm card-tight">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span class="fw-semibold">Últimas Transações</span>
                                            <div class="d-flex gap-2 align-items-center">
                                                <input id="faturacao-search" class="form-control form-control-sm"
                                                    style="width:220px" placeholder="Pesquisar cliente ou serviço…">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="tbl-faturacao" class="table align-middle mb-0">
                                                <colgroup>
                                                    <col>
                                                    <col>
                                                    <col>
                                                    <col>
                                                    <col>
                                                    <col>
                                                </colgroup>
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Cliente</th>
                                                        <th>Serviço</th>
                                                        <th>Data</th>
                                                        <th class="text-end">Valor</th>
                                                        <th>Estado</th>
                                                        <th class="text-end">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div
                                            class="card-footer text-muted small d-flex justify-content-between align-items-center">
                                            <div id="faturacao-info">Página 1</div>
                                            <div>
                                                <button id="fat-prev"
                                                    class="btn btn-sm btn-outline-light me-2">←</button>
                                                <button id="fat-next" class="btn btn-sm btn-outline-light">→</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Enviar Fatura -->
                <div class="modal fade" id="modalFatura" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-light p-3">
                                <h5 class="modal-title">Enviar Fatura</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Fechar"></button>
                            </div>

                            <form id="formFatura" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <input type="hidden" id="faturaVendaId" name="id_venda" />
                                    <div class="mb-3">
                                        <label for="fileFatura" class="form-label">Selecione a fatura</label>
                                        <input type="file" class="form-control" id="fileFatura" name="ficheiro"
                                            accept=".pdf,.jpg,.jpeg,.png" required />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- container-fluid -->

        <!-- End Page-content -->
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

    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-primary btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!-- jQuery-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>
    <script src="src/js/utilizador.js"></script>
    <script src="src/js/notificacoesGlobal.js"></script>
    <script src="src/js/header-user.js"></script>
    <script src="src/js/vendas.js"></script>
    <script src="src/js/login.js"></script>
</body>

</html>