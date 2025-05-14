<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || empty($data['email'])) {
    echo json_encode(['icon' => 'error', 'title' => 'Errore', 'message' => 'Email mancante']);
    exit;
}

include 'connect.php';

$email = $data['email'];

$stmt = $conn->prepare("SELECT id, nome FROM Utente WHERE mail = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $token = bin2hex(random_bytes(32));
    $expire = date('Y-m-d H:i:s', time() + 3600);

    $update = $conn->prepare("UPDATE Utente SET reset_token = :token, reset_expire = :expire WHERE id = :id");
    $update->execute([
        'token' => $token,
        'expire' => $expire,
        'id' => $user['id']
    ]);

    $resetLink = "https://rikuzz.altervista.org/reset_password.php?token=$token";

    $subject = "Recupero Password - CineCraft";
    $message = "Ciao {$user['nome']},\n\nAbbiamo ricevuto una richiesta per reimpostare la tua password.\n\nPer procedere clicca sul link seguente (valido per 1 ora):\n$resetLink\n\nSe non hai richiesto nulla, puoi ignorare questa email.";
    $headers = "From: no-reply@cinecraft.it";

    if (mail($email, $subject, $message, $headers)) {
        echo json_encode([
            'icon' => 'success',
            'title' => 'Email inviata',
            'message' => 'Controlla la tua casella di posta per reimpostare la password'
        ]);
    } else {
        echo json_encode([
            'icon' => 'error',
            'title' => 'Errore invio',
            'message' => 'Errore durante l\'invio dell\'email'
        ]);
    }
} else {
    echo json_encode([
        'icon' => 'error',
        'title' => 'Utente non trovato',
        'message' => 'Nessun account è registrato con questa email'
    ]);
}
