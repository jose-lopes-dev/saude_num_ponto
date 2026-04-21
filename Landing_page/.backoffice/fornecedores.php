<?php require_once __DIR__ . '/src/auth/auth_admin.php'; ?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
  data-sidebar-image="none" data-bs-theme="dark" data-body-image="img-1" data-preloader="disable">

<head>

  <meta charset="utf-8" />
  <title>Fornecedores | Saúde Num Ponto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
  <meta content="Themesbrand" name="author" />
  <!-- App favicon -->
  <link rel="shortcut icon" href="assets/images/Logo.png ">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- DataTables JS -->
  <script src="src/js/lib/datatables.min.js"></script>
  <script src="src/js/lib/dataTables.buttons.min.js"></script>
  <script src="src/js/lib/jszip.min.js"></script>
  <script src="src/js/lib/pdfmake.min.js"></script>
  <script src="src/js/lib/fonts.js"></script>
  <script src="src/js/lib/buttons.html5.min.js"></script>
  <script src="src/js/lib/buttons.print.min.js"></script>
  <link rel="stylesheet" href="src/css/buttons.dataTables.min.css">
  <script src="src/js/lib/dataTables.bootstrap5.min.js"></script>
  <link rel="stylesheet" href="src/css/dataTables.bootstrap5.min.css">

  <!-- Select2 CSS -->
  <link href="src/css/select2.min.css" rel="stylesheet" />

  <!-- Sweet Alert js-->
  <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

  <!-- Sweet Alert css-->
  <link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
  <!-- Select2 JS -->
  <script src="src/js/lib/select2.min.js"></script>

  <!-- Bootstrap Js -->
  <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Simplebar Js -->
  <script src="assets/libs/simplebar/simplebar.min.js"></script>
  <!-- Node-waves Js -->
  <script src="assets/libs/node-waves/waves.min.js"></script>

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
  <!-- custom Css-->
  <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

  <link href="src/css/fornecedor.css" rel="stylesheet" type="text/css" />


</head>

<body>


  <!-- Begin page -->
  <div id="layout-wrapper">

    <header id="page-topbar">
      <div class="layout-width">
        <div class="navbar-header">
          <div class="d-flex">
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
                id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bx bx-search fs-22"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                aria-labelledby="page-header-search-dropdown">
                <form class="p-3">
                  <div class="form-group m-0">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Pesquisar..."
                        aria-label="Recipient's username">
                      <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
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
            <a href="fornecedores.php" class="nav-link menu-link active">
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
              <h4 class="mb-sm-0">Fornecedores</h4>
            </div>
          </div>
        </div>
        <!-- end page title -->

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title mb-0">Gestão dos Fornecedores</h4>
              </div>

              <div class="card-body">
                <div class="listjs-table" id="ListaFornecedores">

                  <!-- Linha de botões e filtro -->
                  <div class="row g-3 mb-3 align-items-end">
                    <div class="col-sm-auto">
                      <button type="button" class="btn btn-primary add-btn" data-bs-toggle="modal" id="create-btn"
                        data-bs-target="#showModal">
                        <i class="ri-add-line align-bottom me-1"></i> Adicionar
                      </button>
                    </div>

                    <!-- Filtro por mês -->
                    <div class="col-sm-3 ms-auto">
                      <label for="filtroMes" class="form-label mb-1">Filtrar por mês</label>
                      <select id="filtroMes" class="form-select filtro-mes" onchange="filtrarPorMes()">
                        <option value="">Todos</option>
                        <option value="01">Janeiro</option>
                        <option value="02">Fevereiro</option>
                        <option value="03">Março</option>
                        <option value="04">Abril</option>
                        <option value="05">Maio</option>
                        <option value="06">Junho</option>
                        <option value="07">Julho</option>
                        <option value="08">Agosto</option>
                        <option value="09">Setembro</option>
                        <option value="10">Outubro</option>
                        <option value="11">Novembro</option>
                        <option value="12">Dezembro</option>
                      </select>
                    </div>
                  </div>

                  <!-- Tabela -->
                  <div id="TabelaFornecedores">
                    <table id="tabelaFornecedores" class="table table-striped table-hover align-middle">
                      <thead class="table-dark">
                        <tr>
                          <th>Fornecedor</th>
                          <th>Descrição</th>
                          <th>Total Débito</th>
                          <th>Total Crédito</th>
                          <th>Saldo</th>
                          <th>Data</th>
                          <th>Editar</th>
                          <th>Concluir</th>
                        </tr>
                      </thead>
                      <tbody id="listagemFornecedores">
                        <!-- Preenchido via JS -->
                      </tbody>
                    </table>
                  </div>

                  <!-- Modal Registo -->
                  <div class="modal fade" id="showModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header bg-light">
                          <h5 class="modal-title">Registar Fornecedor</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form autocomplete="off">
                          <div class="modal-body">
                            <div class="mb-3">
                              <label for="fornecedor" class="form-label">Fornecedor</label>
                              <input type="text" id="fornecedor" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label for="descricao" class="form-label">Descrição</label>
                              <input type="text" id="descricao" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label for="totaldebito" class="form-label">Total Débito</label>
                              <input type="number" id="totaldebito" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label for="total_credito" class="form-label">Total Crédito</label>
                              <input type="number" id="total_credito" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label for="saldo" class="form-label">Saldo</label>
                              <input type="number" id="saldo" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label for="data" class="form-label">Data</label>
                              <input type="date" id="data" class="form-control" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary"
                              onclick="registaFornecedor()">Registar</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <!-- Modal Editar -->
                  <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header bg-light p-3">
                          <h5 class="modal-title">Editar Fornecedor</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <form id="formEditar" autocomplete="off">
                          <div class="modal-body">
                            <div class="mb-3">
                              <label for="editFornecedor" class="form-label">Fornecedor</label>
                              <input type="text" id="editFornecedor" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label for="editDescricao" class="form-label">Descrição</label>
                              <input type="text" id="editDescricao" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label for="editTotalDebito" class="form-label">Total Débito</label>
                              <input type="number" id="editTotalDebito" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label for="editTotalCredito" class="form-label">Total Crédito</label>
                              <input type="number" id="editTotalCredito" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label for="editSaldo" class="form-label">Saldo</label>
                              <input type="number" id="editSaldo" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label for="editData" class="form-label">Data</label>
                              <input type="date" id="editData" class="form-control" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                </div> <!-- /listjs-table -->
              </div> <!-- /card-body -->
            </div> <!-- /card -->
          </div> <!-- /col -->
        </div> <!-- /row principal -->

      </div> <!-- /container-fluid -->
    </div> <!-- /page-content -->

    <!-- ======= Footer ======= -->
    <footer class="footer border-top">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <script>document.write(new Date().getFullYear())</script> © Saúde Num Ponto.
          </div>
        </div>
      </div>
    </footer>
    <!-- ======= End Footer ======= -->

  </div> <!-- /main-content -->

  <!--start back-to-top-->
  <button onclick="topFunction()" class="btn btn-primary btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
  </button>
  <!--end back-to-top-->

  <!-- JAVASCRIPT -->
  
  <script src="assets/libs/simplebar/simplebar.min.js"></script>
  <script src="assets/libs/node-waves/waves.min.js"></script>
  <script src="assets/libs/feather-icons/feather.min.js"></script>
  <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
  <script src="assets/js/plugins.js"></script>

  <!-- App js -->
  <script src="assets/js/app.js"></script>

  <!-- prismjs plugin -->
  <script src="assets/libs/prismjs/prism.js"></script>
  <script src="assets/libs/list.js/list.min.js"></script>
  <script src="assets/libs/list.pagination.js/list.pagination.min.js"></script>

  <!-- Sweet Alerts js -->
  <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
  <script src="assets/js/pages/sweetalerts.init.js"></script>

  <script src="src/js/utilizador.js"></script>

  <script src="src/js/notificacoesGlobal.js"></script>

  <script src="src/js/header-user.js"></script>

  <script src="src/js/fornecedor.js"></script>

  <script src="src/js/login.js"></script>

</body>
</html>
