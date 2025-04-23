<?php
$servername = 'localhost';
$username = 'rikuzz';
$db = 'my_rikuzz';
$password = 'jAuRUKqyET25';
$error = false;

try {
    $conn = new PDO("mysql:host=" . $servername . "; dbname=" . $db, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "DB ERROR:" . $e->getMessage();
    $error = true;
}
?>