<?php
session_start();

$filmId = isset($_GET['film']) ? intval($_GET['film']) : ($_SESSION['film'] ?? 0);
$dateParam = isset($_GET['date']) ? $_GET['date'] : ($_SESSION['date'] ?? '');
$timeParam = isset($_GET['orario']) ? substr($_GET['orario'], 0, 5) : ($_SESSION['orario'] ?? '');
$salaParam = isset($_GET['sala']) ? $_GET['sala'] : ($_SESSION['sala'] ?? '');

if (
  (!isset($_GET['film']) || !isset($_GET['date']) || !isset($_GET['orario']) || !isset($_GET['sala'])) &&
  $filmId && $dateParam && $timeParam && $salaParam
) {

  $redirectUrl = "biglietti.php?film=$filmId&date=$dateParam&orario=$timeParam&sala=$salaParam";
  header("Location: $redirectUrl");
  exit;
}

if (isset($_GET['film'])) {
  $_SESSION['film'] = $filmId;
}
if (isset($_GET['date'])) {
  $_SESSION['date'] = $dateParam;
}
if (isset($_GET['orario'])) {
  $_SESSION['orario'] = substr($_GET['orario'], 0, 5);
}
if (isset($_GET['sala'])) {
  $_SESSION['sala'] = $salaParam;
}

include 'connect.php';
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
  <link href="https://cdn.jsdelivr.net/npm/flat-icons/css/flat-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const userMail = <?= isset($_SESSION['mail']) ? json_encode($_SESSION['mail']) : 'null' ?>;
  </script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>
  <script src="biglietti.js"></script>
  <script src="js/nav.js"></script>
</head>

<body>
  <?php
  include 'header.php';
  include 'nav.html';
  ?>
  <div class="right-content">
    <div class="container">

      <section id="filtro" class="search-section">
        <div class="centralbar">
          <div class="booking-steps">
            <div class="step<?= ($filmId > 0) ? ' completed' : '' ?>">1. Scegli il film</div>
            <div class="step<?= ($filmId > 0 && !empty($dateParam)) ? ' completed' : '' ?>">2. Seleziona orario</div>
            <div class="step<?= ($filmId > 0 && !empty($dateParam)) ? ' completed' : '' ?>">3. Scegli i posti</div>
            <div class="step<?= ($filmId > 0 && !empty($dateParam)) ? ' completed' : '' ?>">4. Pagamento</div>
          </div>
      </section>

      <div class="row row-top">
        <div class="movie-selection">
          <div class="movie-card selected-movie">
            <img src="<?= htmlspecialchars($posterUrl) ?>" alt="Locandina <?= htmlspecialchars($filmData['titolo']) ?>">
          </div>
        </div>

        <div class="datetime-picker">
          <div class="movie-title">
            <h3>Titolo:</h3>
            <span><?= htmlspecialchars($filmData['titolo']) ?></span>
          </div>
          <div class="movie-language">
            <h3>Lingua:</h3>
            <span class="rating"><?= htmlspecialchars($filmData['lingua']) ?></span>
          </div>
          <div class="movie-duration">
            <h3>Durata:</h3>
            <span class="duration"><?= htmlspecialchars($filmData['durata']) ?> min</span>
          </div>
          <div class="data-picker">
            <h3>Giorno:</h3>
            <span id="data" class="selected-date"><?= htmlspecialchars($dateParam) ?></span>
          </div>
          <div class="sala-picker">
            <h3>Orari disponibili:</h3>
          </div>
          <div class="time-slots">
            <?php if (count($times)): ?>
              <?php foreach ($times as $t):
                $tShort = substr($t, 0, 5); ?>
                <button class="time-slot<?= $tShort === substr($timeParam, 0, 5) ? ' selected' : '' ?>"
                  data-time="<?= htmlspecialchars($tShort) ?>">
                  <?= $tShort ?>
                </button>
              <?php endforeach; ?>
            <?php else: ?>
              <p>Nessun orario disponibile per questa data.</p>
            <?php endif; ?>
          </div>
          <div class="sala-picker">
            <h3>Sala:</h3>
            <span id="sala" class="selected-date"><?= htmlspecialchars($salaParam) ?></span>
          </div>
        </div>
      </div>

      <div class="row row-middle">
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
      </div>

      <div class="row row-bottom">
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

  </div>
  <?php
    include 'footer.html';
    ?>
    <script src="footer.js"></script>
</body>

</html>