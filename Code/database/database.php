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
    <title>CodeCrafter - Prenotazione Biglietti</title>
    <link rel="icon" href="../utils/Icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="database.css">
    <link rel="stylesheet" href="../nav_header_footer/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="database.js"></script>
    <script src="../nav_header_footer/nav.js"></script>
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
                <?php
                include '../utils/connect.php';

                $tables = [
                    "Biglietto" => ["numProiezione", "numFila", "numPosto", "dataVendita", "prezzo", "email", "id"],
                    "Film" => ["codice", "titolo", "anno", "durata", "lingua"],
                    "Proiezione" => ["numProiezione", "sala", "filmProiettato", "data", "ora"],
                    "Sala" => ["numero", "numPosti", "dim", "numFile", "numPostiPerFila", "tipo"],
                    "Utente" => ["id", "mail", "password", "nome", "reset_token", "reset_expire"]
                ];

                $pks = [
                    'Biglietto' => 'id',
                    'Film' => 'codice',
                    'Proiezione' => 'numProiezione',
                    'Sala' => 'numero',
                    'Utente' => 'id'
                ];

                foreach ($tables as $table => $columns) {
                    echo "<div class='db-table-container' id='tab-$table' style='display: none;'>";
                    echo "<h2 class='db-title'>Tabella $table</h2>";

                    $sql = "SELECT * FROM `$table`";
                    $stmt = $conn->query($sql);

                    if ($stmt && $stmt->rowCount() > 0) {
                        echo "<div class='db-table-wrap'><table class='db-table'>";
                        echo "<tr>";
                        foreach ($columns as $col) {
                            echo "<th>$col</th>";
                        }
                        echo "<th></th></tr>";

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            foreach ($columns as $col) {
                                echo "<td>" . htmlspecialchars($row[$col]) . "</td>";
                            }

                            $pkName = $pks[$table];
                            $idValue = htmlspecialchars($row[$pkName]);

                            if (in_array($table, ['Biglietto', 'Utente'])) {
                                echo "<td class='delete-cell'>
                                <button class='btn-delete' data-table='$table' data-id='$idValue' title='Elimina'>
                                <img src='https://cdn-icons-png.flaticon.com/128/3976/3976961.png' alt='Elimina' class='delete-icon'>
                                </button>
                            </td>";
                            }

                            echo "<td class='edit-cell'>
                                <button class='btn-edit' data-table='$table' data-id='$idValue' title='Modifica'>
                                <img src='https://cdn-icons-png.flaticon.com/128/7175/7175385.png' alt='Modifica' class='edit-icon'>
                                </button>
                            </td>";
                            echo "</tr>";
                        }

                        echo "</table></div>";
                    } else {
                        echo "<p class='db-nodata'>Nessun dato nella tabella $table.</p>";
                    }
                    echo "</div>";
                }
                ?>
            <?php else: ?>
                <div class="db-denied">
                    <h2>Accesso negato</h2>
                    <p>Solo l'amministratore può visualizzare questi dati.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    include '../nav_header_footer/footer.html';
    ?>
    <script src="../nav_header_footer/footer.js"></script>
</body>

</html>