<?php require_once __DIR__ . '/src/auth/auth_admin.php'; ?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-bs-theme="dark" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Consultas | Saúde Num Ponto</title>
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="src/css/sidebar.css">

    <link rel="stylesheet" href="src/css/consultas.css">

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
                        <a href="consultas.php" class="nav-link menu-link active">
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
                            <h4 class="mb-sm-0">Consultas</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <!-- ===================== CONSULTAS: CONTEÚDO ===================== -->

                <div class="row g-3 align-items-stretch mb-2">
                    <div class="col-12 col-md-4">
                        <div class="card kpi-card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="avatar-sm rounded d-flex align-items-center justify-content-center"><i
                                        class="ri-calendar-check-line"></i></div>
                                <div>
                                    <div class="text-muted fs-12">Consultas agendadas</div>
                                    <div id="kpi-agendadas" class="kpi-value">0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="card kpi-card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="avatar-sm rounded d-flex align-items-center justify-content-center"><i
                                        class="ri-checkbox-circle-line"></i></div>
                                <div>
                                    <div class="text-muted fs-12">Concluídas</div>
                                    <div id="kpi-concluidas" class="kpi-value">0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="card kpi-card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="avatar-sm rounded d-flex align-items-center justify-content-center"><i
                                        class="ri-hand-coin-line"></i></div>
                                <div>
                                    <div class="text-muted fs-12">Receita Mensal</div>
                                    <div id="kpi-receita" class="kpi-value">€ 0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Coluna esquerda: Calendário + Lembretes -->
                <div class="row g-3 align-items-stretch">
                    <div class="col-12 col-xl-4">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">Calendário</span>
                                <div class="d-flex align-items-center gap-2">
                                    <button id="btn-cal-expand" type="button" class="btn btn-sm btn-soft-warning">
                                        <i class="ri-layout-2-line"></i>
                                    </button>
                                    <button id="cal-prev" class="btn btn-sm btn-soft-secondary">
                                        <i class="ri-arrow-left-s-line"></i>
                                    </button>
                                    <div id="cal-title" class="fw-semibold"></div>
                                    <button id="cal-next" class="btn btn-sm btn-soft-secondary"><i
                                            class="ri-arrow-right-s-line"></i></button>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <table class="table table-borderless mini-calendar mb-0">
                                    <thead class="text-muted">
                                        <tr class="text-center">
                                            <th>Dom</th>
                                            <th>Seg</th>
                                            <th>Ter</th>
                                            <th>Qua</th>
                                            <th>Qui</th>
                                            <th>Sex</th>
                                            <th>Sáb</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cal-body"></tbody>
                                </table>
                                <small class="text-muted d-block mt-2">Clique num dia para ver as consultas.</small>
                            </div>
                        </div>

                        <div class="card shadow-sm mt-3">
                            <div class="card-header"><span class="fw-semibold">Lembretes de Pagamento</span></div>
                            <div class="list-group list-group-flush" id="lista-lembretes"></div>
                            <div class="card-footer text-end">
                                <button id="btn-enviar-lembrete" type="button"
                                    class="btn btn-soft-warning btn-sm">Enviar Lembrete</button>
                            </div>
                        </div>
                    </div>
                    <!-- Coluna direita: Hoje + Tabela -->
                    <div class="col-12 col-xl-8">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">Consultas de Hoje</span>
                            </div>
                            <div class="card-body" id="consultas-hoje">
                            </div>
                        </div>

                        <div class="card shadow-sm mt-3">
                            <div class="card-header d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                <span class="fw-semibold">Todas as Consultas</span>
                                <button id="btn-nova-consulta" type="button" class="btn btn-sm btn-primary">
                                    <i class="ri-add-line me-1"></i> Nova Consulta
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Profissional</th>
                                            <th>Serviço</th>
                                            <th>Data/Hora</th>
                                            <th>Valor</th>
                                            <th>Estado</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabela-consultas"></tbody>
                                </table>
                            </div>
                            <div class="card-footer text-muted small d-flex justify-content-between align-items-center">
                                <div id="consultas-info">Página 1</div>
                                <div class="d-flex align-items-center gap-2">
                                    <button id="cons-prev" class="btn btn-sm btn-outline-light">←</button>
                                    <button id="cons-next" class="btn btn-sm btn-outline-light">→</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="novaConsultaModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form id="form-nova-consulta" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Nova Consulta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label">Cliente</label>
                                    <select id="sel-cliente" name="codigo_cliente" class="form-select"></select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Profissional</label>
                                    <select id="sel-prof" name="id_prestador" class="form-select"></select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Serviço</label>
                                    <select id="sel-servico" name="id_servico" class="form-select">
                                        <option value="0">-- Selecionar --</option>
                                        <option value="6">PSICOLOGIA</option>
                                        <option value="7">NUTRIÇÃO</option>
                                        <option value="8">PT</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Serviço extra (opcional)</label>
                                    <div class="dropdown w-100">
                                        <button
                                            class="btn btn-dark btn-extras w-100 d-flex justify-content-between align-items-center"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="label-extras">Selecionar extras...</span>
                                            <i class="ri-arrow-down-s-line"></i>
                                        </button>
                                        <div class="dropdown-menu w-100 px-3 py-2 dropdown-extras">
                                            <div class="form-check">
                                                <input class="form-check-input chk-extra" type="checkbox" value="11"
                                                    data-preco="10" id="extra11">
                                                <label class="form-check-label" for="extra11">
                                                    Exame Bioimpedância
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input chk-extra" type="checkbox" value="12"
                                                    data-preco="15" id="extra12">
                                                <label class="form-check-label" for="extra12">
                                                    Avaliação Física
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input chk-extra" type="checkbox" value="13"
                                                    data-preco="8" id="extra13">
                                                <label class="form-check-label" for="extra13">
                                                    Relatório detalhado
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input chk-extra" type="checkbox" value="14"
                                                    data-preco="5" id="extra14">
                                                <label class="form-check-label" for="extra14">
                                                    Avaliação inicial
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <select id="sel-servico-extra" name="extras[]" multiple class="d-none">
                                        <option value="11" data-preco="10">Exame Bioimpedância</option>
                                        <option value="12" data-preco="15">Avaliação Física</option>
                                        <option value="13" data-preco="8">Relatório detalhado</option>
                                        <option value="14" data-preco="5">Avaliação inicial</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Data</label>
                                    <input type="text" name="data" id="data-consulta" class="form-control"
                                        placeholder="dd/mm/aaaa">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Hora</label>
                                    <input name="hora" type="time" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Valor (€)</label>
                                    <input name="valor" id="input-valor" class="form-control" type="text" readonly
                                        placeholder="auto">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Estado</label>
                                    <select name="id_estado" class="form-select">
                                        <option value="0" disabled selected>-- Selecionar --</option>
                                        <option value="15">Confirmada</option>
                                        <option value="13">Pendente</option>
                                        <option value="16">Concluida</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="extras-box p-2">
                                        <label class="form-label">Extras selecionados</label>
                                        <div class="table-responsive">
                                            <table class="table table-sm mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Serviço extra</th>
                                                        <th class="text-end">Preço (€)</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lista-extras">
                                                    <tr>
                                                        <td colspan="2" class="text-muted">Sem extras selecionados</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-light" type="button" data-bs-dismiss="modal">Fechar</button>
                            <button class="btn btn-primary" type="submit">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



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
    <!-- JAVASCRIPT -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap e plugins -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <!-- Outros gráficos e mapas -->
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>

    <script src="assets/libs/chart.js/chart.umd.js"></script>
    <script src="assets/js/layout.js"></script>
    <script src="assets/js/app.js"></script>

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="src/css/sweetalertFinalVersion.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

    <script src="src/js/utilizador.js"></script>

    <script src="src/js/notificacoesGlobal.js"></script>

    <script src="src/js/header-user.js"></script>

    <script src="src/js/consultas.js"></script>

    <script src="src/js/login.js"></script>

</body>

</html>