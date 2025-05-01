<?php
include 'connect.php';

$filmId = $_GET['film_id'] ?? '';
$filmDate = $_GET['film_date'] ?? '';

if (!$filmId || !$filmDate) {
    http_response_code(400);
    exit('Parametri mancanti');
}

$imgJson = file_get_contents(__DIR__ . '/film_images.json');
$imgData = json_decode($imgJson, true);

$imageUrl = $imgData[$filmId] ?? 'default.jpg';

$sqlFilm = "
  SELECT 
    codice, titolo, anno, durata, lingua
  FROM Film
  WHERE codice = :codice
";
$stmtFilm = $conn->prepare($sqlFilm);
$stmtFilm->bindParam(':codice', $filmId);
$stmtFilm->execute();
$film = $stmtFilm->fetch(PDO::FETCH_ASSOC);

if (!$film) {
    echo "<p>Dettagli non disponibili.</p>";
    exit;
}

$sqlProj = "
  SELECT 
    p.data              AS proiezione_data,
    p.ora               AS proiezione_ora,
    s.numero            AS sala_numero,
    s.tipo              AS sala_tipo,
    s.dim                AS sala_dim,              
    s.numPosti           AS sala_numPosti,        
    s.numFile            AS sala_numFile,          
    s.numPostiPerFila    AS sala_numPostiPerFila 
  FROM Proiezione p
  JOIN Sala s ON p.sala = s.numero
  WHERE 
    p.filmProiettato = :codice
    AND p.data        = :date
  ORDER BY p.ora ASC
  LIMIT 10
";
$stmtProj = $conn->prepare($sqlProj);
$stmtProj->bindParam(':codice', $filmId);
$stmtProj->bindParam(':date', $filmDate);
$stmtProj->execute();
$proiezioni = $stmtProj->fetchAll(PDO::FETCH_ASSOC);

$iconMap = [
    '3-D' => 'https://cdn-icons-png.flaticon.com/128/83/83596.png',
    'tradizionale' => 'https://cdn-icons-png.flaticon.com/128/83/83467.png',
];

?>
<div class="movie-detail">
    <div class="movie-poster-detail">
        <img src="<?= htmlspecialchars($imageUrl, ENT_QUOTES) ?>"
            alt="<?= htmlspecialchars($film['titolo'], ENT_QUOTES) ?>"
            style="max-width:100%; height:auto; border-radius:8px; margin-bottom:1em;">
    </div>

    <h2><?= htmlspecialchars($film['titolo'], ENT_QUOTES) ?></h2>
    <ul class="movie-meta">
        <li><strong>Anno:</strong> <?= htmlspecialchars($film['anno']) ?></li>
        <li><strong>Durata:</strong> <?= htmlspecialchars($film['durata']) ?> min</li>
        <li><strong>Lingua:</strong> <?= htmlspecialchars($film['lingua']) ?></li>
    </ul>

    <h3>Proiezioni del <?= htmlspecialchars($filmDate) ?></h3>
    <?php if (count($proiezioni) > 0): ?>
        <ul class="showtimes-list">
            <?php foreach ($proiezioni as $p): ?>
                <li class="showtime-item" data-numero="<?= htmlspecialchars($p['sala_numero']) ?>"
                    data-tipo="<?= htmlspecialchars($p['sala_tipo']) ?>" data-dim="<?= htmlspecialchars($p['sala_dim']) ?>"
                    data-posti="<?= htmlspecialchars($p['sala_numPosti']) ?>"
                    data-file="<?= htmlspecialchars($p['sala_numFile']) ?>"
                    data-posti-fila="<?= htmlspecialchars($p['sala_numPostiPerFila']) ?>">
                    <span class="showtime-hour"><?= substr($p['proiezione_ora'], 0, 5) ?></span>
                    <span class="showtime-room">
                        Sala <?= htmlspecialchars($p['sala_numero']) ?>
                        <img src="<?= htmlspecialchars($iconMap[$p['sala_tipo']] ?? '') ?>" class="sala-icon" alt="">
                    </span>
                </li>

            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Al momento non ci sono proiezioni programmate per questa data.</p>
    <?php endif; ?>
</div>