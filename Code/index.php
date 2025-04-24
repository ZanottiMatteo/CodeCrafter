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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>
    <script src="index.js"></script>
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
                        <input type="text" id="film" name="film" placeholder="Cerca un film..." list="film-list">
                        <div id="film-suggestions" class="film-suggestions"></div>
                    </div>

                    <div class="form-group">
                        <label for="data"><i class="far fa-calendar-alt"></i> Data:</label>
                        <input type="date" id="data" name="data" placeholder="Seleziona una data">
                    </div>

                    <div class="form-group">
                        <label for="orario"><i class="far fa-clock"></i> Orario:</label>
                        <select id="orario" name="orario">
                            <option value="">Seleziona un orario</option>
                            <option value="16:00">16:00</option>
                            <option value="18:00">18:00</option>
                            <option value="20:00">20:00</option>
                            <option value="22:00">22:00</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sala"><i class="fas fa-theater-masks"></i> Sala:</label>
                        <input type="text" id="sala" name="sala" placeholder="Sala">
                    </div>
                    <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </section>
        <main class="container">
            <section class="now-showing">
                <h2><i class="fas fa-star"></i> Proiezioni disponibili</h2>
                <div class="movie-grid">
                    <article class="movie-card">
                        <div class="movie-poster"
                            style="background-image: url('https://m.media-amazon.com/images/I/91vIHsL-zjL.jpg')">
                            <span class="movie-rating">4.8 <i class="fas fa-star"></i></span>
                        </div>
                        <div class="movie-info">
                            <h3>Interstellar</h3>
                            <div class="movie-meta">
                                <span class="genre">Fantascienza</span>
                                <span class="duration">169 min</span>
                            </div>
                            <div class="screening-info">
                                <p><i class="fas fa-theater-masks"></i> Sala 2 (3D)</p>
                                <p><i class="far fa-clock"></i> 20:30 - Italiano</p>
                                <p><i class="fas fa-chair"></i> Posti disponibili: 56</p>
                            </div>
                            <div class="movie-actions">
                                <button class="btn-details"><i class="fas fa-info-circle"></i> Dettagli</button>
                                <button class="btn-book"><i class="fas fa-ticket-alt"></i> Prenota</button>
                            </div>
                        </div>
                    </article>

                    <article class="movie-card">
                        <div class="movie-poster"
                            style="background-image: url('https://www.agistriveneto.it/wp-content/uploads/2023/09/locandinapg1-1.jpg')">
                            <span class="movie-rating">4.9 <i class="fas fa-star"></i></span>
                        </div>
                        <div class="movie-info">
                            <h3>Oppenheimer</h3>
                            <div class="movie-meta">
                                <span class="genre">Storico/Drammatico</span>
                                <span class="duration">180 min</span>
                            </div>
                            <div class="screening-info">
                                <p><i class="fas fa-theater-masks"></i> Sala 1 (IMAX)</p>
                                <p><i class="far fa-clock"></i> 22:00 - Inglese sott. ITA</p>
                                <p><i class="fas fa-chair"></i> Posti disponibili: 34</p>
                            </div>
                            <div class="movie-actions">
                                <button class="btn-details"><i class="fas fa-info-circle"></i> Dettagli</button>
                                <button class="btn-book"><i class="fas fa-ticket-alt"></i> Prenota</button>
                            </div>
                        </div>
                    </article>

                    <article class="movie-card">
                        <div class="movie-poster"
                            style="background-image: url('https://pad.mymovies.it/filmclub/2021/10/212/locandinapg1.jpg')">
                            <span class="movie-rating">4.5 <i class="fas fa-star"></i></span>
                        </div>
                        <div class="movie-info">
                            <h3>Dune: Parte Due</h3>
                            <div class="movie-meta">
                                <span class="genre">Fantascienza</span>
                                <span class="duration">166 min</span>
                            </div>
                            <div class="screening-info">
                                <p><i class="fas fa-theater-masks"></i> Sala 4 (Dolby Atmos)</p>
                                <p><i class="far fa-clock"></i> 21:15 - Italiano</p>
                                <p><i class="fas fa-chair"></i> Posti disponibili: 12</p>
                            </div>
                            <div class="movie-actions">
                                <button class="btn-details"><i class="fas fa-info-circle"></i> Dettagli</button>
                                <button class="btn-book"><i class="fas fa-ticket-alt"></i> Prenota</button>
                            </div>
                        </div>
                    </article>
                </div>
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