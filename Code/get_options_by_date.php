<?php
include 'connect.php';

$dataSelezionata = $_GET['date'] ?? null;

if ($dataSelezionata) {
    $stmt = $conn->prepare("SELECT DISTINCT p.filmProiettato, f.titolo
                            FROM Proiezione p
                            JOIN Film f ON f.codice = p.filmProiettato
                            WHERE p.data = :data");
    $stmt->execute(['data' => $dataSelezionata]);
    $film = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT DISTINCT s.numero, s.tipo
                            FROM Sala s
                            JOIN Proiezione p ON p.sala = s.numero
                            WHERE p.data = :data");
    $stmt->execute(['data' => $dataSelezionata]);
    $sale = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT DISTINCT p.ora
                            FROM Proiezione p
                            WHERE p.data = :data");
    $stmt->execute(['data' => $dataSelezionata]);
    $orari = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['film' => $film, 'sale' => $sale, 'orari' => $orari]);
} else {
    echo json_encode(['error' => 'Data non selezionata']);
}
?>
