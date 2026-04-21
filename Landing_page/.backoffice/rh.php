<?php require_once __DIR__ . '/src/auth/auth_admin.php'; ?>

<!DOCTYPE html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
  data-sidebar-image="none" data-bs-theme="dark" data-body-image="img-1" data-preloader="disable">

<head>
  <meta charset="utf-8" />
  <title>Recursos Humanos | Saúde Num Ponto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
  <meta content="Themesbrand" name="author" />

  <!--Logo AIO -->
  <link rel="shortcut icon" href="assets/images/Logo.png">

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Sweet Alert css-->
  <link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
  <!-- Bootstrap Css -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- Icons Css -->
  <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
  <!-- App Css-->
  <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />

  <link rel="stylesheet" href="src/css/sidebar.css">

  <link rel="stylesheet" href="src/css/style.css">

  <link rel="stylesheet" href="src/css/sweetalertFinalVersion.css">
  <!-- custom Css-->
  <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
  <link href="src/css/rh.css" rel="stylesheet" type="text/css" />

  <link rel="stylesheet" href="src/css/custom.css">

    <link rel="stylesheet" href="src/css/global.css">

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
          <img src="assets/images/logo_bw_recolor_precisa.png" alt="" height="30" />
        </span>
        <span class="logo-lg">
          <img src="assets/images/logo_bw_recolor_precisa.png" alt="" height="30" />
        </span>
      </a>
      <a href="index.php" class="logo logo-light">
        <span class="logo-sm">
          <img src="assets/images/logo_bw_recolor_precisa.png" alt="" height="30" />
        </span>
        <span class="logo-lg">
          <img src="assets/images/logo_bw_recolor_precisa.png" alt="" height="31" />
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
              <i class="ri-money-dollar-circle-line"></i>
              <span>Custos e Rendimentos</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="rh.php" class="nav-link menu-link active">
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
              <h4 class="mb-sm-0">Recursos Humanos</h4>
            </div>
          </div>
        </div>
        <!-- end page title -->

        <!-- start page title -->
        <div id="rh-page" class="container-fluid">
          <!-- Tabs -->
          <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-rh" type="button" role="tab">
                Prestadores de Serviço / Colaboradores
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-rh-impostos" type="button" role="tab">
                Impostos / RH
              </button>
            </li>
          </ul>

          <div class="tab-content">
            <!-- ===================== Prestadores de Serviço / RH ===================== -->
            <div class="tab-pane fade show active" id="tab-rh" role="tabpanel">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card pt-card-lg pt-topbar-card mt-2">
                    <div class="card-header">
                      <h4 class="card-title mb-0">Gestão dos Recursos Humanos</h4>
                    </div>

                    <!-- start table -->

                    <div class="card-body">
                      <div class="listjs-table" id="ListaPrestadores">
                        <div class="row g-4 mb-3">
                          <div class="col-sm-auto">
                            <button type="button" class="btn btn-primary add-btn" data-bs-toggle="modal" id="create-btn"
                              data-bs-target="#showModal">
                              <i class="ri-add-line align-bottom me-1"></i>
                              Adicionar
                            </button>
                          </div>
                        </div>


                        <div id="TabelaPrestadores">
                          <table id="tabelaPrestadores" class="table table-striped">
                            <thead>
                              <tr>
                                <th>Nome</th>
                                <th>NIF</th>
                                <th>Função</th>
                                <th>Tipo de Contrato</th>
                                <th>Estado</th>
                                <th>Contrato</th>
                                <th>Recibo</th>
                                <th>Editar</th>
                                <th>Remover</th>
                              </tr>
                            </thead>
                            <tbody id="listagemColaboradores">
                              <!-- Linhas preenchidas pelo PHP (modelPrestador.php) -->
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- end card -->
                </div>
                <!-- end col -->
              </div>

              <!-- end col -->
            </div>
            <!-- end row -->

            <!-- Modal Registo de Prestador -->
            <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                  <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Registar Prestador de Serviço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                      id="close-modal"></button>
                  </div>

                  <form class="tablelist-form" id="formPrestador" enctype="multipart/form-data" autocomplete="off">
                    <div class="modal-body">

                      <!-- ===== Dados de Acesso ===== -->
                      <h6 class="fw-bold mb-3">Dados de Acesso</h6>
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="usernamePrestador" class="form-label">Username</label>
                          <input type="text" id="usernamePrestador" class="form-control" placeholder="Insira o username"
                            required />
                          <div class="invalid-feedback">Por favor, insira o username.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                          <label for="emailPrestador" class="form-label">Email</label>
                          <input type="email" id="emailPrestador" class="form-control" placeholder="Insira o email"
                            required />
                          <div class="invalid-feedback">Por favor, insira o email.</div>
                        </div>

                        <div class="col-md-6 d-none">
                          <label for="passwordPrestador" class="form-label">Palavra-passe</label>
                          <input type="password" id="passwordPrestador" class="form-control"
                            placeholder="Insira a palavra-passe" required />
                          <div class="invalid-feedback">Por favor, insira a palavra-passe.</div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="tipo_user" class="form-label">Tipo de utilizador</label>
                        <select id="tipo_user" class="form-select">
                          <option value="1">Admin</option>
                          <option value="2">PT</option>
                          <option value="4">Nutricionista</option>
                          <option value="5">Psicólogo</option>
                        </select>
                      </div>

                      <hr>

                      <!-- ===== Dados Profissionais ===== -->
                      <h6 class="fw-bold mb-3">Dados Profissionais</h6>
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="nomePrestador" class="form-label">Nome Completo</label>
                          <input type="text" id="nomePrestador" class="form-control"
                            placeholder="Insira o nome completo" required />
                          <div class="invalid-feedback">Por favor, insira o nome.</div>
                        </div>

                        <div class="col-md-3 mb-3">
                          <label for="nifPrestador" class="form-label">NIF</label>
                          <input type="text" id="nifPrestador" class="form-control" placeholder="Insira o NIF"
                            required />
                          <div class="invalid-feedback">Por favor, insira o NIF.</div>
                        </div>

                        <div class="col-md-3 mb-3">
                          <label for="contactoPrestador" class="form-label">Contacto</label>
                          <input type="text" id="contactoPrestador" class="form-control" placeholder="Insira o contacto"
                            required />
                          <div class="invalid-feedback">Por favor, insira o contacto.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                          <label for="funcaoPrestador" class="form-label">Função</label>
                          <select class="form-control" id="funcaoPrestador" required></select>
                        </div>

                        <div class="col-md-6 mb-3">
                          <label for="qualificacaoPrestador" class="form-label">Qualificação</label>
                          <input type="text" id="qualificacaoPrestador" class="form-control"
                            placeholder="Ex: Licenciatura em Nutrição" required />
                        </div>

                        <div class="col-md-4 mb-3">
                          <label for="experienciaPrestador" class="form-label">Anos de Experiência</label>
                          <input type="number" id="experienciaPrestador" class="form-control" min="0" placeholder="0"
                            required />
                        </div>

                        <div class="col-md-4 mb-3">
                          <label for="tipoContrato" class="form-label">Tipo de Contrato</label>
                          <select class="form-control" id="tipoContrato" required></select>
                        </div>

                        <div class="col-md-4 mb-3">
                          <label for="estado" class="form-label">Estado</label>
                          <select class="form-control" id="estado" required>
                          </select>
                        </div>

                        <div class="col-md-6 mb-3">
                          <label for="linkContrato" class="form-label">Contrato (PDF)</label>
                          <input type="file" id="linkContrato" name="linkContrato" accept=".pdf" class="form-control"
                            required />
                          <div class="invalid-feedback">Por favor, insira o contrato.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                          <label for="reciboPrestador" class="form-label">Recibo</label>
                          <input type="text" id="reciboPrestador" class="form-control"
                            placeholder="Nenhum recibo enviado ainda" readonly />
                        </div>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="registaPrestador()">Registar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>


            <!-- Modal Editar Prestador -->
            <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Editar Prestador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                  </div>
                  <form id="formEditar" autocomplete="off">
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="editCodigo" class="form-label">Codigo</label>
                        <input type="number" id="editCodigo" class="form-control" required />
                      </div>
                      <div class="mb-3">
                        <label for="editNome" class="form-label">Nome</label>
                        <input type="text" id="editNome" class="form-control" required />
                      </div>

                      <div class="mb-3">
                        <label for="editNif" class="form-label">NIF</label>
                        <input type="text" id="editNif" class="form-control" required />
                      </div>

                      <div class="mb-3">
                        <label for="editFuncao" class="form-label">Função</label>
                        <select id="editFuncao" class="form-control" required>
                          <!-- Opções preenchidas dinamicamente -->
                        </select>
                      </div>

                      <div class="mb-3">
                        <label for="editTipoContrato" class="form-label">Tipo de Contrato</label>
                        <select id="editTipoContrato" class="form-control" required>
                          <!-- Opções preenchidas dinamicamente -->
                        </select>
                      </div>

                      <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" id="editEmail" class="form-control" required />
                      </div>

                      <div class="mb-3">
                        <label for="editEstado" class="form-label">Estado</label>
                        <select id="editEstado" class="form-control" required>
                          <!-- Opções preenchidas dinamicamente -->
                        </select>
                      </div>

                      <div class="mb-3">
                        <label for="editContrato" class="form-label">Contrato (PDF/DOC)</label>
                        <input type="file" id="editContrato" class="form-control" accept=".pdf,.doc,.docx" />
                        <small class="text-muted">Se não selecionar nada, mantém o contrato atual.</small>
                      </div>

                      <div class="mb-3">
                        <label for="editRecibo" class="form-label">Recibo</label>
                        <input type="text" id="editRecibo" class="form-control" placeholder="Nenhum recibo enviado"
                          readonly />
                        <small class="text-muted">
                          O recibo deve ser enviado na tabela principal (botão "Enviar").
                        </small>
                      </div>

                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                      <button type="button" class="btn btn-primary" id="btnGuardar">
                        Guardar
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Modal Enviar Recibo -->
            <div class="modal fade" id="modalRecibo" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Enviar Recibo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                  </div>
                  <form id="formRecibo" enctype="multipart/form-data">
                    <div class="modal-body">
                      <input type="hidden" id="reciboCodigo" name="codigo" />
                      <div class="mb-3">
                        <label for="fileRecibo" class="form-label">Selecione o recibo</label>
                        <input type="file" class="form-control" id="fileRecibo" name="recibo" accept=".pdf,.jpg,.png"
                          required />
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                      </button>
                      <button type="button" class="btn btn-primary" onclick="enviarRecibo()">
                        Enviar
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- Fim Modal Enviar Recibo -->

            <!-- ===================== Impostos / RH ===================== -->
            <div class="tab-pane fade" id="tab-rh-impostos" role="tabpanel">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title mb-0">Gestão de Impostos / RH</h4>
                    </div>
                    <div class="card-body">
                      <div class="listjs-table" id="ListaImpostos">

                        <div>
                          <table id="TabelaImpostos" class="table table-striped">
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Mês</th>
                                <th>DMR</th>
                                <th>DRI</th>
                                <th>Data_Criação</th>
                              </tr>
                            </thead>
                            <tbody id="listagemImpostos">
                              <!-- Linhas preenchidas pelo PHP (modelPrestador.php) -->
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- end card -->
                </div>
                <!-- end col -->
              </div>

              <!-- end col -->
            </div>
            <!-- end row -->

            <!-- end page title -->


            <!-- Modal Enviar DMR -->
            <div class="modal fade" id="modalDMR" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Upload DMR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                  </div>
                  <form id="formDMR" enctype="multipart/form-data">
                    <div class="modal-body">
                      <input type="hidden" id="dmrID" name="id" />
                      <div class="mb-3">
                        <label for="fileDMR" class="form-label">Selecione o DMR</label>
                        <input type="file" class="form-control" id="fileDMR" name="dmr" accept=".pdf,.jpg,.png"
                          required />
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                      </button>
                      <button type="button" class="btn btn-primary" onclick="enviarDMR()">
                        Upload
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- Fim Modal Enviar DMR -->

            <!-- Modal Enviar DRI -->
            <div class="modal fade" id="modalDRI" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Upload DRI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                  </div>
                  <form id="formDRI" enctype="multipart/form-data">
                    <div class="modal-body">
                      <input type="hidden" id="driID" name="id" />
                      <div class="mb-3">
                        <label for="fileDRI" class="form-label">Selecione o DRI</label>
                        <input type="file" class="form-control" id="fileDRI" name="dri" accept=".pdf,.jpg,.png"
                          required />
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                      </button>
                      <button type="button" class="btn btn-primary" onclick="enviarDRI()">
                        Upload
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- Fim Modal Enviar DRI -->
          </div> <!-- end tab-content -->
        </div> <!-- end rh-page -->

      </div>
      <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <footer class="footer border-top">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <script>
              document.write(new Date().getFullYear());
            </script>
            © Saude Num Ponto.
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

  <!-- DataTables JS -->

  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>




  <!-- Sweet Alert js-->
  <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- Bootstrap Js -->
  <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Simplebar Js -->
  <script src="assets/libs/simplebar/simplebar.min.js"></script>
  <!-- Node-waves Js -->
  <script src="assets/libs/node-waves/waves.min.js"></script>

  <!-- Layout config Js -->
  <script src="assets/js/layout.js"></script>


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

  <script src="src/js/header-user.js"></script>

  <script src="src/js/prestador.js"></script>

  <script src="src/js/login.js"></script>

</body>

</html>