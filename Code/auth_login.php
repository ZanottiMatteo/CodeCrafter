<?php
header("Content-Type: application/json");
include 'connect.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$mail = trim($data['mail']);
$password = $data['password'];

$stmt = $conn->prepare("SELECT id, nome, password FROM Utente WHERE mail = ?");
$stmt->execute([$mail]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nome'] = $user['nome'];
    $_SESSION['mail'] = $mail;
    echo json_encode([
        "success" => true,
        "nome" => $user['nome']
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Email o password errati"
    ]);
}
?>