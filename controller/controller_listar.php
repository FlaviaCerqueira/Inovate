<?php
// LISTA COMPRAS
header('Content-Type: application/json');
include '../config/database.php';

try {
    $sql = "SELECT Id, ValorOriginal, Desconto, ValorFinal, FORMAT(DataCompra, 'dd/MM/yyyy HH:mm') as DataFormatada 
            FROM Compras ORDER BY Id DESC";
    $stmt = $conn->query($sql);
    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $compras]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao carregar dados: ' . $e->getMessage()]);
}
?>