<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#580000">
  <title>CineCraft - Prenotazione Biglietti</title>
  <link rel="icon" href="Icon.ico" type="image/x-icon">
  <link rel="stylesheet" href="film.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <?php
  include 'header.html';
  include 'nav.html';
  ?>
  <div class="right-content">
    <div class="hero-banner">
      <div class="hero-content">
        <h2>Consulta e scegli i film che vuoi vedere</h2>
        <p>Seleziona la data e scegli il film</p>
      </div>
    </div>
    <div class="container">
      <div class="centralbar">
        <div class="booking-steps">
          <div class="step">
            <label for="start-date">Data iniziale:</label>
            <input type="date" id="start-date" name="start-date">
          </div>
          <div class="step">
            <label for="end-date">Data finale:</label>
            <input type="date" id="end-date" name="end-date">
          </div>
        </div>
      </div>
      <div class="movies-grid">
        <?php
        include 'connect.php';

        $imgData = json_decode(file_get_contents('film_images.json'), true);
        $startOfWeek = strtotime('monday this week');
        $endOfWeek = strtotime('sunday this week');
        $dates = [];

        for ($i = $startOfWeek; $i <= $endOfWeek; $i = strtotime("+1 day", $i)) {
          $date = date("d/m/Y", $i);
          $dayOfWeek = date('N', $i);
          if ($dayOfWeek != 2 && $dayOfWeek != 3) {
            $dates[] = $date;
          }
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
              echo '
              <article class="movie-card">
                  <div class="movie-poster" style="background-image: url(\'' . $filmData['imgUrl'] . '\')"></div>
                  <div class="movie-info">
                      <h3>' . htmlspecialchars($filmData['titolo']) . '</h3>
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
  include 'footer.html';
  ?>
</body>

</html>