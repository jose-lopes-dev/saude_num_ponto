<?php require_once __DIR__ . '/src/auth/auth_pt.php'; ?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-bs-theme="dark" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Planos e Serviços | Saúde Num Ponto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/Logo.png ">

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- Custom Css -->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

    <!-- FullCalendar CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">

    <!-- Sidebar + página consultas (mantidos) -->
    <link rel="stylesheet" href="src/css/calendarioConsultas.css">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/css/sweetalertFinalVersion.css">

    <link rel="stylesheet" href="src/css/sidebar.css">

    <link rel="stylesheet" href="src/css/plano_servico.css">

    <link rel="stylesheet" href="src/css/global.css">

</head>

<body>
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header snp-topbar">
                    <div class="d-flex align-items-center">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="index.html" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="assets/images/logo_saude_num_ponto.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="assets/images/logo_saude_num_ponto.png" alt="" height="17">
                                </span>
                            </a>

                            <a href="index.html" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="assets/images/logo_saude_num_ponto.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="assets/images/logo_saude_num_ponto.png" alt="" height="17">
                                </span>
                            </a>
                        </div>


                        <!-- TÍTULO + BREADCRUMBS -->
                        <div class="topbar-page-title d-none d-lg-flex flex-column ms-3">
                            <span class="topbar-title">Planos/Serviços</span>
                            <ol class="breadcrumb breadcrumb-sm mb-0">
                                <li class="breadcrumb-item">
                                    <a href="planos_pt.php">PT</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Planos/Serviços</li>
                            </ol>
                        </div>

                        <!-- PESQUISA -->
                        <form class="app-search topbar-search d-none d-md-block ms-4">
                            <div class="position-relative">
                                <input type="text" class="form-control"
                                    placeholder="Pesquisar treinos, planos, consultas..." autocomplete="off"
                                    id="search-options" value="">
                                <span class="mdi mdi-magnify search-widget-icon"></span>
                                <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                                    id="search-close-options"></span>
                            </div>
                            <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
                                <div data-simplebar style="max-height: 320px;">
                                    <div class="dropdown-header">
                                        <h6 class="text-overflow text-muted mb-0 text-uppercase">Pesquisas Recentes</h6>
                                    </div>

                                    <div class="dropdown-item bg-transparent text-wrap">
                                        <a href="index.php" class="btn btn-soft-primary btn-sm rounded-pill">Plano
                                            treino <i class="mdi mdi-magnify ms-1"></i></a>
                                        <a href="index.php" class="btn btn-soft-primary btn-sm rounded-pill">Consultas
                                            <i class="mdi mdi-magnify ms-1"></i></a>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>


                    <div class="d-flex align-items-center">

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
                                            <input type="text" class="form-control" placeholder="Search ..."
                                                aria-label="Recipient's username">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="mdi mdi-magnify"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <!-- item -->

                        <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                id="page-header-notifications-dropdown" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">

                                <i class="bx bx-bell fs-22"></i>

                                <span id="notification-count"
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
                                    <button id="btn-clear-notifications" type="button"
                                        class="btn btn-soft-danger w-100">
                                        Limpar notificações
                                    </button>
                                </div>

                            </div>
                        </div>

                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">

                                <span class="d-flex align-items-center">

                                    <img id="user-avatar" class="rounded-circle header-profile-user" src=""
                                        alt="Header Avatar">

                                    <span class="text-start ms-2">
                                        <span id="user-name" class="fw-semibold user-name-text d-block">
                                        </span>

                                        <span id="user-role" class="fs-12 user-name-sub-text d-block">
                                            Nutricionista
                                        </span>
                                    </span>

                                </span>
                            </button>


                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">
                                    Bem-Vindo <span id="welcome-username"></span>!
                                </h6>

                                <a class="dropdown-item" href="perfil_pt.php">
                                    <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i>
                                    <span class="align-middle">Perfil</span>
                                </a>

                                <a class="dropdown-item" href="suporte_pt.php">
                                    <i class="ri-customer-service-2-line me-2"></i>
                                    <span class="align-middle">Suporte</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="#" onclick="logout(); return false;">
                                    <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                                    <span class="align-middle">Logout</span>
                                </a>
                            </div>
                        </div>


        </header>
    </div>
    <!-- ========== App Menu ========== -->
    <div class="app-menu navbar-menu">
        <div class="navbar-brand-box">
            <!-- Logo -->
            <a href="dashboard_pt.php" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="assets/images/logo_bw_recolor_precisa.png" alt="" height="30">
                </span>
                <span class="logo-lg">
                    <img src="assets/images/logo_bw_recolor_precisa.png" alt="" height="30">
                </span>
            </a>
            <a href="dashboard_pt.php" class="logo logo-light">
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
                        <a href="dashboard_pt.php" class="nav-link menu-link">
                            <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="planotreino_pt.php" class="nav-link menu-link">
                            <i class="ri-calendar-check-line"></i> <span>Plano de Treino</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="consultas_pt.php" class="nav-link menu-link">
                            <i class="ri-calendar-check-line"></i> <span>Consultas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="aulas_pt.php" class="nav-link menu-link">
                            <i class="ri-video-chat-line"></i> <span>Aulas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="clientes_pt.php" class="nav-link menu-link">
                            <i class="ri-user-3-line"></i> <span>Clientes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="agenda_pt.php" class="nav-link menu-link">
                            <i class="ri-calendar-event-line"></i> <span>Agenda</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="planos_pt.php" class="nav-link menu-link active">
                            <i class="ri-list-check-2"></i> <span>Planos/Serviços</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="vendas_pt.php" class="nav-link menu-link">
                            <i class="ri-money-euro-circle-line"></i> <span>Vendas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="comissoes_pt.php" class="nav-link menu-link">
                            <i class="ri-percent-line"></i> <span>Comissões</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="chat_pt.php" class="nav-link menu-link">
                            <i class="ri-chat-3-line"></i> <span>Chat</span>
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
    <div class="main-content">
        <div class="page-content pt-page">
            <div class="container-fluid">

                <div class="row g-3">

                    <!-- COLUNA ESQUERDA -->
                    <div class="col-12 col-xl-6">
                        <div class="card pt-card-lg pt-topbar-card h-100">
                            <div class="card-header">
                                <h5 class="mb-0">Planos do Sistema</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle" id="tblPlanosSistema">
                                        <thead>
                                            <tr>
                                                <th>Plano</th>
                                                <th>Preço</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listagemPlanosSistema"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- COLUNA DIREITA -->
                    <div class="col-12 col-xl-6">
                        <div class="card pt-card-lg pt-topbar-card h-100">
                            <div class="card-header">
                                <h5 class="mb-0">Meus Serviços</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 align-items-end mb-3">
                                    <div class="col-12 col-lg-6">
                                        <label class="form-label" for="selectServicosCatalogo">Serviço</label>
                                        <select class="form-select" id="selectServicosCatalogo"></select>
                                    </div>
                                    <div class="col-12 col-lg-3 pt-body">
                                        <label class="form-label" for="filtroEstadoServicos">Filtrar</label>
                                        <select class="select2" id="filtroEstadoServicos">
                                            <option value="-1" selected>Todos</option>
                                            <option value="1">Ativos</option>
                                            <option value="0">Inativos</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <button type="button" class="btn pt-btn w-100"
                                            onclick="adicionaServicoAoPt()">
                                            <i class="ri-add-line"></i> Adicionar
                                        </button>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="table-responsive">
                                    <table class="table table-striped w-100" id="tblMeusServicos">
                                        <thead>
                                            <tr>
                                                <th>Serviço</th>
                                                <th>Preço Catálogo</th>
                                                <th>Estado</th>
                                                <th class="text-end">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listagemMeusServicos"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LINHA INTEIRA -->
                    <div class="col-12">
                        <div class="card pt-card-lg pt-topbar-card">
                            <div class="card-header">
                                <h5 class="mb-0">Minhas Aulas</h5>
                            </div>

                            <div class="card-body">
                                <div class="row g-3 align-items-end mb-3">
                                    <div class="col-12 col-lg-6 pt-body">
                                        <label class="form-label" for="selectTiposAulaGrupo">Tipo de Aula</label>
                                        <select class="form-select" id="selectTiposAulaGrupo"></select>
                                    </div>

                                    <div class="col-12 col-lg-3 pt-body">
                                        <label class="form-label" for="filtroEstadoTiposAula">Filtrar</label>
                                        <select class="select2" id="filtroEstadoTiposAula">
                                            <option value="-1" selected>Todos</option>
                                            <option value="1">Ativos</option>
                                            <option value="0">Inativos</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-3 d-grid">
                                        <button type="button" class="btn pt-btn" id="btnAddTipoAulaGrupo">+
                                            Adicionar</button>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="table-responsive">
                                    <table class="table table-striped w-100" id="tblTiposAulaPt">
                                        <thead>
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Estado</th>
                                                <th class="text-end">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listagemTiposAulaPt"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div><!-- container-fluid -->
                </div><!-- page-content -->
            </div><!-- main-content -->
        </div><!-- layout-wrapper -->


        <!-- ===== MODAL EDIT PLANO ===== -->
        <div class="modal fade" id="modalEditPlano" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Plano</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">
                        <form class="row g-3" id="formPlanoEdit" autocomplete="off">
                            <div class="col-12 col-md-6">
                                <label for="planoNomeEdit" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="planoNomeEdit">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="planoPrecoEdit" class="form-label">Preço</label>
                                <input type="number" step="0.01" class="form-control" id="planoPrecoEdit">
                            </div>

                            <div class="col-12">
                                <label for="planoDescricaoEdit" class="form-label">Descrição</label>
                                <input type="text" class="form-control" id="planoDescricaoEdit">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="planoEstadoEdit" class="form-label">Estado</label>
                                <select class="form-select" id="planoEstadoEdit">
                                    <option value="1">Ativo</option>
                                    <option value="0">Inativo</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-success" id="btnGuardarPlano">Guardar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- ===== MODAL EDIT SERVIÇO ===== -->
        <div class="modal fade" id="modalEditServico" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Serviço</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">
                        <form class="row g-3" id="formServicoEdit" autocomplete="off">
                            <div class="col-12 col-md-6">
                                <label for="servNomeEdit" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="servNomeEdit">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="servPrecoEdit" class="form-label">Preço</label>
                                <input type="number" step="0.01" class="form-control" id="servPrecoEdit">
                            </div>

                            <div class="col-12">
                                <label for="servDescricaoEdit" class="form-label">Descrição</label>
                                <input type="text" class="form-control" id="servDescricaoEdit">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="servTipoEdit" class="form-label">Tipo</label>
                                <select class="form-select" id="servTipoEdit">
                                    <option value="Extra">Extra</option>
                                    <option value="Avulso">Avulso</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="servEstadoEdit" class="form-label">Estado</label>
                                <select class="form-select" id="servEstadoEdit">
                                    <option value="1">Ativo</option>
                                    <option value="0">Inativo</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-success" id="btnGuardarServico">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        
        <footer class="footer border-top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © Saude Num Ponto.
                        </div>
                    </div>
                </div>
            </footer>
    </div>
    <!-- end main content-->
    

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Bootstrap e plugins -->
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
        <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
        <script src="assets/js/plugins.js"></script>

        <!-- DataTables (não estragam nada se já usas noutros lados) -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

        <!-- Outros gráficos e mapas que já tinhas -->
        <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
        <script src="assets/libs/swiper/swiper-bundle.min.js"></script>
        <script src="assets/libs/chart.js/chart.umd.js"></script>
        <script src="assets/js/app.js"></script>

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>

        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- FullCalendar JS (CDN) -->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

        <!-- JS específico desta página -->
        <script src="src/js/utilizador.js"></script>

        <script src="src/js/header-user.js"></script>

        <script src="src/js/notificacoesGlobal.js"></script>

        <script src="src/js/calendarioConsultas.js"></script>

        <script src="src/js/planos_servico.js"></script>

        <script src="src/js/login.js"></script>

</body>

</html>