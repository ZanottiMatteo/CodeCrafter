<?php
include 'connect.php';

$filmId = $_GET['film_id'] ?? null;
$data = $_GET['data'] ?? null;
$ora = $_GET['ora'] ?? null;

if ($filmId && $data && $ora) {
    $stmt = $conn->prepare("SELECT s.numero, s.tipo
                            FROM Sala s
                            JOIN Proiezione p ON p.sala = s.numero
                            WHERE p.filmProiettato = :film_id AND p.data = :data AND p.ora = :ora");
    $stmt->execute(['film_id' => $filmId, 'data' => $data, 'ora' => $ora]);
    $sale = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['sale' => $sale]);

} else {
    echo json_encode(['error' => 'Nessun parametro fornito o dati incompleti']);
}
?>
