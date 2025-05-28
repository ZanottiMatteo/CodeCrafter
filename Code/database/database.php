<?php
session_start();
$isAdmin = (isset($_SESSION['mail']) && $_SESSION['mail'] === 'admin@gmail.com');
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#580000">
    <title>CodeCrafters - Prenotazione Biglietti</title>
    <link rel="icon" href="../utils/Icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="database.css">
    <link rel="stylesheet" href="../nav_header_footer/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="database.js"></script>
    <script defer src="../nav_header_footer/nav.js"></script>
</head>

<body>
    <?php
    include '../nav_header_footer/header.php';
    include '../nav_header_footer/nav.html';
    ?>

    <div class="right-content">
        <section id="filtro" class="search-section">
            <div class="centralbar">
                <div class="db-tabs">
                    <div class="db-tab active" data-table="Biglietto">Biglietto</div>
                    <div class="db-tab" data-table="Film">Film</div>
                    <div class="db-tab" data-table="Proiezione">Proiezione</div>
                    <div class="db-tab" data-table="Sala">Sala</div>
                    <div class="db-tab" data-table="Utente">Utente</div>
                </div>
            </div>
        </section>

        <div class="content">
            <?php if ($isAdmin): ?>
                <div class="db-table-container" id="tab-Biglietto" style="display:none;">
                    <h2 class="db-title">Tabella Biglietto</h2>
                </div>
                <div class="db-table-container" id="tab-Film" style="display:none;">
                    <h2 class="db-title">Tabella Film</h2>
                </div>
                <div class="db-table-container" id="tab-Proiezione" style="display:none;">
                    <h2 class="db-title">Tabella Proiezione</h2>
                </div>
                <div class="db-table-container" id="tab-Sala" style="display:none;">
                    <h2 class="db-title">Tabella Sala</h2>
                </div>
                <div class="db-table-container" id="tab-Utente" style="display:none;">
                    <h2 class="db-title">Tabella Utente</h2>
                </div>
            <?php else: ?>
                <div class="db-denied">
                    <h2>Accesso negato</h2>
                    <p>Solo l'amministratore può visualizzare questi dati.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="loading-overlay" style="display:none;">
        <div id="loading-box">
            <div id="loading-bar">
                <div id="loading-progress"></div>
            </div>
            <p>Caricamento dati in corso…</p>
        </div>
    </div>

    <?php include '../nav_header_footer/footer.html'; ?>
    <script src="../nav_header_footer/footer.js"></script>
</body>

</html>