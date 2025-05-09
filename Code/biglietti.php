<?php
$filmId = isset($_GET['film']) ? intval($_GET['film']) : 0;
$dateParam = isset($_GET['date']) ? $_GET['date'] : '';
$timeParam = isset($_GET['orario']) ? $_GET['orario'] : '';

require 'connect.php';
$stmt = $conn->prepare(
  "SELECT titolo, durata, lingua
     FROM Film
     WHERE codice = :id"
);
$stmt->bindValue(':id', $filmId, PDO::PARAM_INT);
$stmt->execute();
$filmData = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['titolo' => '', 'durata' => '', 'lingua' => ''];

$imgData = json_decode(file_get_contents('film_images.json'), true);
$posterUrl = $imgData[$filmId] ?? 'default.jpg';

$times = [];
if ($filmId && $dateParam) {
  $stmt = $conn->prepare(
    "SELECT ora
         FROM Proiezione
         WHERE filmProiettato = :id
           AND data = :date
         ORDER BY ora ASC"
  );
  $stmt->bindValue(':id', $filmId, PDO::PARAM_INT);
  $stmt->bindValue(':date', $dateParam);
  $stmt->execute();
  $times = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#580000">
  <title>CineCraft - Prenotazione Biglietti</title>
  <link rel="icon" href="Icon.ico" type="image/x-icon">
  <link rel="stylesheet" href="biglietti.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link href="https://cdn.jsdelivr.net/npm/flat-icons/css/flat-icons.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="biglietti.js"></script>
  <script src="js/nav.js"></script>
</head>

<body>
  <?php
  include 'header.php';
  include 'nav.html';
  ?>
  <div class="right-content">
    <div class="hero-banner">
      <div class="hero-content">
        <h2>Acquista i biglietti per i migliori film</h2>
        <p>Seleziona il film, scegli l’orario e prenota il tuo posto in sala</p>
      </div>
    </div>

    <div class="container">
      <div class="centralbar">
        <div class="booking-steps">
          <div class="step completed">1. Scegli il film</div>
          <div class="step<?= $timeParam ? ' completed' : '' ?>">2. Seleziona orario</div>
          <div class="step">3. Scegli i posti</div>
          <div class="step">4. Pagamento</div>
        </div>
      </div>

      <div class="movie-selection">
        <div class="movie-card selected-movie">
          <img src="<?= htmlspecialchars($posterUrl) ?>" alt="Locandina <?= htmlspecialchars($filmData['titolo']) ?>">
          <h3><?= htmlspecialchars($filmData['titolo']) ?></h3>
          <div class="movie-info">
            <span class="rating"><?= htmlspecialchars($filmData['lingua']) ?></span>
            <span class="duration"><?= htmlspecialchars($filmData['durata']) ?> min</span>
          </div>
        </div>
      </div>

      <div class="datetime-picker">
        <div class="data-picker">
          <h3>Giorno:</h3>
          <input type="text" id="data" name="date" value="<?= htmlspecialchars($dateParam) ?>"
            placeholder="Seleziona una data" readonly>
        </div>

        <h3>Orari disponibili:</h3>
        <div class="time-slots">
          <?php if (count($times)): ?>
            <?php foreach ($times as $t): ?>
              <button type="button" class="time-slot<?= $t === $timeParam ? ' selected' : '' ?>"
                data-time="<?= htmlspecialchars($t) ?>">
                <?= substr($t, 0, 5) ?>
              </button>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Nessun orario disponibile per questa data.</p>
          <?php endif; ?>
        </div>
      </div>

      <div class="main-content">
        <div class="screen">SCHERMO</div>
        <div class="seat-legend">
          <div class="legend-item">
            <div class="seat-sample available"></div><span>Disponibile</span>
          </div>
          <div class="legend-item">
            <div class="seat-sample selected"></div><span>Selezionato</span>
          </div>
          <div class="legend-item">
            <div class="seat-sample occupied"></div><span>Occupato</span>
          </div>
          <div class="legend-item">
            <div class="seat-sample vip"></div><span>VIP</span>
          </div>
        </div>
        <div class="seats-grid"></div>
      </div>

      <div class="cart-summary">
        <h3>Il tuo ordine:</h3>
        <ul class="selected-seats"></ul>
        <div class="total-price"><span>Totale (€):</span><span id="total">0.00</span></div>
        <div class="promo-section">
          <input type="text" placeholder="Inserisci codice promozionale">
          <button class="apply-promo">Applica</button>
        </div>
        <button class="checkout-button">Procedi al pagamento</button>
      </div>
    </div>
  </div>
  <?php include 'footer.html'; ?>
</body>

</html>