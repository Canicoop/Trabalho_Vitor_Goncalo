<?php
include 'conexao.php';
include 'session_check.php';

// Verificar se o Nome foi passado pela URL
if (!isset($_GET['Nome'])) {
    die("Produto não encontrado!");
}

$nome = ($_GET['Nome']); 

// Usar prepared statement para buscar o produto
$stmt = $conexao->prepare("SELECT * FROM produtos WHERE Nome = ?");
$stmt->bind_param("s", $nome);
$stmt->execute();
$result = $stmt->get_result();

// Verificar se o produto existe
if ($result->num_rows === 0) {
    die("Produto não encontrado!");
}

$produto = $result->fetch_assoc();

// Alteração para buscar tamanhos corretamente
$tamanhos_query = $conexao->prepare("
    SELECT t.id, t.descricao 
    FROM tamanho t
    JOIN produtos p ON p.tamanho = t.id
    WHERE p.Nome = ?");
$tamanhos_query->bind_param("s", $nome);
$tamanhos_query->execute();
$tamanhos_result = $tamanhos_query->get_result();
$tamanhos = [];

// Armazenar tamanhos encontrados
while ($tamanho = $tamanhos_result->fetch_assoc()) {
    $tamanhos[] = $tamanho;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['Nome']); ?></title>
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

            <div class="tamanhos">
                <h3>Tamanhos Disponíveis:</h3>
                <?php if (count($tamanhos) > 0): ?>
                    <!-- Exibir o formulário apenas se houver tamanhos disponíveis -->
                    <form action="carrinho.php" method="GET">
                        <label for="tamanho">Escolha o tamanho:</label>
                        <select name="tamanho" id="tamanho" required>
                            <option value="">Selecione um tamanho</option>
                            <?php foreach ($tamanhos as $tamanho): ?>
                            <option value="<?php echo $tamanho['id']; ?>"><?php echo htmlspecialchars($tamanho['descricao']); ?></option>
                         <?php endforeach; ?>
                         </select>

                             <!-- Enviar o ID do produto e o Nome junto com o tamanho -->
                         <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                        <input type="hidden" name="produto_nome" value="<?php echo htmlspecialchars($produto['Nome']); ?>">
                        <button type="submit" class="btn-adicionar">Adicionar ao Carrinho</button>
                        </form>
                    </form>
                <?php else: ?>
                    <!-- Caso não haja tamanhos, exibir a mensagem de erro -->
                    <p>Sem tamanhos disponíveis.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
