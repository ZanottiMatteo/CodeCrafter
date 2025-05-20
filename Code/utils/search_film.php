<?php
include 'connect.php';

if (isset($_GET['term'])) {
    $term = $_GET['term'];

    try {
        if (empty($term)) {
            $stmt = $conn->query("SELECT codice, titolo FROM Film");
        } else {
            $stmt = $conn->prepare("SELECT codice, titolo FROM Film WHERE titolo LIKE :term");
            $stmt->execute(['term' => '%' . $term . '%']);
        }

        $film = [];
        $images = [];
        $imageFile = 'film_images.json';
        
        if (file_exists($imageFile)) {
            $images = json_decode(file_get_contents($imageFile), true);
        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['codice'];
            $titolo = $row['titolo'];

            $film[] = [
                'id' => $id,
                'titolo' => $titolo,
                'immagine' => $images[$id] ?? 'https://example.com/images/default.jpg'
            ];
        }

        echo json_encode($film);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
