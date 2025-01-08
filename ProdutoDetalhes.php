<?php
include 'conexao.php';
include 'session_check.php';

// Verificar se o ID foi passado pela URL
if (!isset($_GET['id'])) {
    die("Produto não encontrado!");
}

$id = intval($_GET['id']); // Sanitizar o ID (opcional para prepared statements)

// Usar prepared statement para maior segurança
$stmt = $conexao->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Verificar se o produto existe
if ($result->num_rows === 0) {
    die("Produto não encontrado!");
}

$produto = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nome']); ?></title>
    <link rel="stylesheet" href="ProdutoDetalhes.css">
    <link rel="stylesheet" href="PaginaInicial.css">
    <link rel="stylesheet" href="paginaproduto.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="free-shipping-bar">
        FREE SHIPPING ON ALL ORDERS OVER 100€
    </div>

    <div class="produto-detalhes">
        <div class="produto-imagem">
            <img src="produtos/<?php echo htmlspecialchars($produto['Imagem']); ?>" alt="<?php echo htmlspecialchars($produto['Nome']); ?>">
        </div>
        <div class="produto-info">
            <h1><?php echo htmlspecialchars($produto['Nome']); ?></h1>
            <p class="preco">Preço: <?php echo number_format($produto['Preco'], 2, ',', '.'); ?>€</p>
            <p class="estoque">Disponível: <?php echo $produto['Stock']; ?> unidades</p>
            <a href="carrinho.php?add_to_cart=<?php echo $produto['id']; ?>" class="btn-adicionar">Adicionar ao Carrinho</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
