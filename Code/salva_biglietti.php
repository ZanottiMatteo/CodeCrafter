<?php
session_start();
require 'connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (
    !$data ||
    !isset($data['proiezioneId'], $data['posti'], $data['prezzi']) ||
    count($data['posti']) !== count($data['prezzi'])
) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Dati mancanti o non validi']);
    exit;
}

$proiezioneId = $data['proiezioneId'];
$posti = $data['posti'];
$prezzi = $data['prezzi'];
$email = isset($data['mail']) ? trim($data['mail']) : null;

$sql = "INSERT INTO Biglietto (numProiezione, numFila, numPosto, dataVendita, prezzo, email)
        VALUES (:numProiezione, :numFila, :numPosto, NOW(), :prezzo, :email)";
$stmt = $conn->prepare($sql);

try {
    foreach ($posti as $i => $p) {
        $fila = substr($p, 0, 1);
        $numero = intval(substr($p, 1));
        $stmt->execute([
            ':numProiezione' => $proiezioneId,
            ':numFila' => $fila,
            ':numPosto' => $numero,
            ':prezzo' => $prezzi[$i],
            ':email' => $email
        ]);
    }

    echo json_encode([
        'status' => 'ok',
        'debug' => [
            'proiezioneId' => $proiezioneId,
            'posti' => $posti,
            'prezzi' => $prezzi,
            'email' => $email
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Errore DB',
        'error' => $e->getMessage()
    ]);
}
