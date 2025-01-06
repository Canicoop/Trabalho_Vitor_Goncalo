<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para adicionar itens ao carrinho.');
}

// ID do usuário logado
$user_id = $_SESSION['user_id'];

// Adicionar ao carrinho
if (isset($_GET['add_to_cart'])) {
    $produto_id = intval($_GET['add_to_cart']);

    // Verificar se o produto já está no carrinho
    $query = "SELECT * FROM carrinho WHERE id_Cliente = $user_id AND id_Produto = $produto_id";
    $result = $conexao->query($query);

    if ($result->num_rows > 0) {
        // Atualizar a quantidade do produto
        $update_query = "UPDATE carrinho SET quantidade = quantidade + 1 WHERE id_Cliente = $user_id AND id_Produto = $produto_id";
        $conexao->query($update_query);
    } else {
        // Inserir um novo item no carrinho
        $insert_query = "INSERT INTO carrinho (id_Cliente, id_Produto, quantidade) VALUES ($user_id, $produto_id, 1)";
        $conexao->query($insert_query);
    }

    // Redirecionar para evitar múltiplos cliques
    header('Location: carrinho.php');
    exit;
}



// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para ver o carrinho.');
}

$user_id = $_SESSION['user_id'];

// Obter os itens do carrinho do banco de dados
$query = "
    SELECT c.id AS carrinho_id, p.Nome, p.Preco, p.Imagem,c.quantidade
    FROM carrinho c
    INNER JOIN produtos p ON c.id_Produto = p.id
    WHERE c.id_Cliente = $user_id
";
$result = $conexao->query($query);

// Exibir os itens na página

if (isset($_GET['remove_from_cart'])) {
    $carrinho_id = intval($_GET['remove_from_cart']);

    $delete_query = "DELETE FROM carrinho WHERE id = $carrinho_id AND id_Cliente = $user_id";
    $conexao->query($delete_query);

    // Redirecionar para evitar múltiplos cliques
    header('Location: carrinho.php');
    exit;
}

// Atualizar a quantidade no carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $carrinho_id = intval($_POST['update_quantity']);
    $nova_quantidade = intval($_POST['quantidade']);

    // Atualizar a quantidade no banco de dados
    if ($nova_quantidade > 0) {
        $update_query = "UPDATE carrinho SET quantidade = $nova_quantidade WHERE id = $carrinho_id AND id_Cliente = $user_id";
        $conexao->query($update_query);
    }

    // Redirecionar para evitar reenvio do formulário
    header('Location: carrinho.php');
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="carrinho.css">
    <link rel="stylesheet" href="PaginaInicial.css">
</head>
<body>
    <h1>Carrinho de Compras</h1>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_geral = 0;
                while ($row = $result->fetch_assoc()):
                    $total_item = $row['Preco'] * $row['quantidade'];
                    $total_geral += $total_item;
                ?>
                    <tr>
                        <td><img src="produtos/<?php echo htmlspecialchars($row['Imagem']); ?>" alt="<?php echo htmlspecialchars($row['Nome']); ?>" style="width: 50px;"></td>
                        <td><?php echo htmlspecialchars($row['Nome']); ?></td>
                        <td><?php echo number_format($row['Preco'], 2, ',', '.'); ?>€</td>
                        <td>
                            <!-- Formulário para alterar a quantidade -->
                            <form action="carrinho.php" method="POST" style="display: inline;">
                                <input type="hidden" name="update_quantity" value="<?php echo $row['carrinho_id']; ?>">
                                <input type="number" name="quantidade" value="<?php echo $row['quantidade']; ?>" min="1" style="width: 50px;" required>
                            </form>
                        </td>
                        <td><?php echo number_format($total_item, 2, ',', '.'); ?>€</td>
                        <td>
                            <a href="carrinho.php?remove_from_cart=<?php echo $row['carrinho_id']; ?>">Remover</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="4">Total Geral</td>
                    <td><?php echo number_format($total_geral, 2, ',', '.'); ?>€</td>
                    <td>
                    <a href="finalizar_compra.php">Finalizar Compra</a></td>
                </tr>
            </tfoot>
        </table>
    <?php else: ?>
        <p>Seu carrinho está vazio.</p>
    <?php endif; ?>
    <br>
    <br>
    <br>
    <footer class="footer">
    <div class="footer-container">
        <!-- Logo -->
        <div class="footer-logo">
            <img src="logo.png" alt="Logo da Empresa">
        </div>

        <!-- Links úteis -->
        <div class="footer-links">
            <h3>Links Úteis</h3>
            <ul>
                <li><a href="#método">Métodos Pagamentos</a></li>
                <li><a href="#servicos">Serviços</a></li>
                <li><a href="#contato">Contato</a></li>
                <li><a href="#faq">FAQ</a></li>
            </ul>
        </div>

        <!-- Métodos de Pagamento -->
        <div class="footer-payments">
            <h3>Métodos de Pagamento</h3>
            <img src="visa.png" alt="Visa">
            <img src="mastercard.png" alt="MasterCard">
            <img src="paypal.png" alt="PayPal">
            <img src="mbway.png" alt="Mbway">
        </div>

        <!-- Informações de Contato -->
        <div class="footer-contact">
            <h3>Contacto</h3>
            <p>Email: gonvi@gmail.com</p>
            <p>Telefone: +351 918326697</p>
            <p>Morada: Rua da Aldeia nº51 2567-345 Lisboa</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 Empresa. Todos os direitos reservados.</p>
    </div>
</footer>
</body>
</html>
