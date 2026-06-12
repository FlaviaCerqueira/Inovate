# Avaliação Técnica - Estágio INOVATE / Bahiana

Olá! Este repositório contém a minha entrega para a avaliação técnica. 

O teste pedia apenas a lógica de programação na Parte 1, mas achei que seria legal ir um pouco além e montar um sisteminha web completo, com interface gráfica e banco de dados. 

Abaixo explico rapidinho como estruturei tudo.

## 💻 Tecnologias que utilizei
* **Backend:** PHP (usando PDO para conectar no banco de forma segura).
* **Banco de Dados:** SQL Server.
* **Frontend:** HTML, CSS, JavaScript (usando Fetch para não precisar recarregar a página).
* **Bibliotecas visuais:** Bootstrap 5 (para o layout), DataTables (para a tabela de histórico), SweetAlert2 e Toastr (para os alertas na tela).

## 🧠 Como eu pensei o projeto

Decidi dividir a minha entrega para respeitar exatamente o que foi pedido, mas ao mesmo tempo mostrar o que sei fazer na prática:

**1. Sobre a Parte 1 (O Sistema Web)**
Como o teste tinha um diferencial opcional (dar desconto de 10% e mostrar o valor original e final), eu percebi que precisaria guardar isso no banco de dados de um jeito organizado. Por isso, criei três tabelas reais para o sistema: `Produtos`, `Compras` (onde guardo o total e o desconto) e `ItensCompra` (onde guardo o que foi comprado em cada venda). 
Fiz tudo em uma tela só (`index.php`), onde dá para ver o histórico e abrir o caixa num Modal.

**2. Sobre a Parte 2 (As Consultas SQL)**
Para a segunda parte, vi que o PDF pedia consultas baseadas em uma estrutura mais simples de tabelas (apenas Produtos e Compras). Para não misturar as coisas e facilitar a vida de quem for corrigir, criei um arquivo separado chamado `respostas_parte2.sql`. Nele, eu crio exatamente as tabelas como estão no PDF e respondo às três questões.

## 📂 Como ficou a estrutura das pastas
* `/assets/` -> Meu CSS e JavaScript.
* `/config/` -> Onde fica a conexão com o banco de dados (`database.php`).
* `/controller/` -> Os arquivos PHP que fazem o trabalho pesado (salvar a compra, listar o histórico, etc).
* `/includes/` -> Cabeçalho e rodapé para não repetir código HTML.
* `index.php` -> A tela principal do sistema.
* `respostas_parte2.sql` -> **Aqui estão as respostas da Parte 2 do teste!**

## 🚀 Como testar o meu projeto

1. Baixe este repositório e coloque na pasta do seu servidor local (ex: `htdocs` no XAMPP).
2. Rode o script de criação das tabelas da Parte 1 no seu SQL Server (são as tabelas `Produtos`, `Compras` e `ItensCompra`).
3. Abra o arquivo `config/database.php` e coloque o usuário e senha do seu SQL Server.
4. Acesse `http://localhost/testeInovate/index.php` no navegador.

Para testar as respostas da **Parte 2**, basta pegar o código que está no arquivo `respostas_parte2.sql` e rodar direto no SQL Server.

---
Muito obrigado pela oportunidade! Qualquer dúvida sobre o código, estou à disposição.