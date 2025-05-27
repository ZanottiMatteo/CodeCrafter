<?php
session_start();
if (!isset($_SESSION['mail']) || $_SESSION['mail'] !== 'admin@gmail.com') {
    die('<h2>Accesso negato</h2><p>Solo l\'amministratore può modificare i dati.</p>');
}
require '../utils/connect.php';

$filmImages = json_decode(file_get_contents(__DIR__ . '/../utils/film_images.json'), true) ?: [];

$schema = [
    'Biglietto' => ['cols' => ['numProiezione', 'numFila', 'numPosto', 'dataVendita', 'prezzo', 'email', 'id'], 'pk' => 'id'],
    'Film' => ['cols' => ['codice', 'titolo', 'anno', 'durata', 'lingua'], 'pk' => 'codice'],
    'Proiezione' => ['cols' => ['numProiezione', 'sala', 'filmProiettato', 'data', 'ora'], 'pk' => 'numProiezione'],
    'Sala' => ['cols' => ['numero', 'numPosti', 'dim', 'numFile', 'numPostiPerFila', 'tipo'], 'pk' => 'numero'],
    'Utente' => ['cols' => ['id', 'mail', 'nome'], 'pk' => 'id'],
];

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? '';

if (!isset($schema[$table]) || $id === '') {
    die('<h2>Tabella non valida o ID mancante</h2>');
}

$cols = $schema[$table]['cols'];
$pk = $schema[$table]['pk'];

$sql = "SELECT " . implode(',', $cols) . " FROM `$table` WHERE `$pk` = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    die('<h2>Record non trovato</h2>');
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [];
    foreach ($cols as $c) {
        $raw = trim($_POST[$c] ?? '');

        if ($table === 'Proiezione' && $c === 'data') {
            $dt = DateTime::createFromFormat('Y-m-d', $raw);
            if ($dt) {
                $raw = $dt->format('d/m/Y');
            }
        }

        $values[] = $raw;
    }
    $values[] = $id;
    $setList = implode('=?,', $cols) . '=?';
    $sqlUpd = "UPDATE `$table` SET $setList WHERE `$pk` = ?";
    $upd = $conn->prepare($sqlUpd);
    if ($upd->execute($values)) {
        if ($table === 'Film' && isset($_POST['image_link'])) {
            $filmImages[$row['codice']] = trim($_POST['image_link']);
            file_put_contents(
                __DIR__ . '/../utils/film_images.json',
                json_encode($filmImages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        }
        header("Location: ../database/database.php?modifica=ok");
        exit;
    } else {
        $msg = 'Errore durante il salvataggio delle modifiche.';
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>CodeCrafter - Modifica <?= htmlspecialchars($table) ?></title>
    <link rel="icon" href="Icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../utils/edit_row.css">
</head>

<body>
    <div class="edit-container">
        <h2>Modifica <?= htmlspecialchars($table) ?></h2>
        <?php if ($msg): ?>
            <div class="error"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <form method="POST">
            <?php foreach ($cols as $col):
                $raw = $row[$col];
                $value = htmlspecialchars($raw);
                if (in_array($col, ['data', 'dataVendita'])) {
                    $dt = DateTime::createFromFormat('d/m/Y', $raw);
                    if ($dt)
                        $value = $dt->format('Y-m-d');
                }
                ?>
                <div class="form-group">
                    <label for="<?= $col ?>">
                        <?= ucfirst(str_replace('_', ' ', $col)) ?>     <?= $col === $pk ? ' (PK)' : '' ?>:
                    </label>

                    <?php if ($col === $pk): ?>
                        <input type="hidden" name="<?= $col ?>" value="<?= $value ?>">
                        <input type="text" id="<?= $col ?>" value="<?= $value ?>" readonly disabled>

                    <?php elseif ($table === 'Biglietto' && in_array($col, ['numProiezione', 'numFila', 'numPosto'])): ?>
                        <input type="hidden" name="<?= $col ?>" value="<?= $value ?>">
                        <input type="text" id="<?= $col ?>" value="<?= $value ?>" readonly disabled>

                    <?php elseif ($table === 'Sala' && in_array($col, ['numero', 'numPosti', 'numFile', 'numPostiPerFila'])): ?>
                        <input type="hidden" name="<?= $col ?>" value="<?= $value ?>">
                        <input type="text" id="<?= $col ?>" value="<?= $value ?>" readonly disabled>

                    <?php elseif ($table === 'Sala' && $col === 'dim'): ?>
                        <input type="number" id="<?= $col ?>" name="<?= $col ?>" value="<?= $value ?>" min="0" step="1"
                            required>

                    <?php elseif ($table === 'Sala' && $col === 'tipo'): ?>
                        <select id="<?= $col ?>" name="<?= $col ?>" required>
                            <option value="">-- Seleziona --</option>
                            <option value="tradizionale" <?= $value === 'tradizionale' ? 'selected' : '' ?>>Tradizionale</option>
                            <option value="3-D" <?= $value === '3-D' ? 'selected' : '' ?>>3-D</option>
                        </select>

                    <?php elseif ($table === 'Proiezione' && $col === 'sala'): ?>
                        <input type="number" id="<?= $col ?>" name="<?= $col ?>" value="<?= $value ?>" min="1" max="15"
                            required>

                    <?php elseif ($table === 'Proiezione' && $col === 'filmProiettato'): ?>
                        <input type="number" id="<?= $col ?>" name="<?= $col ?>" value="<?= $value ?>" min="1" max="100"
                            step="1" required>

                    <?php elseif ($table === 'Proiezione' && $col === 'data'): ?>
                        <input type="date" id="<?= $col ?>" name="<?= $col ?>" value="<?= $value ?>" required>

                    <?php elseif ($table === 'Proiezione' && $col === 'ora'): ?>
                        <select id="ora" name="ora" required>
                            <option value="">-- Seleziona l'orario --</option>
                            <?php foreach (['16:00:00', '18:00:00', '20:00:00', '22:00:00'] as $o): ?>
                                <option value="<?= $o ?>" <?= $value === $o ? 'selected' : '' ?>>
                                    <?= substr($o, 0, 5) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    <?php elseif ($table === 'Biglietto' && $col === 'prezzo'): ?>
                        <select id="prezzo" name="prezzo" required>
                            <option value="">-- Seleziona prezzo --</option>
                            <option value="12" <?= $value === '12' ? 'selected' : '' ?>>€12</option>
                            <option value="18" <?= $value === '18' ? 'selected' : '' ?>>€18</option>
                        </select>

                    <?php elseif (in_array($col, ['anno', 'durata', 'numPosti'])): ?>
                        <input type="number" id="<?= $col ?>" name="<?= $col ?>" value="<?= $value ?>" min="0" step="1"
                            required>

                    <?php elseif (in_array($col, ['dataVendita'])): ?>
                        <input type="date" id="<?= $col ?>" name="<?= $col ?>" value="<?= $value ?>" required>

                    <?php else: ?>
                        <input type="text" id="<?= $col ?>" name="<?= $col ?>" value="<?= $value ?>" required>

                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?php if ($table === 'Film'):
                $code = $row['codice'];
                $imgUrl = $filmImages[$code] ?? '';
                ?>
                <div class="form-group">
                    <label for="image_link">Link immagine:</label>
                    <input type="url" id="image_link" name="image_link" value="<?= htmlspecialchars($imgUrl) ?>" required>
                </div>
                <?php if ($imgUrl): ?>
                    <div class="form-group">
                        <img src="<?= htmlspecialchars($imgUrl) ?>" alt="Copertina film <?= htmlspecialchars($code) ?>"
                            style="max-width:100%;height:auto;border:1px solid #ccc;border-radius:4px;">
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="form-actions">
                <button type="submit" class="btn-save">Salva modifiche</button>
                <button type="button" class="btn-cancel" onclick="window.location.href='../database/database.php'">
                    Annulla
                </button>
            </div>
        </form>
    </div>
</body>

</html>