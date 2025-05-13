<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$nomeUtente = $_SESSION['nome'];
$mailUtente = $_SESSION['mail'];

include 'connect.php';
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#580000">
  <title>CineCraft - I tuoi Biglietti</title>
  <link rel="icon" href="Icon.ico" type="image/x-icon">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="utente.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="js/nav.js"></script>
</head>

<body>
  <?php
  include 'header.php';
  include 'nav.html';
  ?>

  <main class="content">
    <div class="user-card">
      <h1>Informazioni profilo</h1>
      <div class="avatar">
        <img src="https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010">
      </div>
      <div class="user-info">
        <p><strong>Nome:</strong> <?= htmlspecialchars($nomeUtente) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($mailUtente) ?></p>
      </div>
    </div>

    <div class="ticket-list">
      <h1>Tutti i biglietti prenotati</h1>
      <?php
      require 'connect.php';
      setlocale(LC_TIME, 'it_IT.UTF-8');
      date_default_timezone_set('Europe/Rome');

      $stmt = $conn->query("
        SELECT 
          B.numProiezione,
          B.numFila,
          B.numPosto,
          B.dataVendita,
          B.prezzo,
          B.email,
          P.sala
        FROM Biglietto B
        JOIN Proiezione P ON B.numProiezione = P.numProiezione
        WHERE B.email = '$mailUtente'
        ORDER BY B.dataVendita DESC
      ");
      $biglietti = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (count($biglietti) > 0) {
        echo "<table>";
        echo "<thead>
                <tr>
                  <th>Proiezione</th>
                  <th>Sala</th>
                  <th>Fila</th>
                  <th>Posto</th>
                  <th>Data Acquisto</th>
                  <th>Prezzo</th>
                  <th>Email</th>
                </tr>
              </thead>
              <tbody>";

        $formatter = new IntlDateFormatter('it_IT', IntlDateFormatter::LONG, IntlDateFormatter::NONE);

        foreach ($biglietti as $b) {
          $dataFormattata = $formatter->format(new DateTime($b['dataVendita']));
          $prezzoFormattato = '€' . number_format($b['prezzo'], 2, ',', '');

          echo "<tr>
          <td>{$b['numProiezione']}</td>
          <td>{$b['sala']}</td>
          <td>{$b['numFila']}</td>
          <td>{$b['numPosto']}</td>
          <td>{$dataFormattata}</td>
          <td>{$prezzoFormattato}</td>
          <td>{$b['email']}</td>
        </tr>";
        }

        echo "</tbody></table>";
      } else {
        echo "<p>Nessun biglietto acquistato.</p>";
      }
      ?>
    </div>
  </main>

  <?php include 'footer.html'; ?>
</body>

</html>