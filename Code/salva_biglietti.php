<?php
session_start();
require 'connect.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['proiezioneId'], $data['posti'], $data['prezzi'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Dati mancanti']);
    exit;
}

$proiezioneId = $data['proiezioneId'];
$posti = $data['posti'];
$prezzi = $data['prezzi'];

if (count($posti) !== count($prezzi)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Posti e prezzi non corrispondono']);
    exit;
}

$sql = "INSERT INTO Biglietto (numProiezione, numFila, numPosto, dataVendita, prezzo)
        VALUES (:numProiezione, :numFila, :numPosto, NOW(), :prezzo)";
$stmt = $conn->prepare($sql);

try {
    foreach ($posti as $i => $p) {
        $fila = substr($p, 0, 1);
        $numero = intval(substr($p, 1));
        $stmt->execute([
            ':numProiezione' => $proiezioneId,
            ':numFila' => $fila,
            ':numPosto' => $numero,
            ':prezzo' => $prezzi[$i]
        ]);
    }

    echo json_encode([
        'status' => 'ok',
        'debug' => [
            'proiezioneId' => $proiezioneId,
            'posti' => $posti,
            'prezzi' => $prezzi
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Errore DB', 'error' => $e->getMessage()]);
}
