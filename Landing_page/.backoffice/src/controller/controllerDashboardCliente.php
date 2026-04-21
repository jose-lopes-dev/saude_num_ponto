<?php
include_once "../model/modelDashboardCliente.php";
$d = new Dashboard();

if (!isset($_POST["op"])) {
    echo json_encode(["flag" => false, "msg" => "Operação inválida"]);
    exit;
}

$op = $_POST["op"];

if ($op == 1) {
    $data = $d->carregarDados();

    $consultas = $d->proximasConsultas();
    $aulas = $d->proximasAulas();

    $data["consultas"] = array_merge($consultas, $aulas);

    usort($data["consultas"], function ($a, $b) {
        return strtotime($a["data"] . " " . $a["hora_inicio"])
             <=> strtotime($b["data"] . " " . $b["hora_inicio"]);
    });

    $data["consultas"] = array_slice($data["consultas"], 0, 3);

    echo json_encode($data);
    exit;
}



if ($op == 2) {
    echo json_encode(
        $d->atualizarDados(
            $_POST["peso"] ?? null,
            $_POST["calorias"] ?? null,
            $_POST["tempo"] ?? null
        )
    );
    exit;
}

if ($op == 3) {
    echo json_encode($d->participacaoAtividades());
    exit;
}


echo json_encode(["flag" => false, "msg" => "Operação não suportada"]);
exit;
?>
