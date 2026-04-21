<?php
require_once 'model/connection.php'; 


$username = 'Admin'; 
$email = 'admin@gmail.com';
$password = 'Admin123';
$pw_hash = password_hash($password, PASSWORD_BCRYPT);
$id_tipo_user = 1; 


$stmt = $conn->prepare("
    INSERT INTO utilizador (username, email, password, id_tipo_user, foto, data_registo)
    VALUES (?, ?, ?, ?, NULL, NOW())
");


$stmt->bind_param("sssi", $username, $email, $pw_hash, $id_tipo_user);


if ($stmt->execute()) {
    echo "Conta de administrador criada com sucesso.";
} else {
    echo "Erro ao criar admin: " . $stmt->error;
}


$stmt->close();
$conn->close();
?>

