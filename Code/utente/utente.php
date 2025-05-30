<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: ../login_logout/login.php');
  exit;
}

$nomeUtente = $_SESSION['nome'];
$mailUtente = $_SESSION['mail'];

include '../utils/connect.php';
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#580000">
  <title>CodeCrafters - I tuoi Biglietti</title>
  <link rel="icon" href="../utils/Icon.ico" type="image/x-icon">
  <link rel="stylesheet" href="../nav_header_footer/style.css">
  <link rel="stylesheet" href="utente.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../nav_header_footer/nav.js"></script>
  <script src="utente.js"></script>
</head>

<body>
  <?php
  include '../nav_header_footer/header.php';
  include '../nav_header_footer/nav.html';
  ?>

  <main class="right-content">
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
      <h1 class="ticket-title">Tutti i biglietti prenotati</h1>
      <?php
      include '../utils/connect.php';
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
                  <th></th>
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
          <td>
            <form method='POST' action='../utils/elimina_biglietto.php' class='delete-form'>
              <input type='hidden' name='numProiezione' value='{$b['numProiezione']}'>
              <input type='hidden' name='numFila' value='{$b['numFila']}'>
              <input type='hidden' name='numPosto' value='{$b['numPosto']}'>
              <button type='submit' class='btn-icon' title='Elimina biglietto'>
                <img src='https://cdn-icons-png.flaticon.com/128/3976/3976961.png' alt='Elimina'>
              </button>
            </form>
          </td>
        </tr>";
        }

        echo "</tbody></table>";
      } else {
        echo "<p>Nessun biglietto acquistato.</p>";
      }
      ?>
    </div>
  </main>

  <?php
  include '../nav_header_footer/footer.html';
  ?>
  <script src="../nav_header_footer/footer.js"></script>
  <?php if (isset($_GET['deleted'])): ?>
    <script>
      localStorage.setItem('showDeleteAlert', '1');
      if (history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('deleted');
        history.replaceState(null, '', url);
      }
    </script>
  <?php endif; ?>

</body>


</html>