<?php
session_start();
require_once '../model/modelAula.php';

$mdl = new Aula();
$op  = $_POST['op'] ?? '';

//OP 1 — REGISTAR AULA (ADMIN)

if ($op == '1') {

    echo $mdl->registarAula(
        $_POST['titulo'] ?? '',
        $_POST['descricao'] ?? '',
        $_POST['data_inicio'] ?? '',
        $_POST['duracao_min'] ?? 60,
        $_POST['limite_participantes'] ?? 10,
        $_POST['nivel'] ?? 'Iniciante',
        $_POST['preco'] ?? 0,
        $_POST['id_pt'] ?? null,      // FIX
        $_POST['id_estado'] ?? 2,
        $_POST['sala_nome'] ?? ''
    );
    exit;
}

if ($op == '2') {

    $id_cliente = null;

    if (isset($_SESSION['tipo']) && (int)$_SESSION['tipo'] === 3) {
        $id_cliente = $mdl->getClienteCodigoByUtilizador($_SESSION['id']);
    }

    $res = $mdl->listarPublico($_POST['id_estado'] ?? null, $id_cliente);

    while ($row = $res->fetch_assoc()) {

        $lotada = $row['inscritos'] >= $row['limite_participantes'];

        $nivelClass = match($row['nivel']) {
            'Avançado'   => 'danger',
            'Intermédio' => 'warning',
            default      => 'success'
        };

        $titulo  = htmlspecialchars($row['titulo']);
        $nivel   = $row['nivel'];
        $data    = date('d/m/Y H:i', strtotime($row['data_inicio']));
        $insc    = (int)$row['inscritos'];
        $limite  = (int)$row['limite_participantes'];
        $idAula  = (int)$row['id'];

        $baseFisica = $_SERVER['DOCUMENT_ROOT'] . '/Projeto_Final_AIO/Landing_page/';
        $basePublica = '/Projeto_Final_AIO/Landing_page/';

        $mapaImagens = [
    'zumba'        => 'zumba.jpg',
    'hiit'         => 'hiit.jpg',
    'calistenia'   => 'calistenia.jpg',
    'cardio'       => 'cardio.jpg',
    'yoga'         => 'yoga.jpg',
    'funcional'    => 'funcional.jpg'
];

$tituloLower = mb_strtolower($row['titulo'], 'UTF-8');

$imgRelativa = 'assets/img/aulas/default.jpg';

foreach ($mapaImagens as $chave => $img) {
    if (str_contains($tituloLower, $chave)) {
        $imgRelativa = "assets/img/aulas/$img";
        break;
    }
}

$imgPublica = $basePublica . $imgRelativa;


echo <<<HTML

<div class="col-md-4 mb-4">
  <div class="card h-100 shadow-sm aula-card">

    <div class="card-img-top"
     style="
       height:160px;
       background-image:url('{$imgPublica}');
       background-size:cover;
       background-position:center;
     ">
</div>


    <div class="card-body d-flex flex-column">

      <h5 class="card-title mb-1">{$titulo}</h5>

      <span class="badge bg-{$nivelClass} mb-2">{$nivel}</span>

    <p class="small text-muted mb-1 aula-info">
        <i class="ri-calendar-line me-1"></i>
            {$data}
    </p>
    <p class="small text-muted mb-3 aula-info">
        <i class="ri-group-line me-1"></i>
            {$insc} / {$limite} participantes
    </p>

HTML;

$vagasRestantes = $limite - $insc;

if ($vagasRestantes > 0 && $vagasRestantes <= 3) {
    echo '<span class="badge bg-danger mb-2">Últimas vagas</span>';
}

        echo <<<HTML

      <div class="mt-auto d-grid gap-2">
HTML;

        if ($row['ja_inscrito']) {
            echo '
                <a href="video_aula.php?id='.$idAula.'"
                   class="btn btn-success">
                   Entrar na aula
                </a>';
        }
        elseif ($lotada) {
            echo '
                <button class="btn btn-primary btn-lotada" disabled>
                    Aula lotada
                </button>';
        }
        else {
            echo '
                <button class="btn btn-inscrever"
                    onclick="inscreverAulaConfirm('.$idAula.')">
                    Inscrever
                </button>';
        }

        echo '
                <button class="btn btn-outline-secondary btn-sm"
                    onclick="abrirModalAula('.$idAula.')">
                    Detalhes
                </button>
      </div>
    </div>
  </div>
</div>';
    }

    exit;
}


if ($op == '3') {

    $id_aula = intval($_POST['id'] ?? 0);
    $id_user = $_SESSION['id'] ?? null;
    $tipo = $_SESSION['tipo'] ?? null;

    if (!$id_aula) {
        echo json_encode(['flag'=>false,'msg'=>'Aula inválida']);
        exit;
    }

    $aula = $mdl->getAulaDetalheCliente($id_aula, $id_user);

    if (!$aula) {
        echo json_encode(['flag'=>false,'msg'=>'Aula não encontrada']);
        exit;
    }

    // regras
    $aula['estado_aula'] = $aula['id_estado'];

    if ($tipo === 'pt') {
        $aula['pode_entrar'] = true;
    } else {
        $aula['pode_entrar'] =
            ($aula['ja_inscrito'] == 1 && $aula['id_estado'] == 2);
    }

    echo json_encode($aula, JSON_UNESCAPED_UNICODE);
    exit;
}


if ($op == '4') {

    if (!isset($_SESSION['id']) || (int)$_SESSION['tipo'] !== 3) {
        echo json_encode(['flag'=>false,'msg'=>'Sessão inválida']);
        exit;
    }

    // FIX CRÍTICO
    $id_cliente = $mdl->getClienteCodigoByUtilizador($_SESSION['id']);

    if (!$id_cliente) {
        echo json_encode(['flag'=>false,'msg'=>'Cliente inválido']);
        exit;
    }

    echo $mdl->inscreverAula(
        intval($_POST['id_aula']),
        $id_cliente
    );
    exit;
}

if ($op == '5') {
    // cancelar inscrição
    $id_aula = $_POST['id_aula'] ?? 0;
    $id_cliente = $_POST['id_cliente'] ?? 0;
    // opcional id_estado_cancelado
    $id_estado_cancelado = $_POST['id_estado_cancelado'] ?? null;
    echo $mdl->cancelarInscricao($id_aula, $id_cliente, $id_estado_cancelado);
    exit;
}

if ($op == '6') {

    $id_aula = intval($_POST['id_aula'] ?? 0);
    $res = $mdl->listarInscritos($id_aula);

    if ($res && $res->num_rows > 0) {
        echo '<table class="table">
                <thead>
                    <tr><th>Cliente</th><th>Estado</th><th>Data</th></tr>
                </thead><tbody>';
        while ($r = $res->fetch_assoc()) {
            echo '<tr>
                    <td>'.htmlspecialchars($r['cliente_nome']).'</td>
                    <td>'.htmlspecialchars($r['estado_nome']).'</td>
                    <td>'.$r['created_at'].'</td>
                  </tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-info">Sem inscrições.</div>';
    }
    exit;
}

if ($op == '7') {
    // listar admin (tabela)
    $r = $mdl->listarAdmin();
    if ($r && $r->num_rows > 0) {
        echo '<table class="table table-striped" id="tblAulasAdmin"><thead><tr>
                <th>ID</th><th>Título</th><th>Início</th><th>Dur.</th><th>Limite</th><th>PT</th><th>Estado</th><th>Ações</th>
              </tr></thead><tbody>';
        while ($row = $r->fetch_assoc()) {
            $data = date('d/m/Y H:i', strtotime($row['data_inicio']));
            echo '<tr>
                    <td>'.$row['id'].'</td>
                    <td>'.htmlspecialchars($row['titulo']).'</td>
                    <td>'.$data.'</td>
                    <td>'.$row['duracao_min'].'</td>
                    <td>'.$row['limite_participantes'].'</td>
                    <td>'.htmlspecialchars($row['pt_username'] ?? '').'</td>
                    <td>'.htmlspecialchars($row['estado_nome'] ?? '').'</td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="abrirEditarAula('.$row['id'].')">
                        <i class="ri-pencil-line"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="removerAula('.$row['id'].')">
                        <i class="ri-delete-bin-line"></i>
                        </button>
                        <button class="btn btn-sm btn-info" onclick="verInscritos('.$row['id'].')">Inscritos</button>
                    </td>
                  </tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-info">Sem aulas.</div>';
    }
    exit;
}

if ($op == '12') {
    // editar (admin)
    $nivel = trim($_POST['nivel']);
    
    $nivelsValidos = ['Iniciante', 'Intermédio', 'Avançado'];

if (!in_array($nivel, $nivelsValidos, true)) {
    echo json_encode([
        'status' => 'erro',
        'msg' => 'Nível inválido'
    ]);
    exit;
}

    echo $mdl->editarAula(
        $_POST['id'] ?? 0,
        $_POST['titulo'] ?? '',
        $_POST['descricao'] ?? '',
        $_POST['data_inicio'] ?? '',
        $_POST['duracao_min'] ?? 60,
        $_POST['limite_participantes'] ?? 10,
        $_POST['nivel'] ?? 'Iniciante',
        $_POST['preco'] ?? 0,
        $_POST['id_pt'] ?? null,
        $_POST['id_estado'] ?? 1,        
        $_POST['sala_nome'] ?? ''
    );
    exit;
}

if ($op == '9') {
    // remover (admin)
    echo $mdl->removerAula($_POST['id'] ?? 0);
    exit;
}

if ($op == '10') { 
    $r = $mdl->listarEstados();
    if ($r && $r->num_rows > 0) {
        while($row = $r->fetch_assoc()) {
            echo "<option value='{$row['id']}'>".htmlspecialchars($row['descricao'])."</option>";
        }
    }
    exit;
}

if ($op == '11') { 
    $r = $mdl->listarIDPT();
    if ($r && $r->num_rows > 0) {
        while($row = $r->fetch_assoc()) {
            echo "<option value='{$row['id']}'>".htmlspecialchars($row['nome_completo'])."</option>";
        }
    }
    exit;
}

if ($op == '13') {
    $id = (int)($_POST['id'] ?? 0);
    $aula = $mdl->getAulaById($id);
    echo $aula;
    exit;
}

if ($op == '20') {

    // Apenas PT
    if ((int)$_SESSION['tipo'] !== 2) {
        echo '<div class="alert alert-danger">Acesso negado</div>';
        exit;
    }

    $res = $mdl->listarAulasPT($_SESSION['id']);

    if (!$res || $res->num_rows === 0) {
        echo '<div class="alert alert-info">Ainda não tens aulas.</div>';
        exit;
    }

    while ($row = $res->fetch_assoc()) {

        $inscritos = (int)$row['inscritos'];

echo '
<div class="pt-card aula-card">
  <div class="aula-row">
    <div class="aula-info">
      <h5>'.htmlspecialchars($row['titulo']).'</h5>

      <small class="d-block">
        '.date('d/m/Y H:i', strtotime($row['data_inicio'])).'
      </small>

      <span class="badge mt-2">
        '.$inscritos.' inscrito'.($inscritos === 1 ? '' : 's').'
      </span>
    </div>

    <div class="aula-actions">
      <button class="pt-btn-outline"
        type="button"
        onclick="verInscritosPT('.$row['id'].')">
        Ver inscritos
      </button>

      <a href="video_aula.php?id='.$row['id'].'" class="pt-btn">
        Entrar na aula
      </a>
    </div>
  </div>
</div>';

    }

    exit;
}

echo json_encode(['flag'=>false,'msg'=>'Operação inválida']);
