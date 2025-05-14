<?php include 'connect.php'; ?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#580000">
  <title>CineCraft - Prenotazione Biglietti</title>
  <link rel="icon" href="Icon.ico" type="image/x-icon">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="sale.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script defer src="js/nav.js"></script>
  <script defer src="sale.js"></script>
</head>

<body>
  <?php include 'header.php'; ?>
  <?php include 'nav.html'; ?>

  <div class="right-content">
    <section id="filtro" class="search-section">
      <div class="container">
        <form class="search-form" method="GET" action="">
          <div class="form-group">
            <label for="sala"><i class="fas fa-theater-masks"></i> Sala:</label>
            <select id="sala" name="sala" class="sala">
              <option value="">Seleziona una sala</option>
              <?php
              $stmt = $conn->query("SELECT numero, tipo FROM Sala");
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . (int)$row['numero'] . '">'
                  . 'Sala ' . htmlspecialchars($row['numero']) . ' – '
                  . htmlspecialchars($row['tipo']) . '</option>';
              }
              ?>
            </select>
          </div>
          <button type="submit" class="btn-search2"><i class="fas fa-search"></i></button>
        </form>
      </div>
    </section>

    <main class="container">
      <div class="main-content" id="sala-wrapper" style="display: none;">
        <div class="screen">SCHERMO</div>
        <div class="seats-grid"></div>
      </div>
    </main>
  </div>

  <?php
    include 'footer.html';
    ?>
    <script src="footer.js"></script>
</body>

</html>