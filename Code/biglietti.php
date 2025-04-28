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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="biglietti.js"></script>
  <script src="js/nav.js"></script>
</head>

<body>
  <?php
  include 'header.html';
  ?>
  <?php
  include 'nav.html';
  ?>
  <div class="right-content">
    <div class="hero-banner">
      <div class="hero-content">
          <h2>Acquista i bigletti per i migliori Film</h2>
          <p>Seleziona il film, scegli l’orario e prenota il tuo posto in sala</p>
      </div>
    </div>
    <div class="container">
      <div class="centralbar">
        <div class="booking-steps">
          <div class="step active">1. Scegli il film</div>
          <div class="step">2. Seleziona orario</div>
          <div class="step">3. Scegli i posti</div>
          <div class="step">4. Pagamento</div>
        </div>
      </div>

      <div class="movie-selection">
        <div class="movie-card">
          <img src="" alt="Locandina film">
          <h3>Avatar: La via dell'acqua</h3>
          <div class="movie-info">
            <span class="rating">PG-13</span>
            <span class="duration">192 min</span>
          </div>
          <button class="select-movie">Seleziona</button>
        </div>
        <!-- Aggiungi più card film qui -->
      </div>

      <div class="datetime-picker">
        <div class="data-picker">
          <h3>Giorno:</h3>
          <input type="text" id="data" name="data" placeholder="Seleziona una data" readonly>
        </div>

        <h3>Orari disponibili:</h3>
        <div class="time-slots">
          <button class="time-slot">14:30</button>
      
        </div>
      </div>

      <div class="main-content">
        <div class="screen">SCHERMO</div>
        <div class="seat-legend">
          <div class="legend-item">
            <div class="seat-sample available"></div>
            <span>Disponibile</span>
          </div>
          <div class="legend-item">
            <div class="seat-sample selected"></div>
            <span>Selezionato</span>
          </div>
          <div class="legend-item">
            <div class="seat-sample occupied"></div>
            <span>Occupato</span>
          </div>
          <div class="legend-item">
            <div class="seat-sample vip"></div>
            <span>VIP</span>
          </div>
        </div>

        <div class="seats-grid">
          <!-- Posti generati dinamicamente via JavaScript -->
        </div>
      </div>

      <div class="cart-summary">
        <h3>Il tuo ordine:</h3>
        <ul class="selected-seats"></ul>
        <div class="total-price">
          <span class="rating">Totale (€):</span>
          <span id="total"> 0.00</span>
        </div>
        <div class="promo-section">
          <input type="text" placeholder="Inserisci codice promozionale">
          <button class="apply-promo">Applica</button>
        </div>
        <button class="checkout-button">Procedi al pagamento</button>
      </div>
    </div>
  </div>
  <?php
  include 'footer.html';
  ?>
</body>