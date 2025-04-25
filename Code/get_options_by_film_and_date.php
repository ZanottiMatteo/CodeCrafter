<?php
include 'connect.php';

$filmId = $_GET['film_id'] ?? null;
$data = $_GET['data'] ?? null;

if ($filmId && $data) {
    $stmt = $conn->prepare("SELECT DISTINCT s.numero, s.tipo
                            FROM Sala s
                            JOIN Proiezione p ON p.sala = s.numero
                            WHERE p.filmProiettato = :film_id AND p.data = :data");
    $stmt->execute(['film_id' => $filmId, 'data' => $data]);
    $sale = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT DISTINCT p.ora
                            FROM Proiezione p
                            WHERE p.filmProiettato = :film_id AND p.data = :data");
    $stmt->execute(['film_id' => $filmId, 'data' => $data]);
    $orari = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['sale' => $sale, 'orari' => $orari]);
}
else {
    echo json_encode(['error' => 'Nessun parametro fornito']);
}
?>
