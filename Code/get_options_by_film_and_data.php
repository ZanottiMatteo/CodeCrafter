<?php
include 'connect.php';

$filmId = $_GET['film_id'] ?? null;
$data = $_GET['data'] ?? null;

if ($filmId && $data) {
    

    echo json_encode(['sale' => $sale, 'orari' => $orari]);
} else {
    echo json_encode(['error' => 'Film non selezionato']);
}
?>
