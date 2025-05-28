<?php
session_start();
if (!isset($_SESSION['mail']) || $_SESSION['mail'] !== 'admin@gmail.com') {
    http_response_code(403);
    exit('Accesso negato');
}
include '../utils/connect.php';

$allowed = ['Biglietto', 'Film', 'Proiezione', 'Sala', 'Utente'];
$table = $_GET['table'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 100;
$offset = ($page - 1) * $perPage;

if (!in_array($table, $allowed)) {
    http_response_code(400);
    exit('Tabella non valida');
}

$columns = [
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

$stmtCount = $conn->query("SELECT COUNT(*) FROM `$table`");
$totalRows = intval($stmtCount->fetchColumn());
$totalPages = max(1, ceil($totalRows / $perPage));

$sql = "SELECT * FROM `$table` LIMIT $perPage OFFSET $offset";
$stmt = $conn->query($sql);

echo "<h2 class='db-title'>Tabella $table</h2>";

if ($stmt && $stmt->rowCount() > 0) {
    echo "<div class='db-table-wrap'>";
    echo "<table class='db-table'><tr>";
    foreach ($columns[$table] as $col) {
        echo "<th>$col</th>";
    }
    echo "<th></th></tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        foreach ($columns[$table] as $col) {
            echo "<td>" . htmlspecialchars($row[$col]) . "</td>";
        }
        $id = htmlspecialchars($row[$pks[$table]]);
        if (in_array($table, ['Biglietto', 'Utente'])) {
            echo "<td class='delete-cell'>
                      <button class='btn-delete' data-table='$table' data-id='$id' title='Elimina'>
                        <img src='https://cdn-icons-png.flaticon.com/128/3976/3976961.png' class='delete-icon'>
                      </button>
                    </td>";
        }
        echo "<td class='edit-cell'>
                  <button class='btn-edit' data-table='$table' data-id='$id' title='Modifica'>
                    <img src='https://cdn-icons-png.flaticon.com/128/7175/7175385.png' class='edit-icon'>
                  </button>
                </td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";

} else {
    echo "<p class='db-nodata'>Nessun dato nella tabella $table.</p>";
}

echo "<div class='pagination' data-total='$totalPages'>";
echo "<div class='page-left'>";
if ($page > 1) {
    $prev = $page - 1;
    echo "<button class='page-btn' data-page='$prev'>&laquo; Prev</button>";
}
echo "<span class='page-info'>Pagina $page di $totalPages</span>";
if ($page < $totalPages) {
    $next = $page + 1;
    echo "<button class='page-btn' data-page='$next'>Next &raquo;</button>";
}
echo "</div>";

echo "<div class='page-right'>";
echo "<label class='page-jump'>Vai a:
            <input type='number' class='page-input' min='1' max='$totalPages' value='$page' />
            <button class='page-go'>Go</button>
          </label>";
echo "</div>";
echo "</div>";


