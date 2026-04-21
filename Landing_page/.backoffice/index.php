<?php require_once __DIR__ . '/src/auth/auth_admin.php'; ?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
  data-sidebar-image="none" data-bs-theme="dark" data-body-image="img-1" data-preloader="disable">

<head>
  <meta charset="utf-8" />
  <title>Dashboard | Saúde Num Ponto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
  <meta content="Themesbrand" name="author" />
  <!-- App favicon -->
  <link rel="shortcut icon" href="assets/images/Logo.png">

  <!-- jsvectormap css -->
  <link href="assets/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />

  <!--Swiper slider css-->
  <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />

  <!-- Bootstrap Css -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- Icons Css -->
  <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
  <!-- App Css-->
  <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />

  <link rel="stylesheet" href="src/css/sidebar.css">

  <!-- custom Css-->
  <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

  <link href="src/css/index.css" rel="stylesheet" type="text/css" />

    <link href="src/css/global.css" rel="stylesheet" type="text/css" />

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
            <a href="index.php" class="nav-link menu-link active">
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
              <h4 class="mb-sm-0">Dashboard</h4>

            

            </div>
          </div>
        </div>
        <!-- end page title -->

        <!-- Cards -->
        <div class="row">
          <!-- SALDO TOTAL -->
          <div class="col-xl-3 col-md-6">
            <div class="pt-card">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <p class="text-uppercase fw-medium text-muted mb-0">Saldo Total</p>
                  <h5 id="percentualSaldo" class="fs-14 mb-0 text-danger">0.00%</h5>
                </div>
                <div class="mt-4">
                  <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="saldoTotal">€0.00</h4>
                </div>
              </div>
            </div>
          </div>

          <!-- CUSTOS -->
          <div class="col-xl-3 col-md-6">
            <div class="pt-card">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <p class="text-uppercase fw-medium text-muted mb-0">Custos</p>
                  <h5 id="percentualCustos" class="fs-14 mb-0 text-danger">0.00%</h5>
                </div>
                <div class="mt-4">
                  <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="custosSetembro">€0.00</h4>
                </div>
              </div>
            </div>
          </div>

          <!-- RENDIMENTOS -->
          <div class="col-xl-3 col-md-6">
            <div class="pt-card">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <p class="text-uppercase fw-medium text-muted mb-0">Rendimentos</p>
                  <h5 id="percentualRendimentos" class="fs-14 mb-0 text-success">0.00%</h5>
                </div>
                <div class="mt-4">
                  <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="rendimentosSetembro">€0.00</h4>
                </div>
              </div>
            </div>
          </div>

          <!-- RAI -->
          <div class="col-xl-3 col-md-6">
            <div class="pt-card">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <p class="text-uppercase fw-medium text-muted mb-0">RAI</p>
                  <h5 id="percentualRAI" class="fs-14 mb-0 text-danger">0.00%</h5>
                </div>
                <div class="mt-4">
                  <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="raiSetembro">€0.00</h4>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Gráfico + Eventos -->
        <div class="row">
          <!-- Gráfico -->
          <div class="col-12 col-xxl-6 mb-4 mb-xxl-0">
            <div class="card pt-card-lg pt-topbar-card mt-2">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Saúde Financeira</h4>
              </div>
              <div class="card-body">
                <canvas id="lineChart" class="chartjs-chart"></canvas>
              </div>
            </div>
          </div>

          <!-- Eventos -->
          <div class="col-12 col-xxl-6">
            <div class="card pt-card-lg pt-topbar-card mt-2" id="cardDeclarativas">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                  <i class="ri-calendar-check-line me-1"></i> Obrigações Declarativas
                </h4>
                <button type="button" class="btn btn-primary add-btn" data-bs-toggle="modal"
                  data-bs-target="#modalNovoEvento">
                  <i class="ri-add-line align-bottom me-1"></i> Adicionar
                </button>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="tabelaEventos" class="table table-striped mb-0">
                    <thead>
                      <tr>
                        <th>Título</th>
                        <th class="d-none d-xxl-table-cell">Descrição</th>
                        <th>Data Limite</th>
                        <th>Editar</th>
                        <th>Concluir</th>
                      </tr>
                    </thead>
                    <tbody id="listagemEventos"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagamentos -->
        <div class="row mt-4">
          <div class="col-12">
            <div class="card pt-card-lg pt-topbar-card mt-2" id="cardPagamentos">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"><i class="ri-bill-line me-1"></i> Pagamentos</h4>
                <button type="button" class="btn btn-primary add-btn" data-bs-toggle="modal"
                  data-bs-target="#modalTarefa">
                  <i class="ri-add-line align-bottom me-1"></i> Adicionar Pagamento
                </button>
              </div>

              <div class="card-body">
                <div class="p-3 border border-dashed rounded mb-3">
                  <form id="formFiltro">
                    <div class="row g-3 align-items-end">
                      <div class="col-12 col-md-5">
                        <div class="search-box">
                          <input type="text" id="pesquisaTexto" class="form-control search bg-light border-light"
                            placeholder="Pesquisar tarefas..." />
                          <i class="ri-search-line search-icon"></i>
                        </div>
                      </div>
                      <div class="col-12 col-md-5">
                        <input type="text" class="form-control bg-light border-light" id="date-range"
                          data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true"
                          placeholder="Intervalo de datas" />
                      </div>
                      <div class="col-12 col-md-2">
                        <button type="button" class="btn btn-primary w-100" onclick="filtrarTarefas();">
                          <i class="ri-equalizer-fill me-1 align-bottom"></i> Filtrar
                        </button>
                      </div>
                    </div>
                  </form>
                </div>

                <div class="table-responsive table-card mb-0">
                  <table class="table table-hover mb-0 w-100" id="tasksTable">
                    <thead class="table-dark">
                      <tr>
                        <th class="d-none d-xxl-table-cell">ID</th>
                        <th>Tipo de Obrigação</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data de Vencimento</th>
                        <th class="d-none d-xxl-table-cell">Data de Pagamento</th>
                        <th>Estado</th>
                        <th class="text-center">Editar</th>
                        <th class="text-center">Concluir</th>
                      </tr>
                    </thead>
                    <tbody id="listagemTarefas"></tbody>
                  </table>
                </div>

                <div class="noresult d-none text-center p-4">
                  <lord-icon src="src/json/gsqxdxog.json" trigger="loop" colors="primary:#8c68cd,secondary:#4788ff"
                    class="icon-75"></lord-icon>
                  <h5 class="mt-2">Nenhuma tarefa encontrada</h5>
                  <p class="text-muted mb-0">Não foram encontrados resultados para a pesquisa atual.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ====================== -->
        <!-- Modais -->
        <!-- ====================== -->

        <!-- Modal Novo Evento -->
        <div class="modal fade" id="modalNovoEvento" tabindex="-1" aria-labelledby="modalNovoEventoLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0">
              <div class="modal-header p-3 bg-primary-subtle">
                <h5 class="modal-title" id="modalNovoEventoLabel">Adicionar Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
              </div>
              <form id="formNovoEvento" autocomplete="off">
                <div class="modal-body">
                  <div class="row g-3">
                    <div class="col-lg-6">
                      <label for="evt_titulo" class="form-label">Título</label>
                      <input type="text" id="evt_titulo" name="titulo" class="form-control" required />
                    </div>
                    <div class="col-12">
                      <label for="evt_descricao" class="form-label">Descrição</label>
                      <textarea id="evt_descricao" name="descricao" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-lg-6">
                      <label for="evt_data_fim" class="form-label">Data Limite</label>
                      <input type="datetime-local" id="evt_data_fim" name="data_fim" class="form-control" required />
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-primary">Registar</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Modal Editar Evento -->
        <div class="modal fade" id="modalEditarEvento" tabindex="-1" aria-labelledby="modalEditarEventoLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0">
              <div class="modal-header p-3 bg-primary-subtle">
                <h5 class="modal-title" id="modalEditarEventoLabel">Editar Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
              </div>
              <form id="formEditarEvento" autocomplete="off">
                <div class="modal-body">
                  <input type="hidden" id="edit_id" name="id" />
                  <div class="row g-3">
                    <div class="col-lg-6">
                      <label for="edit_titulo" class="form-label">Título</label>
                      <input type="text" id="edit_titulo" name="titulo" class="form-control" required />
                    </div>
                    <div class="col-12">
                      <label for="edit_descricao" class="form-label">Descrição</label>
                      <textarea id="edit_descricao" name="descricao" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-lg-6">
                      <label for="edit_data_fim" class="form-label">Data Limite</label>
                      <input type="datetime-local" id="edit_data_fim" name="data_fim" class="form-control" required />
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
              </form>
            </div>
          </div>
        </div>


        <!-- Modal Tarefa -->
        <div class="modal fade zoomIn" id="modalTarefa" tabindex="-1" aria-labelledby="modalTarefaLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0">
              <div class="modal-header p-3 bg-primary-subtle">
                <h5 class="modal-title" id="modalTarefaLabel">Criar / Editar Tarefa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
              </div>
              <form id="formTarefa" autocomplete="off">
                <div class="modal-body">
                  <input type="hidden" id="taskId" />
                  <div class="row g-3">
                    <div class="col-lg-12">
                      <label for="tipoObrigacao" class="form-label">Tipo de Obrigação</label>
                      <select id="tipoObrigacao" class="form-control" required></select>
                    </div>
                    <div class="col-lg-12">
                      <label for="descricao" class="form-label">Descrição</label>
                      <input type="text" id="descricao" class="form-control" placeholder="Descrição da tarefa"
                        required />
                    </div>
                    <div class="col-lg-12">
                      <label for="valor" class="form-label">Valor (€)</label>
                      <input type="number" id="valor" class="form-control" placeholder="Valor (€)" step="0.01" min="0"
                        required />
                    </div>
                    <div class="col-lg-6">
                      <label for="dataVencimento" class="form-label">Data de Vencimento</label>
                      <input type="date" id="dataVencimento" class="form-control" required />
                    </div>
                    <div class="col-lg-6">
                      <label for="dataPagamento" class="form-label">Data de Pagamento</label>
                      <input type="date" id="dataPagamento" class="form-control" required />
                    </div>
                    <div class="col-lg-12">
                      <label for="estado" class="form-label">Estado</label>
                      <select id="estado" class="form-control" required></select>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="submit" class="btn btn-success">Guardar</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>


      </div>
      <!-- end container-fluid -->
    </div>
    <!-- end page-content -->

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
  <!-- end main-content -->

  <!-- Back-to-top -->
  <button onclick="topFunction()" class="btn btn-primary btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
  </button>



  <!-- JAVASCRIPT -->
  <!-- jQuery -->
  <script src="src/js/lib/jquery3.6.0.min.js"></script>

  <!-- Bootstrap e plugins -->
  <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/libs/simplebar/simplebar.min.js"></script>
  <script src="assets/libs/node-waves/waves.min.js"></script>
  <script src="assets/libs/feather-icons/feather.min.js"></script>
  <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
  <script src="assets/js/plugins.js"></script>

  <!-- DataTables -->
  <script src="src/js/lib/jszip.min.js"></script>
  <script src="src/js/lib/pdfmake.min.js"></script>
  <script src="src/js/lib/fonts.js"></script>

  <!-- Outros gráficos e mapas -->
  <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
  <script src="assets/libs/swiper/swiper-bundle.min.js"></script>

  <script src="assets/libs/chart.js/chart.umd.js"></script>
  <script src="assets/js/layout.js"></script>
  <script src="assets/js/app.js"></script>

  <!-- SweetAlert2 -->
  <script src="src/js/lib/sweetalert2.js"></script>

  <link rel="stylesheet" href="src/css/sweetalertFinalVersion.css">

  <script src="src/js/utilizador.js"></script>

  <script src="src/js/notificacoesGlobal.js"></script>

   <script src="src/js/header-user.js"></script>

  <!-- Tarefa JS deve vir por último -->
  <script src="src/js/tarefa.js"></script>

  <script src="src/js/index.js"></script>

  <script src="src/js/eventos.js"></script>

  <script src="src/js/login.js"></script>


</body>

</html>