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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtendo os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telemovel = $_POST['telemovel'];
    $morada = $_POST['morada'];
    $codigo_postal = $_POST['codigo_postal'];
    $cidade = $_POST['cidade'];
    $id_pagamento = $_POST['id_pagamento']; // Forma de pagamento escolhida

    // Obter o id_carrinho do cliente
    $query_carrinho = "SELECT id FROM carrinho WHERE id_Cliente = ?";
    $stmt_carrinho = $conexao->prepare($query_carrinho);
    $stmt_carrinho->bind_param("i", $user_id);
    $stmt_carrinho->execute();
    $result_carrinho = $stmt_carrinho->get_result();

    if ($result_carrinho->num_rows > 0) {
        $id_carrinho = $result_carrinho->fetch_assoc()['id'];
    } else {
        die('❌ Carrinho não encontrado.');
    }

    // ✅ Inserindo na tabela "encomendas" (incluindo `data_compra`)
    $query_insert = "INSERT INTO encomendas (id_carrinho, id_pagamento, nome_completo, email, telemovel, morada, codigo_postal, cidade, total, data_compra)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conexao->prepare($query_insert);
    if (!$stmt) {
        die("❌ Erro na preparação da query: " . $conexao->error);
    }

    $stmt->bind_param("iissssssd", $id_carrinho, $id_pagamento, $nome, $email, $telemovel, $morada, $codigo_postal, $cidade, $total_geral);

    if ($stmt->execute()) {
        echo "✅ Encomenda realizada com sucesso!";
        
        // 🔹 Remover produtos do carrinho após finalizar compra
        $query_limpar_carrinho = "DELETE FROM carrinho WHERE id_Cliente = ?";
        $stmt_limpar = $conexao->prepare($query_limpar_carrinho);
        $stmt_limpar->bind_param("i", $user_id);
        $stmt_limpar->execute();
    } else {
        die("❌ Erro ao inserir encomenda: " . $stmt->error);
    }
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
            <form action="FinalizarCompra.php" method="POST">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" placeholder="Ex: João Silva" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Ex: joao@email.com" required>

                <label for="telemovel">Telemóvel:</label>
                <input type="tel" id="telemovel" name="telemovel" placeholder="Ex: 912345678" required>

                <label for="morada">Morada:</label>
                <input type="text" id="morada" name="morada" placeholder="Ex: Rua das Flores, 123" required>

                <label for="codigo-postal">Código Postal:</label>
                <input type="text" id="codigo-postal" name="codigo_postal" placeholder="Ex: 1000-001" required>

                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" placeholder="Ex: Lisboa" required>

                <!-- Formas de pagamento -->
                <section class="checkout-section">
                    <h3>Forma de Pagamento</h3>
                    <div>
                        <input type="radio" id="mbway" name="id_pagamento" value="1" required>
                        <label for="mbway">MB Way</label>
                    </div>
                    <div>
                        <input type="radio" id="cartao" name="id_pagamento" value="2">
                        <label for="cartao">Cartão de Crédito/Débito</label>
                    </div>
                    <div>
                        <input type="radio" id="transferencia" name="id_pagamento" value="3">
                        <label for="transferencia">Transferência Bancária</label>
                    </div>
                </section>

                <!-- Botão de finalizar compra -->
                <button type="submit" class="finalizar-btn">Confirmar Compra</button>
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
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
