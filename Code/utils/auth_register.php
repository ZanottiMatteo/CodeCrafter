<?php
header("Content-Type: application/json");
include 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$nome = trim($data['nome']);
$mail = trim($data['mail']);
$password = $data['password'];

if (empty($nome) || empty($mail) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Tutti i campi sono obbligatori"]);
    exit;
}

$stmt = $conn->prepare("SELECT COUNT(*) FROM Utente WHERE mail = ?");
$stmt->execute([$mail]);

if ($stmt->fetchColumn() > 0) {
    echo json_encode(["success" => false, "message" => "Email già registrata"]);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO Utente (nome, mail, password) VALUES (?, ?, ?)");
$ok = $stmt->execute([$nome, $mail, $hash]);

echo json_encode(["success" => $ok]);
?>