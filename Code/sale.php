<?php
require_once 'connect.php'; 
?>

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
  <script defer src="js/nav.js"></script>
  
</head>

<body>
  <?php include 'header.html'; ?>
  <?php include 'nav.html'; ?>

  <div class="right-content">
    <div class="hero-banner">
      <div class="hero-content">
        <h2>Scopri le sale che ci sono</h2>
        <p>Tradizionali e 3-D</p>
      </div>
    </div>

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
                      . 'Sala ' . htmlspecialchars($row['numero']) . '</option>';
              }
              ?>
            </select>
          </div>
          <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
        </form>
      </div>
    </section>

    <main class="container">
      <div class="main-content">
        <div class="screen">SCHERMO</div>

        <div class="seat-legend">
          <div class="legend-item"><div class="seat-sample available"></div><span>Disponibile</span></div>
          <div class="legend-item"><div class="seat-sample selected"></div><span>Selezionato</span></div>
          <div class="legend-item"><div class="seat-sample occupied"></div><span>Occupato</span></div>
          <div class="legend-item"><div class="seat-sample vip"></div><span>VIP</span></div>
        </div>

        <?php
        if (isset($_GET['sala']) && is_numeric($_GET['sala'])) {
            $numeroSala = (int)$_GET['sala'];
            $stmt = $conn->prepare("SELECT numFile, numPostiPerFila FROM Sala WHERE numero = ?");
            $stmt->execute([$numeroSala]);
            $sala = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($sala) {
                $righe = (int)$sala['numPostiPerFila'];
                $colonne = (int)$sala['numFile'];
                echo '<div class="seats-grid">';
                for ($i = 0; $i < $righe; $i++) {
                    echo '<div class="row">';
                    for ($j = 0; $j < $colonne; $j++) {
                        echo '<div class="seat available" data-row="' . ($i + 1) . '" data-col="' . ($j + 1) . '"></div>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo "<p>Sala non trovata.</p>";
            }
        }
        ?>
      </div>
    </main>
  </div>

  <?php include 'footer.html'; ?>
</body>
</html>
