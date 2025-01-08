<?php

include 'conexao.php';
include 'session_check.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para finalizar a compra.');
}

// ID do usuário logado
$user_id = $_SESSION['user_id'];

// Obter os itens do carrinho do banco de dados
$query = "
    SELECT p.Nome, p.Preco, p.Imagem, c.quantidade
    FROM carrinho c
    INNER JOIN produtos p ON c.id_Produto = p.id
    WHERE c.id_Cliente = $user_id
";
$result = $conexao->query($query);

// Calcular o total geral
$total_geral = 0;
$itens_carrinho = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['total'] = $row['Preco'] * $row['quantidade']; // Calcula o total por produto
        $total_geral += $row['total']; // Incrementa o total geral
        $itens_carrinho[] = $row; // Adiciona o item ao array de itens do carrinho
    }
} else {
    die('Seu carrinho está vazio. Adicione itens antes de finalizar a compra.');
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="finalizarCompra.css">
    <link rel="stylesheet" href="PaginaInicial.css">
    <link rel="stylesheet" href="paginaproduto.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Conteúdo principal -->
    <div class="checkout-container">
        <h2>Finalizar Compra</h2>
        
        <!-- Dados do cliente -->
        <section class="checkout-section">
            <h3>Dados do Cliente</h3>
            <form>
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" placeholder="Ex: João Silva">

                <label for="email">Email:</label>
                <input type="email" id="email" placeholder="Ex: joao@email.com">

                <label for="telemovel">Telemóvel:</label>
                <input type="tel" id="telemovel" placeholder="Ex: 912345678">

                <label for="morada">Morada:</label>
                <input type="text" id="morada" placeholder="Ex: Rua das Flores, 123">

                <label for="codigo-postal">Código Postal:</label>
                <input type="text" id="codigo-postal" placeholder="Ex: 1000-001">

                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" placeholder="Ex: Lisboa">
            </form>
        </section>

        <!-- Resumo do pedido -->
        <section class="checkout-section">
        <h3>Resumo do Pedido</h3>
        <div class="order-summary">
            <?php foreach ($itens_carrinho as $item): ?>
                <div class="order-item">
                    <img src="produtos/<?php echo htmlspecialchars($item['Imagem']); ?>" alt="<?php echo htmlspecialchars($item['Nome']); ?>" style="width: 50px;">
                    <p><?php echo htmlspecialchars($item['Nome']); ?></p>
                    <span><?php echo number_format($item['Preco'], 2, ',', '.'); ?>€ x <?php echo $item['quantidade']; ?></span>
                    <p>Total: <?php echo number_format($item['total'], 2, ',', '.'); ?>€</p>
                </div>
            <?php endforeach; ?>
            <div class="order-total">
                <p>Total Geral:</p>
                <span><?php echo number_format($total_geral, 2, ',', '.'); ?>€</span>
            </div>
        </div>
    </section>

        <!-- Formas de pagamento -->
        <section class="checkout-section">
            <h3>Forma de Pagamento</h3>
            <form>
                <div>
                    <input type="radio" id="mbway" name="pagamento">
                    <label for="mbway">MB Way</label>
                </div>
                <div>
                    <input type="radio" id="cartao" name="pagamento">
                    <label for="cartao">Cartão de Crédito/Débito</label>
                </div>
                <div>
                    <input type="radio" id="transferencia" name="pagamento">
                    <label for="transferencia">Transferência Bancária</label>
                </div>
            </form>
        </section>

        <!-- Botão de finalizar compra -->
        <button class="finalizar-btn">Confirmar Compra</button>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
