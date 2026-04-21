<?php
require_once 'connection.php';
require_once '../config/square_config.php';

class Produto {
    private $db;

    public function __construct() {
        global $conn;
        $this->db = $conn;
    }

    private function handleUpload($fileFieldName = 'imagem'){

        $dirWeb = 'assets/images/products/';
        $dirFs  = realpath(__DIR__ . '/../../') . '/assets/images/products/';

        if (!is_dir($dirFs)) {
            mkdir($dirFs, 0775, true);
        }

        if (!isset($_FILES[$fileFieldName]) || $_FILES[$fileFieldName]['error'] !== UPLOAD_ERR_OK) {
            return array('flag'=>false,'msg'=>'Sem upload','target'=>'');
        }

        $original = basename($_FILES[$fileFieldName]['name']);
        $safe = time() . '_' . preg_replace('/[^a-zA-Z0-9_\-\.]/','_', $original);

        $targetFs  = $dirFs . $safe;
        $targetWeb = $dirWeb . $safe;

        if (move_uploaded_file($_FILES[$fileFieldName]['tmp_name'], $targetFs)) {
            return array('flag'=>true,'msg'=>'Upload ok','target'=>$targetWeb);
        }

        return array('flag'=>false,'msg'=>'Erro upload','target'=>'');
    }

    // OP 1 - REGISTAR

    public function registarProduto($nome, $preco, $descricao, $stock, $id_parceiro, $id_estado){

        $nome = $this->db->real_escape_string($nome);
        $descricao = $this->db->real_escape_string($descricao);
        $preco = (float)$preco;
        $stock = (int)$stock;
        $id_parceiro = (int)$id_parceiro;
        $id_estado = (int)$id_estado;

        $upload = $this->handleUpload('imagem');

        if ($upload['flag']) {

            $imagem = $upload['target'];

            $sql = "INSERT INTO produto_marketplace (nome, descricao, preco, stock, imagem, id_parceiro, id_estado)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssdissi', $nome, $descricao, $preco, $stock, $imagem, $id_parceiro, $id_estado);

        } else {

            $sql = "INSERT INTO produto_marketplace (nome, descricao, preco, stock, id_parceiro, id_estado)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssdiii', $nome, $descricao, $preco, $stock, $id_parceiro, $id_estado);

        }

        $ok = $stmt->execute();

        return json_encode(array(
            'flag' => $ok,
            'msg'  => $ok ? 'Produto registado com sucesso' : 'Erro ao inserir: ' . $this->db->error
        ));
    }

    // LISTAGENS BD

    private function listarAdminBD(){
        return $this->db->query("
            SELECT pm.*, p.nome AS parceiro_nome, e.descricao AS estado_nome
            FROM produto_marketplace pm
            LEFT JOIN parceiro_marketplace p ON pm.id_parceiro = p.id
            LEFT JOIN estado e ON pm.id_estado = e.id
            ORDER BY pm.id DESC
        ");
    }

    public function getProdutosPublicosBD(){
        return $this->db->query("
            SELECT *
            FROM produto_marketplace
            WHERE id_estado = 2
            ORDER BY id DESC
        ");
    }

    // OP 2 - LISTAR ADMIN

    public function getListaProdutosAdmin(){

        $r = $this->listarAdminBD();

        if($r && $r->num_rows > 0){

            $html = '';
            $html .= '<table class="table table-striped" id="tblProdutosAdmin">';
            $html .= '<thead><tr>
                        <th>ID</th><th>Nome</th><th>Preço</th><th>Stock</th><th>Imagem</th>
                        <th>Parceiro</th><th>Estado</th><th>Ações</th>
                      </tr></thead><tbody>';

            while($row = $r->fetch_assoc()){

                $img = !empty($row['imagem']) ? $row['imagem'] : 'assets/images/placeholder.png';

                $html .= '<tr>';
                $html .= '<td>'.$row['id'].'</td>';
                $html .= '<td>'.htmlspecialchars($row['nome']).'</td>';
                $html .= '<td>'.number_format($row['preco'],2,',','.').' €</td>';
                $html .= '<td>'.$row['stock'].'</td>';
                $html .= '<td><img src="../'.$img.'" style="height:50px;object-fit:cover"></td>';
                $html .= '<td>'.htmlspecialchars($row['parceiro_nome']).'</td>';
                $html .= '<td>'.htmlspecialchars($row['estado_nome']).'</td>';
                $html .= '<td>
                            <button class="btn btn-sm btn-success" onclick="abrirModalEditarProduto('.$row['id'].')">
                                <i class="ri-pencil-line"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="removerProduto('.$row['id'].')">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                          </td>';
                $html .= '</tr>';
            }

            $html .= '</tbody></table>';
            return $html;

        }else{
            return '<div class="alert alert-info">Sem produtos</div>';
        }
    }

    // OP 3 - REMOVER

    public function removerProduto($id){

        $id = (int)$id;

        $stmt = $this->db->prepare("DELETE FROM produto_marketplace WHERE id = ?");
        $stmt->bind_param('i',$id);

        $ok = $stmt->execute();

        return json_encode(array(
            'flag'=> $ok,
            'msg'=> $ok ? 'Removido com sucesso' : 'Erro: ' . $this->db->error
        ));
    }

    // OP 4 - DADOS 

    public function getDadosProduto($id){

        $id = (int)$id;

        $stmt = $this->db->prepare("SELECT * FROM produto_marketplace WHERE id = ?");
        $stmt->bind_param('i',$id);
        $stmt->execute();

        $res = $stmt->get_result();
        $row = $res->fetch_assoc();

        return json_encode($row ?: array(), JSON_UNESCAPED_UNICODE);
    }

    // OP 5 - EDITAR

    public function guardaEditProduto($id, $nome, $preco, $descricao, $stock, $id_parceiro, $id_estado){

        $id = (int)$id;
        $nome = $this->db->real_escape_string($nome);
        $descricao = $this->db->real_escape_string($descricao);
        $preco = (float)$preco;
        $stock = (int)$stock;
        $id_parceiro = (int)$id_parceiro;
        $id_estado = (int)$id_estado;

        $upload = $this->handleUpload('imagem');

        if ($upload['flag']) {

            $imagem = $upload['target'];

            $sql = "UPDATE produto_marketplace
                    SET nome=?, descricao=?, preco=?, stock=?, imagem=?, id_parceiro=?, id_estado=?
                    WHERE id=?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssdiisii', $nome, $descricao, $preco, $stock, $imagem, $id_parceiro, $id_estado, $id);

        } else {

            $sql = "UPDATE produto_marketplace
                    SET nome=?, descricao=?, preco=?, stock=?, id_parceiro=?, id_estado=?
                    WHERE id=?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssdiisi', $nome, $descricao, $preco, $stock, $id_parceiro, $id_estado, $id);

        }

        $ok = $stmt->execute();

        return json_encode(array(
            'flag' => $ok,
            'msg'  => $ok ? 'Produto editado com sucesso' : 'Erro ao editar: '.$this->db->error
        ));
    }

    // OP 6 - SELECT PARCEIROS 

    public function getSelectParceiros(){

        $r = $this->db->query("
            SELECT id, nome
            FROM parceiro_marketplace
            WHERE ativo = 1
            ORDER BY nome
        ");

        $html = "";

        if($r && $r->num_rows > 0){
            while($row = $r->fetch_assoc()){
                $html .= "<option value='".$row['id']."'>".htmlspecialchars($row['nome'])."</option>";
            }
        }

        return $html;
    }

    // OP 7 - SELECT ESTADOS

    public function getSelectEstados(){

        $r = $this->db->query("
            SELECT id, descricao
            FROM estado
            ORDER BY descricao
        ");

        $html = "";

        if($r && $r->num_rows > 0){
            while($row = $r->fetch_assoc()){
                $html .= "<option value='".$row['id']."'>".htmlspecialchars($row['descricao'])."</option>";
            }
        }

        return $html;
    }

    // OP 8 - MARKETPLACE CLIENTE 

    public function getListaProdutosMarketplace(){

        $r = $this->getProdutosPublicosBD();
        $html = "";

        if($r && $r->num_rows > 0){

            while($row = $r->fetch_assoc()){

                $img   = !empty($row['imagem']) ? $row['imagem'] : 'assets/images/placeholder.png';
                $nome  = htmlspecialchars($row['nome'], ENT_QUOTES);
                $desc  = htmlspecialchars($row['descricao'], ENT_QUOTES);
                $preco = number_format($row['preco'], 2, ',', '.');
                $precoData = number_format($row['preco'], 2, '.', '');
                $stock = (int)($row['stock'] ?? 0);

                $stockLabel = $stock > 0 ? "Em stock" : "Esgotado";

                $html .= '<div class="col-12 col-sm-6 col-lg-4 col-xxl-3">';
                $html .= '  <div class="mp-card produto-card" data-nome="'.$nome.'" data-preco="'.$precoData.'" data-stock="'.$stock.'">';
                $html .= '      <div class="mp-media">';
                $html .= '          <img src="../'.$img.'" alt="'.$nome.'">';
                $html .= '          <span class="mp-badge">'.$stockLabel.'</span>';
                $html .= '      </div>';

                $html .= '      <div class="mp-body">';
                $html .= '          <h5 class="mp-title">'.$nome.'</h5>';
                $html .= '          <p class="mp-desc">'.$desc.'</p>';

                $html .= '          <div class="mp-price-row">';
                $html .= '              <div class="mp-price">'.$preco.' €</div>';
                $html .= '          </div>';

                $disabled = ($stock <= 0) ? 'disabled' : '';

                $html .= '          <div class="mp-actions">';
                $html .= '              <button class="btn btn-mp-add btn-add-cart" '.$disabled.'
                                    data-id="'.$row['id'].'"
                                    data-nome="'.$nome.'"
                                    data-preco="'.$precoData.'"
                                    data-imagem="'.htmlspecialchars($row['imagem'] ?? '', ENT_QUOTES).'">
                                    <i class="ri-shopping-cart-line"></i> Adicionar
                                </button>';
                $html .= '          </div>';

                $html .= '      </div>';
                $html .= '  </div>';
                $html .= '</div>';
            }

        }else{
            $html .= '<div class="col-12"><div class="alert alert-warning">Sem produtos disponíveis.</div></div>';
        }

        return $html;
    }

    // SQUARE (OP 9)

    private function squareRequest($endpoint, $payload){

        $ch = curl_init(squareBaseUrl() . $endpoint);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . SQUARE_ACCESS_TOKEN,
            "Content-Type: application/json",
            "Square-Version: 2025-01-23"
        ));

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));

        $resp = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($resp === false){
            $err = curl_error($ch);
            curl_close($ch);
            return array("ok"=>false, "http"=>0, "raw"=>"", "err"=>$err);
        }

        curl_close($ch);

        return array("ok"=>($http >= 200 && $http < 300), "http"=>$http, "raw"=>$resp, "err"=>"");
    }

    public function processarPagamentoSquare(
        $nonce,
        $itemsJson,
        $idCliente,
        $subtotalCli = null,
        $descontoCli = null,
        $ivaCli = null,
        $amountCli = null,
        $nome = '',
        $email = '',
        $nif = '',
        $telefone = '',
        $altDados = 0,
        $termos = 0,
        $cupaoCodigo = ''
    ){
        $items = json_decode($itemsJson, true);
        $nonce = trim($nonce);
        $idCliente = (int)$idCliente;

        $nome = trim((string)$nome);
        $email = trim((string)$email);
        $nif = trim((string)$nif);
        $telefone = trim((string)$telefone);
        $altDados = (int)$altDados;
        $termos = (int)$termos;
        $cupaoCodigo = trim((string)$cupaoCodigo);

        if($nonce == "" || $idCliente <= 0 || !is_array($items) || count($items) == 0){
            return json_encode(array("flag"=>false,"msg"=>"Dados inválidos."));
        }

        // se estás a usar termos no checkout, valida aqui (senão comenta)
        if($termos == 0){
            return json_encode(array("flag"=>false,"msg"=>"Tens de aceitar os termos."));
        }

        if($email != "" && strpos($email, "@") === false){
            return json_encode(array("flag"=>false,"msg"=>"Email inválido."));
        }

        if($nif != "" && !preg_match("/^[0-9]{9}$/", $nif)){
            return json_encode(array("flag"=>false,"msg"=>"NIF inválido."));
        }

        // 1) recalcular total pela BD (seguro)
        $subtotal = 0.0;
        $linhas = array();

        foreach($items as $it){

            $idProduto = (int)($it['id'] ?? 0);
            $qtd       = (int)($it['qtd'] ?? 0);

            if($idProduto <= 0 || $qtd <= 0){
                continue;
            }

            $sql = "
                SELECT 
                    p.nome,
                    p.preco,
                    p.id_parceiro,
                    COALESCE(pm.percentual_comissao,0) AS percentual_comissao
                FROM produto_marketplace p
                LEFT JOIN parceiro_marketplace pm ON pm.id = p.id_parceiro
                WHERE p.id = ?
            ";

            $stmt = $this->db->prepare($sql);
            if(!$stmt){
                return json_encode(array("flag"=>false,"msg"=>"Erro SQL: ".$this->db->error));
            }

            $stmt->bind_param("i", $idProduto);
            $stmt->execute();
            $res = $stmt->get_result();
            $p = $res ? $res->fetch_assoc() : null;

            if(!$p){
                return json_encode(array("flag"=>false,"msg"=>"Produto inválido."));
            }

            $preco = (float)$p['preco'];
            $sub = $preco * $qtd;
            $subtotal += $sub;

            $percentual = (float)$p['percentual_comissao'];
            $valorComissao = ($sub * $percentual) / 100;

            $linhas[] = array(
                "id_parceiro"     => (int)$p['id_parceiro'],
                "nome"            => $p['nome'],
                "preco"           => $preco,
                "qtd"             => $qtd,
                "percentual"      => $percentual,
                "valor_comissao"  => $valorComissao
            );
        }

        if($subtotal <= 0){
            return json_encode(array("flag"=>false,"msg"=>"Carrinho inválido."));
        }

        // 2) desconto/iva: por agora do lado do servidor (seguro)
        // Se ainda não tens cupões na BD, mantém desconto=0.
        $desconto = 0.0;
        $iva = 0.0;

        // total final
        $totalFinal = ($subtotal - $desconto) + $iva;

        // 3) pagamento Square (usa totalFinal)
        $taxaCambio = 1.08;
        $totalUsd = round($totalFinal * $taxaCambio, 2);

        $payload = array(
            "source_id" => $nonce,
            "idempotency_key" => bin2hex(random_bytes(16)),
            "amount_money" => array(
                "amount" => (int) round($totalUsd * 100),
                "currency" => "USD"
            ),
        );

        $res = $this->squareRequest("/v2/payments", $payload);
        $data = json_decode($res["raw"], true);

        if(!$res["ok"]){
            $msg = $data["errors"][0]["detail"] ?? "Pagamento recusado.";
            return json_encode(array("flag"=>false,"msg"=>$msg));
        }

        // 4) cliente -> codigo
        $sql = "SELECT codigo FROM cliente WHERE id_utilizador = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $r = $stmt->get_result();

        if(!$r || $r->num_rows == 0){
            return json_encode(array("flag"=>false,"msg"=>"Cliente não existe"));
        }

        $row = $r->fetch_assoc();
        $codigoCliente = (int)$row['codigo'];

        // 5) registar venda (mantém teu insert atual)
        $metodo = "cartao";
        $estado = 12;
        $idServico = 10;

        $sql = "INSERT INTO venda (id_cliente, id_servico, valor, data_venda, metodo_pagamento, id_estado)
                VALUES (?, ?, ?, NOW(), ?, ?)";
        $stmt = $this->db->prepare($sql);
        if(!$stmt){
            return json_encode(array("flag"=>false,"msg"=>"Erro SQL venda: ".$this->db->error));
        }

        $stmt->bind_param("iidsi", $codigoCliente, $idServico, $totalFinal, $metodo, $estado);
        if(!$stmt->execute()){
            return json_encode(array("flag"=>false,"msg"=>"Erro a registar venda: ".$this->db->error));
        }

        $idVenda = (int)$this->db->insert_id;

        // 6) linhas venda_marketplace
        $sqlLinha = "INSERT INTO venda_marketplace
                    (id_venda, id_parceiro, produto_nome, preco_produto, percentual_comissao)
                    VALUES (?,?,?,?,?)";
        $stmtLinha = $this->db->prepare($sqlLinha);
        if(!$stmtLinha){
            return json_encode(array("flag"=>false,"msg"=>"Erro SQL venda_marketplace: ".$this->db->error));
        }

        foreach($linhas as $l){
            $idParceiro = (int)$l['id_parceiro'];
            $nomeProd   = (string)$l['nome'];
            $precoProd  = (float)$l['preco'];
            $percCom    = (float)$l['percentual'];

            $stmtLinha->bind_param("iisdd", $idVenda, $idParceiro, $nomeProd, $precoProd, $percCom);
            $stmtLinha->execute();
        }

        // 7) (opcional) guardar snapshot buyer se tiveres tabela venda_buyer_snapshot
        // Se não tiveres, isto falha silenciosamente (como deve ser).
        $sqlSnap = "INSERT INTO venda_buyer_snapshot (id_venda, nome, email, nif, telefone, alt_dados, termos_aceites)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtS = $this->db->prepare($sqlSnap);
        if($stmtS){
            $stmtS->bind_param("issssii", $idVenda, $nome, $email, $nif, $telefone, $altDados, $termos);
            $stmtS->execute();
        }

        return json_encode(array(
            "flag"=>true,
            "msg"=>"Pagamento efetuado e venda registada"
        ));
    }

    public function getParceirosMarketplace(){

        $r = $this->db->query("
            SELECT id, nome, logo
            FROM parceiro_marketplace
            WHERE ativo = 1
            ORDER BY nome ASC
        ");

        $html = "";

        if($r && $r->num_rows > 0){

            while($row = $r->fetch_assoc()){

                $nome = trim($row['nome']);
                $safeNome = htmlspecialchars($nome, ENT_QUOTES);

                $logo = isset($row['logo']) ? trim($row['logo']) : "";
                $iniciais = "";

                $parts = preg_split('/\s+/', $nome);
                if(count($parts) >= 2){
                    $iniciais = mb_strtoupper(mb_substr($parts[0],0,1).mb_substr($parts[1],0,1));
                }else{
                    $iniciais = mb_strtoupper(mb_substr($nome,0,2));
                }

                $html .= '<a class="mp-partner" href="#">';
                if($logo != ""){
                    $html .= '  <span class="mp-partner-badge mp-partner-badge-img">';
                    $html .= '    <img src="../'.htmlspecialchars($logo, ENT_QUOTES).'" alt="'.$safeNome.'">';
                    $html .= '  </span>';
                }else{
                    $html .= '  <span class="mp-partner-badge">'.$iniciais.'</span>';
                }
                $html .= '  <span class="mp-partner-name">'.$safeNome.'</span>';
                $html .= '</a>';
            }
        }

        return $html;
    }

    public function validarCupao($codigo){

        $codigo = trim($codigo);

        if($codigo == ''){
            return json_encode(["flag"=>false,"msg"=>"Código vazio."]);
        }

        $sql = "SELECT codigo, tipo, valor, ativo, data_inicio, data_fim, uso_max, usos_atual, min_subtotal
                FROM cupao
                WHERE codigo = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        if(!$stmt){
            return json_encode(["flag"=>false,"msg"=>"Cupões não configurados."]);
        }

        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $res = $stmt->get_result();
        $c = $res ? $res->fetch_assoc() : null;

        if(!$c){
            return json_encode(["flag"=>false,"msg"=>"Cupão inválido."]);
        }

        if((int)$c['ativo'] != 1){
            return json_encode(["flag"=>false,"msg"=>"Cupão inativo."]);
        }

        $hoje = date("Y-m-d");
        if(!empty($c['data_inicio']) && $hoje < $c['data_inicio']){
            return json_encode(["flag"=>false,"msg"=>"Cupão ainda não está ativo."]);
        }
        if(!empty($c['data_fim']) && $hoje > $c['data_fim']){
            return json_encode(["flag"=>false,"msg"=>"Cupão expirado."]);
        }

        if(!empty($c['uso_max']) && (int)$c['usos_atual'] >= (int)$c['uso_max']){
            return json_encode(["flag"=>false,"msg"=>"Cupão esgotado."]);
        }

        return json_encode([
            "flag" => true,
            "tipo" => $c["tipo"],
            "valor"=> (float)$c["valor"],
            "msg"  => "OK"
        ]);
    }

    public function getDadosComprador($idUtilizador){

        $idUtilizador = (int)$idUtilizador;

        if($idUtilizador <= 0){
            return json_encode([
                "flag" => false,
                "msg"  => "Utilizador inválido."
            ]);
        }

        // UTILIZADOR não tem "nome". O nome do cliente está em cliente.nome_completo
        $sql = "SELECT 
                    c.nome_completo AS nome,
                    u.email AS email,
                    c.nif AS nif,
                    c.contacto AS tel
                FROM cliente c
                INNER JOIN utilizador u ON u.id = c.id_utilizador
                WHERE c.id_utilizador = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        if(!$stmt){
            return json_encode([
                "flag" => false,
                "msg"  => "Erro SQL: ".$this->db->error
            ]);
        }

        $stmt->bind_param("i", $idUtilizador);
        $stmt->execute();

        $res = $stmt->get_result();

        if(!$res || $res->num_rows == 0){
            return json_encode([
                "flag" => false,
                "msg"  => "Cliente não encontrado."
            ]);
        }

        $row = $res->fetch_assoc();

        return json_encode([
            "flag"  => true,
            "nome"  => $row["nome"] ?? "",
            "email" => $row["email"] ?? "",
            "nif"   => $row["nif"] ?? "",
            "tel"   => $row["tel"] ?? ""
        ]);
    }
}
