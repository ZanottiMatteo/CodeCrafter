<?php
include 'connect.php';
header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  http_response_code(400);
  echo json_encode(['error' => 'ID sala non valido']);
  exit;
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT numFile, numPostiPerFila FROM Sala WHERE numero = ?");
$stmt->execute([$id]);
$sala = $stmt->fetch(PDO::FETCH_ASSOC);

if ($sala) {
  echo json_encode($sala);
} else {
  http_response_code(404);
  echo json_encode(['error' => 'Sala non trovata']);
}
