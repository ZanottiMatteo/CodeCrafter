<?php
require 'connect.php';

$film = $_GET['film'] ?? '';
$data = $_GET['data'] ?? '';
$ora = $_GET['ora'] ?? '';

if (!$film || !$data || !$ora) {
    http_response_code(400);
    echo json_encode(['proiezioneId' => null, 'message' => 'Parametri mancanti']);
    exit;
}

$stmt = $conn->prepare("
  SELECT numProiezione
  FROM Proiezione
  WHERE filmProiettato = :film
    AND data = :data
    AND ora = :ora
  LIMIT 1
");

$stmt->execute([
    ':film' => $film,
    ':data' => $data,
    ':ora' => $ora
]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode(['proiezioneId' => $row['numProiezione'] ?? null]);
