<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $proiezione = $_POST['numProiezione'] ?? '';
    $fila = $_POST['numFila'] ?? '';
    $posto = $_POST['numPosto'] ?? '';
    $email = $_SESSION['mail'];

    $stmt = $conn->prepare("DELETE FROM Biglietto 
                            WHERE numProiezione = :p 
                            AND numFila = :f 
                            AND numPosto = :n 
                            AND email = :e");

    $stmt->execute([
        ':p' => $proiezione,
        ':f' => $fila,
        ':n' => $posto,
        ':e' => $email
    ]);

    header("Location: ../utente/utente.php?deleted=1");
    exit;
}
?>