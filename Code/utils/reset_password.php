<?php
require 'connect.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    die('Token mancante');
}

$stmt = $conn->prepare("SELECT id FROM Utente WHERE reset_token = :token AND reset_expire > NOW()");
$stmt->execute(['token' => $token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Token non valido o scaduto');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (strlen($password) < 6) {
        $error = "La password deve avere almeno 6 caratteri.";
    } elseif ($password !== $confirm) {
        $error = "Le password non coincidono.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE Utente SET password = :password, reset_token = NULL, reset_expire = NULL WHERE id = :id");
        $stmt->execute(['password' => $hash, 'id' => $user['id']]);
        $success = "Password aggiornata con successo. Ora puoi effettuare il login.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineCraft - Login / Signup</title>
    <link rel="icon" href="Icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="reset_password.css">
    <link rel="stylesheet" href="../nav_header_footer/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/flat-icons/css/flat-icons.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="reset-container">
        <h2>Reset Password</h2>
        <?php if (!empty($error))
            echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (!empty($success)) {
            echo "<p style='color:lime;'>$success</p><a href='../login_logout/login.php'>Torna al login</a>";
        } else { ?>
            <form method="POST">
                <input type="password" name="password" placeholder="Nuova password" required>
                <input type="password" name="confirm" placeholder="Conferma password" required>
                <button type="submit">Aggiorna Password</button>
            </form>
        <?php } ?>
    </div>
</body>

</html>