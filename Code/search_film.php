<?php
include 'connect.php';

if (isset($_GET['term'])) {
    $term = $_GET['term'];

    try {
        $stmt = $conn->prepare("SELECT titolo FROM Film WHERE titolo LIKE :term LIMIT 10");
        $stmt->execute(['term' => '%' . $term . '%']);

        $film = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $film[] = $row['titolo'];
        }

        echo json_encode($film);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
