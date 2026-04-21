<?php require_once __DIR__ . '/src/auth/auth_cliente.php'; ?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-bs-theme="dark" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Plano De Treino | Saúde Num Ponto</title>
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="src/css/sweetalertFinalVersion.css">

    <link href="src/css/sweetalert.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="src/css/sidebar.css">
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="src/css/global.css" />

    <link href="src/css/configuracoes_cliente.css" rel="stylesheet" type="text/css" />

    <link href="src/css/notificacoes.css" rel="stylesheet" type="text/css" />

    <link href="src/css/planotreino_cliente.css" rel="stylesheet" type="text/css" />

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

                        <div class="topbar-page-title d-none d-lg-flex flex-column ms-3">
                            <span class="topbar-title">Plano de Treino</span>
                            <ol class="breadcrumb breadcrumb-sm mb-0">
                                <li class="breadcrumb-item">
                                    <a href="dashboard_cliente.php">Cliente</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Plano de Treino</li>
                            </ol>
                        </div>

                        <!-- PESQUISA -->
                        <form class="app-search topbar-search d-none d-md-block ms-4">
                            <div class="position-relative">
                                <input type="text"
                                    class="form-control"
                                    placeholder="Pesquisar treinos, planos, consultas..."
                                    autocomplete="off"
                                    id="search-options"
                                    value="">
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
                                        <a href="index.php" class="btn btn-soft-primary btn-sm rounded-pill">Plano treino <i class="mdi mdi-magnify ms-1"></i></a>
                                        <a href="index.php" class="btn btn-soft-primary btn-sm rounded-pill">Consultas <i class="mdi mdi-magnify ms-1"></i></a>
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
                                        <h6 class="m-0 fs-16 fw-semibold text-white">
                                            Notificações
                                        </h6>
                                    </div>
                                </div>

                                <div class="py-2 ps-2">
                                    <div data-simplebar style="max-height: 300px;" class="pe-2">
                                        <div id="notification-list">
                                            <!-- JS injecta aqui -->
                                        </div>
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

                                <a class="dropdown-item" href="perfil_cliente.php">
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
            </div>
        </header>


        <!-- Sidebar -->
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
                <a href="dashboard_cliente.php" class="logo logo-light">
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
                            <a href="dashboard_cliente.php" class="nav-link menu-link">
                                <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="planotreino_cliente.php" class="nav-link menu-link active">
                                <i class="ri-run-line"></i> <span>Plano de Treino</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="plano_alimentar.php" class="nav-link menu-link">
                                <i class="ri-restaurant-2-line"></i> <span>Plano Alimentar</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="treinos.php" class="nav-link menu-link">
                                <i class="ri-video-line"></i> <span>Treinos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="consulta_cliente.php" class="nav-link menu-link">
                                <i class="ri-stethoscope-line"></i> <span>Consulta</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="aulas_cliente.php" class="nav-link menu-link">
                                <i class="ri-video-chat-line"></i> <span>Aulas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="calendario_cliente.php" class="nav-link menu-link">
                                <i class="ri-calendar-line"></i> <span>Calendário</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="chat_cliente.php" class="nav-link menu-link">
                                <i class="ri-chat-3-line"></i> <span>Chat</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="planosSistema.php" class="nav-link menu-link">
                                <i class="ri-folder-line"></i> <span>Planos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="marketplace_cliente.php" class="nav-link menu-link">
                                <i class="ri-shopping-cart-2-line"></i> <span>Marketplace</span>
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
            <div class="page-content">
                <div class="container-fluid pt-page">

                    <div class="row">

                        <!-- BUILDER -->
                        <div class="col-12 col-lg-7 mb-4">
                            <div class="pt-card-lg p-4 shadow-sm rounded-4 h-100 pt-form-card">
                                <h5 class="mb-3 fw-bold">
                                    <i class="ri-edit-2-line me-2"></i> Criar Plano (Cliente)
                                </h5>

                                <div class="mb-3">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" id="tituloPlano"
                                        placeholder="Ex: Hipertrofia 4x/semana">
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label">Dia</label>
                                        <select id="diaSemana" class="form-control">
                                            <option value="1">Segunda</option>
                                            <option value="2">Terça</option>
                                            <option value="3">Quarta</option>
                                            <option value="4">Quinta</option>
                                            <option value="5">Sexta</option>
                                            <option value="6">Sábado</option>
                                            <option value="7">Domingo</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Equipamento</label>
                                        <select id="f_equip" class="form-control">
                                            <option value="">Todos</option>
                                            <option value="peso_corporal">Peso corporal</option>
                                            <option value="halteres">Halteres</option>
                                            <option value="barra">Barra</option>
                                            <option value="cabos">Cabos</option>
                                            <option value="maquina">Máquina</option>
                                            <option value="banco">Banco</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-2 mt-2">
                                    <div class="col-6">
                                        <label class="form-label">Grupo muscular (clicar no boneco)</label>
                                        <select id="f_grupo" class="form-control">
                                            <option value="">Todos</option>
                                            <option value="peito">Peito</option>
                                            <option value="costas">Costas</option>
                                            <option value="ombros">Ombros</option>
                                            <option value="biceps">Bíceps</option>
                                            <option value="triceps">Tríceps</option>
                                            <option value="antebraco">Antebraços</option>
                                            <option value="gluteos">Glúteos</option>
                                            <option value="pernas">Pernas</option>
                                            <option value="core">Core</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Escolher exercício</label>
                                        <select id="selExercicio" class="form-control"></select>
                                    </div>
                                </div>

                                <div class="row g-2 mt-2">
                                    <div class="col-4">
                                        <label class="form-label">Séries</label>
                                        <input type="number" class="form-control" id="inSeries" min="1" value="3">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Reps</label>
                                        <input type="text" class="form-control" id="inReps" value="8-12">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Desc (s)</label>
                                        <input type="number" class="form-control" id="inDesc" min="0" value="180">
                                    </div>
                                </div>
                                <div class="row g-2 mt-4 justify-content-center">
                                    <div class="col-4 d-grid">
                                        <button class="btn pt-btn" id="btnAddEx">+ Adicionar ao dia</button>
                                    </div>
                                    <div class="col-4 d-grid">
                                        <button class="btn pt-btn" id="btnGuardarPlano">Guardar Plano</button>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="pt-resumo-wrap">
                                    <h6 class="fw-bold">Resumo do dia</h6>
                                    <div id="diaResumo" class="mt-2">
                                        <p class="text-muted mb-0">Sem exercícios ainda.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- LISTA PLANOS -->
                        <div class="col-12 col-lg-5 mb-4">
                            <div class="pt-card-lg p-4 shadow-sm rounded-4 h-100">
                                <div class="bodymap-wrap mt-3" id="bodyMap">
                                    <?php include __DIR__ . "/src/imagens/body_front.svg"; ?>
                        
                                    <?php include __DIR__ . "/src/imagens/body_back.svg"; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <!-- PLANOS DO CLIENTE -->
                        <div class="col-12 col-lg-4">
                            <div class="pt-card-lg p-4 shadow-sm rounded-4 h-100">
                                <h5 class="mb-3 fw-bold">
                                    <i class="ri-run-line me-2"></i> Os Meus Planos de Treino
                                </h5>
                                <div id="listaPlanosCliente">
                                    <p class="text-muted">A carregar...</p>
                                </div>
                            </div>
                        </div>

                        <!-- FICHEIROS DO PT -->
                        <div class="col-12 col-lg-4">
                            <div class="pt-card-lg p-4 shadow-sm rounded-4 h-100">
                                <h5 class="mb-3 fw-bold">
                                    <i class="ri-file-download-line me-2"></i> Ficheiros do PT
                                </h5>

                                <div id="listaFicheirosPT">
                                    <p class="text-muted">A carregar...</p>
                                </div>
                            </div>
                        </div>

                        <!-- PLANOS DO PT -->
                        <div class="col-12 col-lg-4">
                            <div class="pt-card-lg p-4 shadow-sm rounded-4 h-100">
                                <h5 class="mb-3 fw-bold">
                                    <i class="ri-file-list-2-line me-2"></i> Planos do PT
                                </h5>

                                <div id="listaPlanosPT">
                                    <p class="text-muted">A carregar...</p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Modal Detalhes -->
            <div class="modal fade" id="modalPlano" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalhes do Plano</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Título:</strong> <span id="mTitulo"></span></p>
                            <hr>
                            <div id="mCorpo"></div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
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
    </div>

    <!-- END layout-wrapper -->

    <script>
        const USER_SESSION = {
            id: <?php echo json_encode($_SESSION['id']); ?>,
            tipo: <?php echo json_encode($_SESSION['tipo']); ?>,
            cliente_id: <?php echo json_encode($_SESSION['cliente_id']); ?>
        };
    </script>


    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script src="src/js/lib/jquery3.6.0.min.js"></script>

    <script src="assets/libs/chart.js/chart.umd.js"></script>

    <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <script src="src/js/lib/select2.min.js"></script>

    <script src="src/js/utilizador.js"></script>

    <script src="src/js/header-user.js"></script>

    <script src="src/js/notificacoesGlobal.js"></script>

    <script src="src/js/planotreino_cliente.js"></script>

    <script src="src/js/login.js"></script>

</body>

</html>