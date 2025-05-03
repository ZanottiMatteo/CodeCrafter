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
  <script src="js/nav.js"></script>
  
</head>

<body>
  <?php
  include 'header.html';
  include 'nav.html';
  ?>
  <div class="right-content">
    <div class="hero-banner">
      <div class="hero-content">
        <h2>Scopri le sale che ci sono</h2>
        <p>Tradizionali e 3-D</p>
      </div>
    </div>

    <section id="filtro" class="search-section">
      <div class="container">
        <form class="search-form">
          <div class="form-group">
            <label for="sala"><i class="fas fa-theater-masks"></i> Sala:</label>
            <select id="sala" name="sala" class="sala">
              <option value="">Seleziona una sala</option>
              <option value="3-D">3-D</option>
              <option value="tradizionale">Tradizionale</option>
            </select>
          </div>
          <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
        </form>
      </div>
    </section>
    <main class="container">
      <section class="now-showing">
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
        <!-- qui metti i dati dinamici -->
        <div
          class="seats-grid"
          data-rows="<%= sala.numFile %>"
          data-seats-per-row="<%= sala.numPostiPerFila %>"></div>

      </section>
    </main>
  </div>
  <?php
  include 'footer.html';
  ?>
</body>

<script src="sale.js"></script>