<?php require_once __DIR__ . '/src/auth/auth_psicologo.php'; ?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-bs-theme="dark" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Dashboard | Saúde Num Ponto</title>
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

    <link rel="stylesheet" href="src/css/sweetalertFinalVersion.css">

    <link rel="stylesheet" href="src/css/sidebar.css">
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

    <link href="src/css/sweetalert.css" rel="stylesheet" type="text/css" />

    <link href="src/css/dashboard_cliente.css" rel="stylesheet" type="text/css" />

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
                            <span class="topbar-title">Dashboard</span>
                            <ol class="breadcrumb breadcrumb-sm mb-0">
                                <li class="breadcrumb-item">
                                    <a href="dashboard_cliente.php">Cliente</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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
                                        </span>
                                    </span>

                                </span>
                            </button>


                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">
                                    Bem-Vindo <span id="welcome-username"></span>!
                                </h6>

                                <a class="dropdown-item" href="perfil_psicologo.php">
                                    <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i>
                                    <span class="align-middle">Perfil</span>
                                </a>

                                <a class="dropdown-item" href="suporte_psicologo.php">
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


        <!-- Sidebar -->
        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <div class="navbar-brand-box">
                <!-- Logo -->
                <a href="dashboard_psicologo.php" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo_bw_recolor_precisa.png" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo_bw_recolor_precisa.png" alt="" height="30">
                    </span>
                </a>
                <a href="dashboard_psicologo.php" class="logo logo-light">
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
                            <a href="dashboard_psicologo.php" class="nav-link menu-link active">
                                <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        </li>
                        </li>
                        <li class="nav-item">
                            <a href="consulta_psicologo.php" class="nav-link menu-link">
                                <i class="ri-stethoscope-line"></i> <span>Consulta</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="comissoes_psicologo.php" class="nav-link menu-link">
                                <i class="ri-percent-line"></i> <span>Comissões</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="vendas_psicologo.php" class="nav-link menu-link">
                                <i class="ri-money-euro-circle-line"></i> <span>Vendas</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="calendario_psicologo.php" class="nav-link menu-link">
                                <i class="ri-calendar-line"></i> <span>Calendário</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="chat_psicologo.php" class="nav-link menu-link">
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

                    <div class="row mb-3">
                        <div class="col-12">
                            <div
                                class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent">
                                <h4 class="mb-sm-0 fw-bold">Dashboard</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">

                        <div class="col-lg-8">

                            <div class="row g-3 mb-4">

                                <div class="col-md-4">
                                    <div class="card shadow-sm p-3 text-center rounded-3">
                                        <small class="text-muted">Pacientes acompanhados</small>
                                        <h3 class="fw-bold mb-0" id="kpi_pacientes">--</h3>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card shadow-sm p-3 text-center rounded-3">
                                        <small class="text-muted">Sessões Pendentes</small>
                                        <h3 class="fw-bold mb-0" id="kpi_pendentes">--</h3>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card shadow-sm p-3 text-center rounded-3">
                                        <small class="text-muted">Sessões Hoje</small>
                                        <h3 class="fw-bold mb-0" id="kpi_hoje">--</h3>
                                    </div>
                                </div>

                            </div>

                            <div class="card shadow-sm p-4 rounded-4 mb-4">
                                <h5 class="fw-bold mb-2">Sessões por Semana</h5>
                                <p class="text-muted">Resumo das sessões deste mês.</p>
                                <canvas id="graficoSemanal" height="200"></canvas>
                            </div>

                            <div class="card shadow-sm p-4 rounded-4">
                                <h5 class="fw-bold mb-3">Sessões de Hoje</h5>

                                <ul class="list-group list-group-flush" id="sessoes_hoje">
                                </ul>
                            </div>

                        </div>

                        <div class="col-lg-4">

                            <div class="card shadow-sm p-4 rounded-4 mb-4">
                                <h6 class="fw-bold mb-3">Últimos Pacientes Atendidos</h6>

                                <div class="list-group" id="ultimos_pacientes">
                                </div>
                            </div>

                            <div class="card shadow-sm p-4 rounded-4">
                                <h6 class="fw-bold mb-3">Sessões Pendentes</h6>

                                <div class="list-group" id="lista_pendentes_dashboard">
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>


        <!-- FOOTER -->
        <footer class="footer border-top py-3 mt-4">
            <div class="container-fluid text-muted small">
                ©
                <script>document.write(new Date().getFullYear())</script> Saude Num Ponto.
            </div>
        </footer>
    </div>



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-primary btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->




    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script src="assets/libs/chart.js/chart.umd.js"></script>

    <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <script src="src/js/lib/jquery3.6.0.min.js"></script>

    <script src="src/js/notificacoesGlobal.js"></script>

    <script src="src/js/utilizador.js"></script>

    <script src="src/js/login.js"></script>

    <script src="src/js/header-user.js"></script>

    <script src="src/js/dashboard_psicologo.js"></script>
</body>

</html>