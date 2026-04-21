<?php require_once __DIR__ . '/src/auth/auth_pt.php'; ?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-bs-theme="dark" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Exercícios | Saúde Num Ponto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/Logo.png ">

    <link rel="stylesheet" href="src/css/style.css">
    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- Select2 CSS -->
    <link href="src/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="src/css/sweetalertFinalVersion.css">

    <link rel="stylesheet" href="src/css/sidebar.css">

    <link rel="stylesheet" href="src/css/style.css">
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

    <link href="src/css/sweetalert.css" rel="stylesheet" type="text/css" />

    <link href="src/css/configuracoes_cliente.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="src/css/global.css" />

    <link rel="stylesheet" href="src/css/planotreino.css" />

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
                            <a href="dashboard_pt.php" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="assets/images/logo_saude_num_ponto.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="assets/images/logo_saude_num_ponto.png" alt="" height="17">
                                </span>
                            </a>

                            <a href="dashboard_pt.php" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="assets/images/logo_saude_num_ponto.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="assets/images/logo_saude_num_ponto.png" alt="" height="17">
                                </span>
                            </a>
                        </div>

                        <!-- App Search-->
                        <form class="app-search d-none d-md-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Pesquise..." autocomplete="off"
                                    id="search-options" value="">
                                <span class="mdi mdi-magnify search-widget-icon"></span>
                                <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                                    id="search-close-options"></span>
                            </div>
                            <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
                                <div data-simplebar style="max-height: 320px;">
                                    <!-- item-->
                                    <div class="dropdown-header">
                                        <h6 class="text-overflow text-muted mb-0 text-uppercase">Pesquisas Recentes</h6>
                                    </div>

                                    <div class="dropdown-item bg-transparent text-wrap">
                                        <a href="dashboard_pt.php" class="btn btn-soft-primary btn-sm rounded-pill">how
                                            to
                                            setup <i class="mdi mdi-magnify ms-1"></i></a>
                                        <a href="dashboard_pt.php"
                                            class="btn btn-soft-primary btn-sm rounded-pill">buttons <i
                                                class="mdi mdi-magnify ms-1"></i></a>
                                    </div>
                                    <!-- item-->
                                    <div class="dropdown-header mt-2">
                                        <h6 class="text-overflow text-muted mb-1 text-uppercase">Pages</h6>
                                    </div>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="ri-bubble-chart-line align-middle fs-18 text-muted me-2"></i>
                                        <span>Analytics Dashboard</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="ri-lifebuoy-line align-middle fs-18 text-muted me-2"></i>
                                        <span>Help Center</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="ri-user-settings-line align-middle fs-18 text-muted me-2"></i>
                                        <span>My account settings</span>
                                    </a>

                                    <!-- item-->
                                    <div class="dropdown-header mt-2">
                                        <h6 class="text-overflow text-muted mb-2 text-uppercase">Members</h6>
                                    </div>

                                    <div class="notification-list">
                                        <!-- item -->
                                        <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                            <div class="d-flex">
                                                <img src="assets/images/users/avatar-2.jpg"
                                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                <div class="flex-grow-1">
                                                    <h6 class="m-0">Angela Bernier</h6>
                                                    <span class="fs-11 mb-0 text-muted">Manager</span>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- item -->
                                        <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                            <div class="d-flex">
                                                <img src="assets/images/users/avatar-3.jpg"
                                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                <div class="flex-grow-1">
                                                    <h6 class="m-0">David Grasso</h6>
                                                    <span class="fs-11 mb-0 text-muted">Web Designer</span>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- item -->
                                        <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                            <div class="d-flex">
                                                <img src="assets/images/users/avatar-5.jpg"
                                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                <div class="flex-grow-1">
                                                    <h6 class="m-0">Mike Bunch</h6>
                                                    <span class="fs-11 mb-0 text-muted">React Developer</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="text-center pt-3 pb-1">
                                    <a href="pages-search-results.html" class="btn btn-primary btn-sm">View All Results
                                        <i class="ri-arrow-right-line ms-1"></i></a>
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
                                    <img id="user-avatar" class="rounded-circle header-profile-user"
                                        src="assets/images/users/user-dummy-img.jpg" alt="Header Avatar">

                                    <span class="text-start ms-xl-2">
                                        <span id="user-name"
                                            class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text">
                                        </span>

                                        <span id="user-role" class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">
                                        </span>
                                    </span>
                                </span>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">
                                    Bem-Vindo <span id="welcome-username"></span>!
                                </h6>

                                <a class="dropdown-item" href="pages-profile.html">
                                    <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i>
                                    <span class="align-middle">Perfil</span>
                                </a>

                                <a class="dropdown-item" href="suporte.php">
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
                    </div>
                </div>
        </header>


        <!-- Sidebar -->
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
                            <a href="planotreino_pt.php" class="nav-link menu-link active">
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
                            <a href="planos_pt.php" class="nav-link menu-link">
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
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div
                                class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                                <h4 class="mb-sm-0">Exercícios</h4>
                            </div>
                        </div>
                    </div>

                    <div class="card pt-card-lg pt-table-card pt-exercicios-card pt-page">
                        <div class="card-header">
                            <div class="row g-2 align-items-end pt-toolbar">

                                <div class="col-md-2">
                                    <label class="form-label mb-1">Grupo</label>
                                    <select class="form-control" id="f_grupo">
                                        <option value="todos">Todos</option>
                                        <option value="costas">Costas</option>
                                        <option value="peito">Peito</option>
                                        <option value="pernas">Pernas</option>
                                        <option value="ombros">Ombros</option>
                                        <option value="biceps">Bíceps</option>
                                        <option value="triceps">Tríceps</option>
                                        <option value="core">Core</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label mb-1">Equipamento</label>
                                    <select class="form-control" id="f_equip">
                                        <option value="todos">Todos</option>
                                        <option value="maquina">Máquina</option>
                                        <option value="cabo">Cabo</option>
                                        <option value="barra">Barra</option>
                                        <option value="halteres">Halteres</option>
                                        <option value="peso_corporal">Peso corporal</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label mb-1">Pesquisa</label>
                                    <input type="text" class="form-control" id="f_pesquisa"
                                        placeholder="ex: remada, pulldown...">
                                </div>

                                <div class="col-md-1 d-grid">
                                    <button type="button" class="btn pt-btn pt-btn-accent"
                                        id="btnFiltrar">Filtrar</button>
                                </div>

                                <div class="col-md-1 d-grid">
                                    <button type="button" class="btn pt-btn pt-btn-accent-outline"
                                        id="btnLimparFiltros">Limpar</button>
                                </div>

                                <div class="col-md-1 d-grid">
                                    <button type="button" class="btn pt-btn pt-btn-accent"
                                        id="btnAbrirModalNovo">Novo</button>
                                </div>

                                <div class="col-md-1 d-grid">
                                    <a id="btnVoltarPlano" class="btn pt-btn pt-btn-back" href="planotreino_pt.php"
                                        title="Voltar">
                                        <i class="ri-arrow-left-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="pt-card-body">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Grupo</th>
                                            <th>Equipamento</th>
                                            <th>Tipo</th>
                                            <th>Dificuldade</th>
                                            <th class="text-center">Imagem</th>
                                            <th class="text-center">Editar</th>
                                            <th class="text-center">Remover</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listagemExercicios"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div id="exerciciosInfo" class="text-muted small"></div>
                            <nav aria-label="Paginação">
                                <ul id="paginacaoExercicios" class="pagination pagination-sm mb-0"></ul>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Modal: Novo Exercício -->
                <div class="modal fade" id="modalNovoExercicio" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Novo Exercício</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nome</label>
                                        <input type="text" class="form-control" id="nome">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Grupo</label>
                                        <select class="form-control" id="grupo">
                                            <option value="costas">Costas</option>
                                            <option value="peito">Peito</option>
                                            <option value="pernas">Pernas</option>
                                            <option value="ombros">Ombros</option>
                                            <option value="biceps">Bíceps</option>
                                            <option value="triceps">Tríceps</option>
                                            <option value="core">Core</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Equipamento</label>
                                        <select class="form-control" id="equipamento">
                                            <option value="maquina">Máquina</option>
                                            <option value="cabo">Cabo</option>
                                            <option value="barra">Barra</option>
                                            <option value="halteres">Halteres</option>
                                            <option value="peso_corporal">Peso corporal</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Tipo</label>
                                        <select class="form-control" id="tipo">
                                            <option value="composto">Composto</option>
                                            <option value="isolamento">Isolamento</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Dificuldade</label>
                                        <select class="form-control" id="dificuldade">
                                            <option value="">(opcional)</option>
                                            <option value="iniciante">Iniciante</option>
                                            <option value="intermedio">Intermédio</option>
                                            <option value="avancado">Avançado</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Video URL (opcional)</label>
                                        <input type="text" class="form-control" id="video_url"
                                            placeholder="https://...">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Imagem URL (opcional)</label>
                                        <input type="text" class="form-control" id="imagem_url"
                                            placeholder="https://...">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Descrição (opcional)</label>
                                        <textarea class="form-control" id="descricao" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                <button type="button" class="btn btn-primary"
                                    id="btnRegistarExercicio">Registar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal: Editar Exercício -->
                <div class="modal fade" id="modalEditExercicio" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Exercício</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" id="idEdit">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Nome</label>
                                        <input type="text" class="form-control" id="nomeEdit">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Grupo</label>
                                        <select class="form-control" id="grupoEdit">
                                            <option value="costas">Costas</option>
                                            <option value="peito">Peito</option>
                                            <option value="pernas">Pernas</option>
                                            <option value="ombros">Ombros</option>
                                            <option value="biceps">Bíceps</option>
                                            <option value="triceps">Tríceps</option>
                                            <option value="core">Core</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Equipamento</label>
                                        <select class="form-control" id="equipamentoEdit">
                                            <option value="maquina">Máquina</option>
                                            <option value="cabo">Cabo</option>
                                            <option value="barra">Barra</option>
                                            <option value="halteres">Halteres</option>
                                            <option value="peso_corporal">Peso corporal</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Tipo</label>
                                        <select class="form-control" id="tipoEdit">
                                            <option value="composto">Composto</option>
                                            <option value="isolamento">Isolamento</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Dificuldade</label>
                                        <select class="form-control" id="dificuldadeEdit">
                                            <option value="">(opcional)</option>
                                            <option value="iniciante">Iniciante</option>
                                            <option value="intermedio">Intermédio</option>
                                            <option value="avancado">Avançado</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Video URL (opcional)</label>
                                        <input type="text" class="form-control" id="video_urlEdit">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Imagem URL (opcional)</label>
                                        <input type="text" class="form-control" id="imagem_urlEdit">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Descrição (opcional)</label>
                                        <textarea class="form-control" id="descricaoEdit" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="guardaEditExercicio()">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- End main content -->

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


        <!-- JAVASCRIPT -->
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
        <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
        <script src="assets/js/plugins.js"></script>

        <script src="src/js/lib/jquery3.6.0.min.js"></script>
        <!-- App js -->
        <script src="assets/js/app.js"></script>

        <script src="src/js/lib/select2.min.js"></script>

        <script src="assets/libs/chart.js/chart.umd.js"></script>

        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

        <script src="src/js/lib/jquery3.6.0.min.js"></script>

        <script src="src/js/notificacoesGlobal.js"></script>

        <script src="src/js/utilizador.js"></script>

        <script src="src/js/header-user.js"></script>

        <script src="src/js/exercicio_pt.js"></script>

        <script src="src/js/login.js"></script>

</body>

</html>