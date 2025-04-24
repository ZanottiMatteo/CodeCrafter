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
</head>

<body>
  <?php
  include 'header.html';
  ?>
  <?php
  include 'nav.html';
  ?>
  <div class="right-content">
    <div class="container">
        <div class="centralbar">
            <h2>Segli i tuoi posti in sala</h2>
            <ul>
                <li><input type="checkbox"> Prezzi</li>
                <li><input type="checkbox" checked> Legenda</li>
                <li><input type="checkbox"> Carrello</li>
            </ul>
        </div>

        <div class="main-content">
            <div class="screen">SCHERMO</div>
            <div class="seat-selection">
                <div class="seats-grid">
                    <!-- Ripeti questo blocco per ogni posto -->
                    <div class="seat">A1</div>
                    <div class="seat">A2</div>
                    <div class="seat">A3</div>
                    <div class="seat">A4</div>
                    <div class="seat">A5</div>
                    <div class="seat">A6</div>
                    <div class="seat">A7</div>
                    <div class="seat">A8</div>
                    <div class="seat">A9</div>
                    <div class="seat">A10</div>
                    <div class="seat">A11</div>
                    <div class="seat">A12</div>
                    <div class="seat">A13</div>
                    <div class="seat">A14</div>
                    <div class="seat">A15</div>
                    <div class="seat">A16</div>
                    <div class="seat">A17</div>
                    <div class="seat">A18</div>
                    <div class="seat">A19</div>
                    <div class="seat">A20</div>
                    <!-- Aggiungi più posti qui -->
                </div>
            </div>
        </div>
        <button class="cart-button">Vai sul carrello per confermarli</button>
    </div>
  </div>
  <?php
  include 'footer.html';
  ?>
</body>