<?php
// REALIZA O INSERT DO PRODUTO NO BANCO E FINALIZA A COMPRA
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

include '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

if (empty($input)) {
    echo json_encode(['status' => 'error', 'message' => 'Carrinho vazio.']);
    exit;
}

$valorOriginal = 0;
foreach ($input as $item) {
    $valorOriginal += ($item['quantidade'] * $item['preco']);
}

$desconto = $valorOriginal > 100 ? $valorOriginal * 0.10 : 0;
$valorFinal = $valorOriginal - $desconto;

try {
    $conn->beginTransaction();

    // 1. Grava a Compra 
    $stmtCompra = $conn->prepare("INSERT INTO Compras (ValorOriginal, Desconto, ValorFinal) VALUES (?, ?, ?)");
    $stmtCompra->execute([$valorOriginal, $desconto, $valorFinal]);
    $compraId = $conn->lastInsertId();

    // 2. Prepara inserção
    $stmtCheckProd = $conn->prepare("SELECT Id FROM Produtos WHERE Nome = ?");
    $stmtInsertProd = $conn->prepare("INSERT INTO Produtos (Nome, PrecoUnitario) VALUES (?, ?)");
    $stmtUpdateProd = $conn->prepare("UPDATE Produtos SET PrecoUnitario = ? WHERE Id = ?");
    $stmtInsertItem = $conn->prepare("INSERT INTO ItensCompra (CompraId, ProdutoId, Quantidade, PrecoUnitario) VALUES (?, ?, ?, ?)");

    foreach ($input as $item) {
        $nome = $item['nome'];
        $preco = $item['preco'];
        $quantidade = $item['quantidade'];

        $stmtCheckProd->execute([$nome]);
        $produto = $stmtCheckProd->fetch(PDO::FETCH_ASSOC);

        if ($produto) {
            $produtoId = $produto['Id'];
            $stmtUpdateProd->execute([$preco, $produtoId]);
        } else {
            $stmtInsertProd->execute([$nome, $preco]);
            $produtoId = $conn->lastInsertId();
        }

        $stmtInsertItem->execute([$compraId, $produtoId, $quantidade, $preco]);
    }

    $conn->commit();

    echo json_encode([
        'status' => 'success',
        'valorOriginal' => number_format($valorOriginal, 2, ',', '.'),
        'desconto' => number_format($desconto, 2, ',', '.'),
        'valorFinal' => number_format($valorFinal, 2, ',', '.')
    ]);

} catch (PDOException $e) {
    $conn->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar: ' . $e->getMessage()]);
}
?>