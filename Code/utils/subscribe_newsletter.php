<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? null;

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'invalid_email']);
    exit;
}

$subject = "🎉 Benvenuto nella Newsletter di CodeCrafter!";
$message = "
<html>
<head>
  <meta charset='UTF-8'>
  <title>Iscrizione Newsletter</title>
</head>
<body>
  <h2 style='color:#580000;'>Grazie per esserti iscritto!</h2>
  <p>Hai appena ricevuto le ultime novità e promozioni di <strong>CodeCrafter</strong> direttamente nella tua casella.</p>
  <p style='margin-top:20px;'>Buona visione e a presto! 🍿</p>
</body>
</html>
";

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: CodeCrafter <no-reply@codecrafter.it>\r\n";

if (mail($email, $subject, $message, $headers)) {
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error']);
}
