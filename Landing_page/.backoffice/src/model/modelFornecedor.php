<?php
require_once 'connection.php';

class Fornecedor {

    // === REGISTAR FORNECEDOR ===
    public function registaFornecedor($fornecedor, $descricao, $total_debito, $total_credito, $saldo, $data) {
        global $conn;
        $flag = true;
        $msg = "";

        $sql = "INSERT INTO fornecedor (fornecedor, descricao, total_debito, total_credito, saldo, data, estado)
                VALUES ('$fornecedor', '$descricao', '$total_debito', '$total_credito', '$saldo', '$data', 'pendente')";

        if ($conn->query($sql) !== TRUE) {
            $flag = false;
            $msg = "Erro: " . $conn->error;
        } else {
            $msg = "Registado com sucesso!";
        }

        return json_encode(["flag" => $flag, "msg" => $msg]);
    }

    // === LISTAR TODOS OS FORNECEDORES ===
    public function getListaFornecedores() {
        global $conn;
        $msg = "";
        $sql = "SELECT * FROM fornecedor ORDER BY id ASC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $msg .= $this->montaLinha($row);
        }
        return $msg;
    }

    // === FILTRAR POR MÊS ===
    public function getListaFornecedoresPorMes($mes) {
        global $conn;
        $msg = "";
        $sql = "SELECT * FROM fornecedor WHERE MONTH(data) = '$mes' ORDER BY id ASC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $msg .= $this->montaLinha($row);
        }
        return $msg;
    }

    // === MARCAR COMO CONCLUÍDO ===
    public function concluirFornecedor($id) {
        global $conn;
        $flag = true;
        $msg = "";

        $sql = "UPDATE fornecedor SET estado = 'concluido' WHERE id = '$id'";

        if ($conn->query($sql) !== TRUE) {
            $flag = false;
            $msg = "Erro ao concluir fornecedor: " . $conn->error;
        } else {
            $msg = "Fornecedor concluído com sucesso!";
        }

        return json_encode(["flag" => $flag, "msg" => $msg]);
    }

    // === MONTAR LINHA HTML (número fixo de colunas) ===
    private function montaLinha($row) {
        if ($row['estado'] === 'concluido') {
            $acoes = "
                <td class='text-center'></td>
                <td class='text-center'>
                    <span class='badge bg-success'>CONCLUÍDO</span>
                </td>
            ";
        } else {
            $acoes = "
                <td class='text-center'>
                    <button class='btn btn-primary btn-sm' onclick='getDadosFornecedor(".$row['id'].")'>
                        <i class='ri-pencil-line'></i>
                    </button>
                </td>
                <td class='text-center'>
                    <button class='btn btn-concluir btn-sm' onclick='concluirFornecedor(".$row['id'].")'>
                        <i class='ri-check-line'></i>
                    </button>
                </td>
            ";
        }

        return "
            <tr>
                <td>".htmlspecialchars($row['fornecedor'])."</td>
                <td>".htmlspecialchars($row['descricao'])."</td>
                <td>".number_format($row['total_debito'], 2, ',', '.')." €</td>
                <td>".number_format($row['total_credito'], 2, ',', '.')." €</td>
                <td>".number_format($row['saldo'], 2, ',', '.')." €</td>
                <td>".htmlspecialchars($row['data'])."</td>
                $acoes
            </tr>
        ";
    }

    // === OBTER DADOS DE UM FORNECEDOR ===
    public function getDadosFornecedor($id) {
        global $conn;
        $sql = "SELECT * FROM fornecedor WHERE id = '$id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return json_encode($row);
    }

    // === GUARDAR EDIÇÃO ===
    public function guardaEditFornecedor($id, $fornecedor, $descricao, $total_debito, $total_credito, $saldo, $data) {
        global $conn;
        $flag = true;
        $msg = "";

        $sql = "UPDATE fornecedor SET 
                    fornecedor='$fornecedor',
                    descricao='$descricao',
                    total_debito='$total_debito',
                    total_credito='$total_credito',
                    saldo='$saldo',
                    data='$data'
                WHERE id='$id'";

        if ($conn->query($sql) !== TRUE) {
            $flag = false;
            $msg = "Erro: " . $conn->error;
        } else {
            $msg = "Editado com sucesso!";
        }

        return json_encode(["flag" => $flag, "msg" => $msg]);
    }
}
?>
