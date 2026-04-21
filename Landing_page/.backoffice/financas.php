<?php require_once __DIR__ . '/src/auth/auth_admin.php'; ?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-bs-theme="dark" data-body-image="img-1" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Financas | Saúde Num Ponto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />

    <!--Logo AIO -->
    <link rel="shortcut icon" href="assets/images/Logo.png">
    <link rel="stylesheet" href="src/css/style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Boxicons CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css">
    <!-- Remixicon CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.4.0/remixicon.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/datatables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/pt-BR.js"></script>
    <!-- Parsley JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.
9.2/parsley.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Sweet Alert css-->
    <link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="src/css/sidebar.css">

    <link rel="stylesheet" href="src/css/financas.css">

    <link rel="stylesheet" href="src/css/sweetalertFinalVersion.css">
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
                        <a href="financas.php" class="nav-link menu-link active">
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

    </div>

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
                            <h4 class="mb-sm-0">Finanças</h4>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <!-- start page title -->

                <!-- container-fluid -->

                <!-- DASHBOARD -->

                <div class="container-fluid my-4">
                    <!-- Cards KPI -->
                    <div class="row g-2 mb-4" id="kpi-row">
                        <!-- Card 1 -->
                        <div class="col-6 col-md-3">
                            <div class="card text-center p-2 bg-primary">
                                <div class="card-body">
                                    <h6>Valor do Empréstimo</h6>
                                    <h4 id="k_valor_emprestimo">0 €</h4>
                                </div>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="col-6 col-md-2">
                            <div class="card text-center p-2 bg-success">
                                <div class="card-body">
                                    <h6>Valor Amortizado</h6>
                                    <h4 id="k_valor_pago">0 €</h4>
                                </div>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="col-6 col-md-3">
                            <div class="card text-center p-2 bg-info-subtle">
                                <div class="card-body">
                                    <h6>Próxima Prestação</h6>
                                    <h4 id="k_proxima_prestacao">0 €</h4>
                                    <small id="k_proxima_data" class="d-block text-muted"></small>
                                </div>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="col-6 col-md-2">
                            <div class="card text-center p-2 bg-danger">
                                <div class="card-body">
                                    <h6>Capital em Dívida</h6>
                                    <h4 id="k_valor_por_pagar">0 €</h4>
                                </div>
                            </div>
                        </div>
                        <!-- Card 5 -->
                        <div class="col-6 col-md-2">
                            <div class="card text-center p-2 bg-danger-subtle">
                                <div class="card-body">
                                    <h6>Juros Pagos</h6>
                                    <h4 id="k_juros_pagos">0 €</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="row mb-4">
                        <div class="col-lg-8">
                            <div class="card p-3">
                                <h6>Amortização & Juros (Anual)</h6>
                                <canvas id="chartLine" height="220"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card p-3">
                                <h6>Progresso do Pagamento</h6>
                                <canvas id="chartPie" height="220"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela de Prestações -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card p-3">
                                <h6>Prestações</h6>
                                <div class="table-responsive">
                                    <table id="prestacoesTable" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Data Prevista</th>
                                                <th>Prestação (€)</th>
                                                <th>Juros (€)</th>
                                                <th>Amortização (€)</th>
                                                <th>Saldo Devedor (€)</th>
                                                <th>Estado</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody id="prestacoesBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIM DASHBOARD -->




            </div><!-- container-fluid -->

        </div><!-- End Page-content -->




        <!-- Footer -->
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

    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-primary btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->


    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Layout e plugins -->
    <script src="assets/js/layout.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="src/js/utilizador.js"></script>

    <script src="src/js/notificacoesGlobal.js"></script>

    <script src="src/js/header-user.js"></script>

    <!-- Finanças JS -->
    <script src="src/js/financas.js"></script>

    <!-- ChartJS Init -->
    <script src="assets/js/pages/chartjs-financas.init.js"></script>

    <!-- App JS -->
    <script src="assets/js/app.js"></script>

    <script src="src/js/login.js"></script>    

</body>

</html>