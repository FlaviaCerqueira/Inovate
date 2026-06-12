-- ==========================================
-- TABELAS
-- ==========================================
CREATE TABLE Produtos (
    ID_Produto INT PRIMARY KEY,
    Nome_Produto VARCHAR(100),
    Preco_Unitario DECIMAL(10,2)
);

CREATE TABLE Compras (
    ID_Compra INT,
    ID_Produto INT,
    Quantidade INT,
    FOREIGN KEY (ID_Produto) REFERENCES Produtos(ID_Produto)
);

-- ==========================================
-- QUESTÕES - PARTE 2
-- ==========================================

-- Questão 1: Valor total de cada compra
SELECT 
    c.ID_Compra,
    p.Nome_Produto,
    c.Quantidade,
    p.Preco_Unitario,
    (c.Quantidade * p.Preco_Unitario) AS Valor_Total_Compra
FROM Compras c
JOIN Produtos p ON c.ID_Produto = p.ID_Produto;

-- Questão 2: Produto mais vendido
SELECT TOP 1
    p.ID_Produto,
    p.Nome_Produto,
    SUM(c.Quantidade) AS Quantidade_Total_Comprada
FROM Produtos p
JOIN Compras c ON p.ID_Produto = c.ID_Produto
GROUP BY p.ID_Produto, p.Nome_Produto
ORDER BY Quantidade_Total_Comprada DESC;

-- Questão 3: Todos os produtos e quantidade total (mesmo sem compras)
SELECT 
    p.ID_Produto,
    p.Nome_Produto,
    COALESCE(SUM(c.Quantidade), 0) AS Quantidade_Total_Comprada
FROM Produtos p
LEFT JOIN Compras c ON p.ID_Produto = c.ID_Produto
GROUP BY p.ID_Produto, p.Nome_Produto;