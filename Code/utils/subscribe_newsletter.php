<?php
// subscribe_newsletter.php
session_start();

// Legge il JSON in ingresso
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? null;

// Validazione dell'email
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'invalid_email']);
    exit;
}

// Invia una mail di benvenuto
$subject = "🎉 Benvenuto nella Newsletter di CineCraft!";
$message = "
<html>
<head>
  <meta charset='UTF-8'>
  <title>Iscrizione Newsletter</title>
</head>
<body>
  <h2 style='color:#580000;'>Grazie per esserti iscritto!</h2>
  <p>Hai appena ricevuto le ultime novità e promozioni di <strong>CineCraft</strong> direttamente nella tua casella.</p>
  <p style='margin-top:20px;'>Buona visione e a presto! 🍿</p>
</body>
</html>
";

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: CineCraft <no-reply@cinecraft.it>\r\n";

if (mail($email, $subject, $message, $headers)) {
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error']);
}
