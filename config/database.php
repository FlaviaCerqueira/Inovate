<?php
header('Content-Type: application/json');

$serverName = "localhost";
$database = "inovate";
$uid = "inovate";
$pwd = "1n0vate@#";

try {
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $uid, $pwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro de conexão: ' . $e->getMessage()]);
    exit;
}
