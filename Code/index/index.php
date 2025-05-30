<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#580000">
    <title>CodeCrafters - Home</title>
    <link rel="icon" href="../utils/Icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="../nav_header_footer/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/flat-icons/css/flat-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>
    <script src="index.js"></script>
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
                <form class="search-form" action="../biglietti/biglietti.php" method="get">
                    <div class="form-group">
                        <label for="film"><i class="fas fa-film"></i> Film:</label>
                        <input type="text" id="film" name="film" placeholder="Cerca un film..." list="film-list"
                            autocomplete="off">
                        <input type="hidden" id="film-id" name="film">
                        <div id="film-suggestions" class="film-suggestions"></div>
                    </div>

                    <div class="form-group">
                        <label for="data"><i class="far fa-calendar-alt"></i> Data:</label>
                        <input type="text" id="data" name="date" placeholder="Seleziona una data" readonly>
                    </div>

                    <div class="form-group">
                        <label for="orario"><i class="far fa-clock"></i> Orario:</label>
                        <select id="orario" name="orario">
                            <option value="">Seleziona un orario</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sala"><i class="fas fa-theater-masks"></i> Sala:</label>
                        <div id="sala" class="sala"></div>
                        <input type="hidden" id="sala-hidden" name="sala">
                    </div>
                    <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </section>
        <main class="container">
            <section class="now-showing">
                <h2><i class="fas fa-star"></i> Proiezioni consigliate</h2>
                <div class="carousel-wrapper">
                    <button class="carousel-btn prev" aria-label="Precedente">‹</button>
                    <div class="movie-carousel">
                        <?php
                        include '../utils/connect.php';

                        $imgData = json_decode(file_get_contents('../utils/film_images.json'), true);
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
                                SELECT 
                                    f.codice,
                                    f.titolo, 
                                    f.durata, 
                                    f.lingua, 
                                    f.anno, 
                                    s.numero AS sala_numero, 
                                    s.dim AS sala_dim, 
                                    s.numPosti AS sala_numPosti, 
                                    s.numFile AS sala_numFile, 
                                    s.tipo AS sala_tipo, 
                                    s.numPostiPerFila AS sala_numPostiPerFila, 
                                    p.data AS proiezione_data, 
                                    p.ora AS proiezione_ora, 
                                    p.numProiezione, 
                                    p.filmProiettato
                                FROM 
                                    Proiezione p
                                JOIN 
                                    Film f ON p.filmProiettato = f.codice
                                JOIN 
                                    Sala s ON p.sala = s.numero
                                WHERE 
                                    p.data = :date  -- Parametro per la data
                                ORDER BY 
                                    p.data ASC, p.ora ASC
                                LIMIT 1
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
                                        'codice' => $codice,
                                        'titolo' => $titolo,
                                        'imgUrl' => $imgUrl,
                                        'lingua' => $film['lingua'],
                                        'durata' => $film['durata'],
                                        'sala_numPosti' => $film['sala_numPosti'],
                                    ];
                                }
                            }
                            if (!empty($films)) {
                                foreach ($films as $filmData) {
                                    echo '
                                        <article class="movie-card">
                                            <div class="movie-poster" style="background-image: url(\'' . $filmData['imgUrl'] . '\')"></div>
                                            <div class="movie-info">
                                                <h3>' . htmlspecialchars($filmData['titolo']) . '</h3>
                                                <div class="movie-meta">
                                                    <span class="genre">' . htmlspecialchars($filmData['lingua']) . '</span>
                                                    <span class="duration">' . htmlspecialchars($filmData['durata']) . ' min</span>
                                                </div>
                                                <div class="screening-info">
                                                    <p><i class="fas fa-chair"></i> Posti disponibili: ' . htmlspecialchars($filmData['sala_numPosti']) . '</p>
                                                    <p><i class="fas fa-calendar"></i>' . htmlspecialchars($date) . '</p>
                                                </div>
                                                <div class="movie-actions">
                                                    <button
                                                        class="btn-details"
                                                        data-film-id="' . htmlspecialchars($codice, ENT_QUOTES) . '"
                                                        data-film-date="' . htmlspecialchars($date, ENT_QUOTES) . '">
                                                        <i class="fas fa-info-circle"></i> Dettagli
                                                    </button>
                                                    <a 
                                                        href="../biglietti/biglietti.php?film=' . urlencode($codice) . '&date=' . urlencode($date) . '" 
                                                        class="btn-book">
                                                        <i class="fas fa-ticket-alt"></i> Prenota
                                                    </a>
                                                </div>
                                            </div>
                                        </article>';
                                }
                            } else {
                                echo "<p>Nessun film in programmazione per il giorno " . $date . ".</p>";
                            }
                        }
                        $conn = null;
                        ?>
                        <a href="../film/film.php" class="movie-card load-more-card">
                            <div class="more-content">
                                <i class="fas fa-plus-circle"></i>
                                <span>Altri film</span>
                            </div>
                        </a>
                    </div>
                    <button class="carousel-btn next" aria-label="Successivo">›</button>
                </div>
            </section>

            <section class="promotions">
                <h2><i class="fas fa-percentage"></i> Promozioni</h2>
                <div class="promo-cards">
                    <div class="promo-card">
                        <i class="fas fa-child"></i>
                        <h3>Fedeltà</h3>
                        <p>Sconto del 10% se sei registrato al sito</p>
                    </div>
                    <div class="promo-card">
                        <i class="fas fa-user-friends"></i>
                        <h3>Gruppi</h3>
                        <p>Sconto del 20% se acquisti almeno 10 biglietti</p>
                    </div>
                    <div class="promo-card">
                        <i class="fas fa-glass-martini"></i>
                        <h3>Cibo e bevande</h3>
                        <p>Sconto del 35% se acquisti almeno un menù al bar</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <div id="movieModal" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <button id="modalClose">&times;</button>
            <div id="modalBody">
            </div>
        </div>
        <div id="salaTooltip" class="sala-tooltip" style="display:none;"></div>
    </div>

    <?php
    include '../nav_header_footer/footer.html';
    ?>
    <script src="../nav_header_footer/footer.js"></script>
</body>

