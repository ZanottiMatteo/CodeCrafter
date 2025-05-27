<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['mail']) || $_SESSION['mail'] !== 'admin@gmail.com') {
    echo json_encode(['success' => false, 'message' => 'Non autorizzato']);
    exit;
}
require '../utils/connect.php';

$table = $_POST['table'] ?? '';
$idRaw = $_POST['id'] ?? '';
$allowed = ['Biglietto', 'Utente'];

if (!in_array($table, $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Tabella non consentita']);
    exit;
}

if ($table === 'Utente') {
    $stmt = $conn->prepare("DELETE FROM Utente WHERE id = ?");
    $ok = $stmt->execute([$idRaw]);

} else {
    $stmt = $conn->prepare("DELETE FROM Biglietto WHERE id = ?");
    $ok = $stmt->execute([$idRaw]);
}

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Utente eliminato con successo']);
} else {
    echo json_encode(['success' => false, 'message' => 'Errore durante eliminazione']);
}
