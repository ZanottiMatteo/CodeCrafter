<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);


$email = $_SESSION['mail'] ?? $data['email'] ?? null;

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'invalid_email']);
    exit;
}

$subject = "🎫 Conferma Prenotazione - CodeCrafter";

$message = "
<html>
<head>
  <meta charset='UTF-8'>
  <title>Conferma Prenotazione</title>
</head>
<body>
  <h2 style='color:#580000;'>Conferma Prenotazione</h2>
  <p><strong>Film:</strong> {$data['film']}</p>
  <p><strong>Orario:</strong> {$data['orario']}</p>
  <p><strong>Posti:</strong> {$data['posti']}</p>
  <p><strong>Totale:</strong> €{$data['totale']}</p>
  <p style='margin-top:20px;'>Grazie per aver prenotato con <strong>CodeCrafter</strong>. Buona visione! 🍿</p>
</body>
</html>
";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: CodeCrafter <no-reply@codecrafter.it>\r\n";

if (mail($email, $subject, $message, $headers)) {
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error']);
}
