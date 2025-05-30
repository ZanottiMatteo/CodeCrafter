<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#580000">
  <title>CodeCrafters - Prenotazione Biglietti</title>
  <link rel="icon" href="../utils/Icon.ico" type="image/x-icon">
  <link rel="stylesheet" href="film.css">
  <link rel="stylesheet" href="../nav_header_footer/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="film.js"></script>
  <script src="../nav_header_footer/nav.js"></script>
</head>

<body>
  <?php
  include '../nav_header_footer/header.php';
  include '../nav_header_footer/nav.html';
  ?>
  <div class="right-content">
    <section id="filtro" class="search-section">
      <div class="container">
        <form class="search-form" method="get" action="">
          <div class="form-group">
            <label for="start-date"><i class="far fa-calendar-alt"></i>Data iniziale:</label>
            <input type="date" id="start-date" name="start-date" min="2021-01-01" max="2025-12-31"
              value="<?= htmlspecialchars($_GET['start-date'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label for="end-date"><i class="far fa-calendar-alt"></i>Data finale:</label>
            <input type="date" id="end-date" name="end-date" min="2021-01-01" max="2025-12-31"
              value="<?= htmlspecialchars($_GET['end-date'] ?? '') ?>">
          </div>
          <button type="submit" class="btn-search1"><i class="fas fa-search"></i></button>
        </form>
      </div>
    </section>

    <div class="container">
      <div class="movies-grid">
        <?php
        include '../utils/connect.php';

        $imgData = json_decode(file_get_contents('../utils/film_images.json'), true);
        if (!empty($_GET['start-date']) && !empty($_GET['end-date'])) {
          $startTs = strtotime($_GET['start-date']);
          $endTs = strtotime($_GET['end-date']);
          if ($startTs > $endTs) {
            list($startTs, $endTs) = [$endTs, $startTs];
          }
        } else {
          $startTs = strtotime('monday this week');
          $endTs = strtotime('sunday this week');
        }

        $dates = [];
        for ($ts = $startTs; $ts <= $endTs; $ts = strtotime('+1 day', $ts)) {
          $dow = (int) date('N', $ts);

          if ($dow === 2 || $dow === 3) {
            continue;
          }

          $dates[] = date('d/m/Y', $ts);
        }

        foreach ($dates as $date) {
          $sql = "
            SELECT Film.titolo, Film.codice
            FROM Film
            JOIN Proiezione ON Film.codice = Proiezione.filmProiettato
            WHERE Proiezione.data = :date
          ";

          $stmt = $conn->prepare($sql);
          $stmt->bindParam(':date', $date);
          $stmt->execute();

          $films = [];
          while ($film = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $titolo = $film['titolo'];
            $codice = $film['codice'];
            $imgUrl = $imgData[$codice] ?? 'default.jpg';

            if (!isset($films[$codice])) {
              $films[$codice] = [
                'titolo' => $titolo,
                'imgUrl' => $imgUrl,
                'codice' => $codice
              ];
            }
          }
          echo '<div class="day-section">';
          echo '<h3>' . htmlspecialchars($date) . '</h3>';
          if (!empty($films)) {
            echo '<div class="carousel-wrapper">';
            echo '<button class="carousel-btn prev" aria-label="Precedente">‹</button>';
            echo '<div class="movie-carousel">';
            foreach ($films as $filmData) {
              $titolo = htmlspecialchars($filmData['titolo']);
              $filmCodice = urlencode($filmData['codice']);
              $imgUrl = htmlspecialchars($filmData['imgUrl']);
              $urlDate = urlencode($date);

              echo '<article class="movie-card">';

              $today = (new DateTime())->setTime(0, 0, 0);
              $filmDay = DateTime::createFromFormat('d/m/Y', $date);

              if ($filmDay && $filmDay >= $today) {
                echo '
                <a href="../biglietti/biglietti.php?film=' . $filmCodice . '&date=' . $urlDate . '" class="ticket-btn" title="Acquista biglietto">
                  <img src="https://cdn-icons-png.flaticon.com/128/3702/3702886.png" alt="Ticket" class="ticket-icon">
                </a>
              ';
              }

              echo '
              <div class="movie-poster" style="background-image: url(\'' . $imgUrl . '\')"></div>
              <div class="movie-info">
                  <h2>' . $titolo . '</h2>
              </div>
            </article>';
            }

            echo '</div>';
            echo '<button class="carousel-btn next" aria-label="Successivo">›</button>';
            echo '</div>';
          } else {
            echo "<p>Nessun film in programmazione per il giorno " . htmlspecialchars($date) . ".</p>";
          }
          echo '</div>';
        }
        $conn = null;
        ?>
      </div>
    </div>
  </div>
  <?php
  include '../nav_header_footer/footer.html';
  ?>
  <script src="../nav_header_footer/footer.js"></script>
</body>

</html>