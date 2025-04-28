<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#580000">
    <title>CineCraft - Home</title>
    <link rel="icon" href="Icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/flat-icons/css/flat-icons.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>
    <script src="index.js"></script>
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
                <h2>Prenota la tua esperienza cinematografica</h2>
                <p>Scopri i migliori film in programmazione e riserva il tuo posto</p>
            </div>
        </div>

        <section id="filtro" class="search-section">
            <div class="container">
                <form class="search-form">
                    <div class="form-group">
                        <label for="film"><i class="fas fa-film"></i> Film:</label>
                        <input type="text" id="film" name="film" placeholder="Cerca un film..." list="film-list"
                            autocomplete="off">
                        <div id="film-suggestions" class="film-suggestions"></div>
                    </div>

                    <div class="form-group">
                        <label for="data"><i class="far fa-calendar-alt"></i> Data:</label>
                        <input type="text" id="data" name="data" placeholder="Seleziona una data" readonly>
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
                                                    <button class="btn-details"><i class="fas fa-info-circle"></i> Dettagli</button>
                                                    <button class="btn-book"><i class="fas fa-ticket-alt"></i> Prenota</button>
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
                        <div class="load-more">
                            <a href="film.php" class="btn-load-more">Altro...</a>
                        </div>
                    </div>
                    <button class="carousel-btn next" aria-label="Successivo">›</button>
            </section>

            <section class="promotions">
                <h2><i class="fas fa-percentage"></i> Promozioni</h2>
                <div class="promo-cards">
                    <div class="promo-card">
                        <i class="fas fa-child"></i>
                        <h3>Martedì Bambini</h3>
                        <p>Tutti i martedì, biglietto a €5 per i bambini sotto i 12 anni</p>
                    </div>
                    <div class="promo-card">
                        <i class="fas fa-user-friends"></i>
                        <h3>Gruppi</h3>
                        <p>Sconto del 20% per gruppi di almeno 10 persone</p>
                    </div>
                    <div class="promo-card">
                        <i class="fas fa-birthday-cake"></i>
                        <h3>Compleanno</h3>
                        <p>Biglietto omaggio nel tuo compleanno!</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?php
    include 'footer.html';
    ?>
</body>