<?php
require 'connect.php';

$proiezioneId = isset($_GET['proiezione']) ? $_GET['proiezione'] : '';
if (!$proiezioneId) {
    echo json_encode(['errore' => 'ID mancante']);
    exit;
}

$stmt = $conn->prepare("SELECT numFila, numPosto FROM Biglietto WHERE numProiezione = ?");
$stmt->execute([$proiezioneId]);

$posti = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $fila = strtoupper($row['numFila']);
    $posto = intval($row['numPosto']);
    $posti[] = "{$fila}{$posto}";
}

echo json_encode($posti);
