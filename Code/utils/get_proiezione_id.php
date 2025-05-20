<?php
require 'connect.php';

$film = $_GET['film'] ?? '';
$data = $_GET['data'] ?? '';
$ora = $_GET['ora'] ?? '';

if ($ora) {
  $parts = explode(':', $ora);
  if (count($parts) >= 2) {
    $ora = sprintf(
      '%02d:%02d:%02d',
      intval($parts[0]),
      intval($parts[1]),
      isset($parts[2]) ? intval($parts[2]) : 0
    );
  } else {
    $ora = '';
  }
}

if (!$film || !$data || !$ora) {
  http_response_code(400);
  echo json_encode(['proiezioneId' => null, 'message' => 'Parametri mancanti']);
  exit;
}

$stmt = $conn->prepare("
  SELECT numProiezione, sala
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

if ($row) {
  echo json_encode([
    'proiezioneId' => $row['numProiezione'],
    'sala' => $row['sala']
  ]);
} else {
  http_response_code(404);
  echo json_encode([
    'proiezioneId' => null,
    'sala' => null,
    'message' => 'Proiezione non trovata'
  ]);
}
