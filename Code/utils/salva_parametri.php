<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);
$_SESSION['sala'] = $data['sala'] ?? null;
$_SESSION['orario'] = isset($data['orario']) ? substr($data['orario'], 0, 5) : null;
echo 'ok';
