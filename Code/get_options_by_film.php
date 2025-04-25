<?php
include 'connect.php';

$filmId = $_GET['film_id'] ?? null;

if ($filmId) {
    $stmt = $conn->prepare("SELECT DISTINCT s.numero, s.tipo
                            FROM Sala s
                            JOIN Proiezione p ON p.sala = s.numero
                            WHERE p.filmProiettato = :film_id");
    $stmt->execute(['film_id' => $filmId]);
    $sale = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT DISTINCT p.data
                            FROM Proiezione p
                            WHERE p.filmProiettato = :film_id");
    $stmt->execute(['film_id' => $filmId]);
    $date = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT DISTINCT p.ora
                            FROM Proiezione p
                            WHERE p.filmProiettato = :film_id");
    $stmt->execute(['film_id' => $filmId]);
    $orari = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['sale' => $sale, 'date' => $date, 'orari' => $orari]);
}
else {
    echo json_encode(['error' => 'Nessun parametro fornito']);
}
?>
