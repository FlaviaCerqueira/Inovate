<?php
// DETALHES DA COMPRA (CUPONS)
header('Content-Type: application/json');
include '../config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID da compra não fornecido.']);
    exit;
}

$compraId = intval($_GET['id']);

try {

    $sqlCompra = "SELECT Id, ValorOriginal, Desconto, ValorFinal, FORMAT(DataCompra, 'dd/MM/yyyy HH:mm') as DataFormatada FROM Compras WHERE Id = ?";
    $stmtCompra = $conn->prepare($sqlCompra);
    $stmtCompra->execute([$compraId]);
    $compra = $stmtCompra->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        echo json_encode(['status' => 'error', 'message' => 'Compra não encontrada.']);
        exit;
    }

    $sqlItens = "SELECT ic.Quantidade, ic.PrecoUnitario, p.Nome 
                 FROM ItensCompra ic 
                 JOIN Produtos p ON ic.ProdutoId = p.Id 
                 WHERE ic.CompraId = ?";
    $stmtItens = $conn->prepare($sqlItens);
    $stmtItens->execute([$compraId]);
    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'compra' => $compra,
        'itens' => $itens
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao carregar detalhes: ' . $e->getMessage()]);
}
?>