<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'connect.php';

try {
    // 1) leggi tutte le sale
    $roomsStmt = $conn->query("
        SELECT numero, tipo, numFile, numPostiPerFila
        FROM Sala
    ");
    $rooms = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($rooms as $r) {
        // 2) per ogni sala, i posti occupati
        $occStmt = $conn->prepare("
            SELECT fila, colonna
            FROM Occupazione
            WHERE sala = ?
        ");
        $occStmt->execute([ $r['numero'] ]);
        $occ = $occStmt->fetchAll(PDO::FETCH_ASSOC);

        // trasforma in ["A1","B2",...]
        $occupiedSeats = array_map(function($o) {
            return sprintf(
                "%s%d",
                chr(64 + $o['fila']),
                $o['colonna']
            );
        }, $occ);

        $data[] = [
            'id'               => (int)$r['numero'],
            'name'             => $r['tipo'],
            'numFile'          => (int)$r['numFile'],
            'numPostiPerFila'  => (int)$r['numPostiPerFila'],
            'occupiedSeats'    => $occupiedSeats,
        ];
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
