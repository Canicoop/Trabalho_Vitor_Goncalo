<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para adicionar itens ao carrinho.');
}

// ID do usuário logado
$user_id = $_SESSION['user_id'];

if (isset($_GET['produto_nome']) && isset($_GET['tamanho'])) {
    $produto_nome = $_GET['produto_nome'];
    $tamanho_id = intval($_GET['tamanho']);

    // Buscar o ID correto do produto com base no nome e tamanho
    $query = "SELECT id FROM produtos WHERE Nome = ? AND tamanho = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("si", $produto_nome, $tamanho_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
        $produto_id = $produto['id']; // Agora temos o ID correto do produto
    } 

    if (isset($produto_id)) { // Só roda se o ID for encontrado
        // Verificar se o produto já está no carrinho
        $query = "SELECT * FROM carrinho WHERE id_Cliente = ? AND id_Produto = ?";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param("ii", $user_id, $produto_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            // Atualizar a quantidade do produto no carrinho
            $update_query = "UPDATE carrinho SET quantidade = quantidade + 1 WHERE id_Cliente = ? AND id_Produto = ?";
            $stmt = $conexao->prepare($update_query);
            $stmt->bind_param("ii", $user_id, $produto_id);
            $stmt->execute();
        } else {
            // Inserir um novo item no carrinho
            $insert_query = "INSERT INTO carrinho (id_Cliente, id_Produto, quantidade) VALUES (?, ?, 1)";
            $stmt = $conexao->prepare($insert_query);
            $stmt->bind_param("ii", $user_id, $produto_id);
            $stmt->execute();
        }
    
        // Redirecionamento para evitar reenvios acidentais
        header('Location: carrinho.php');
        exit;
    }
}


// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para ver o carrinho.');
}

$user_id = $_SESSION['user_id'];

// Obter os itens do carrinho do banco de dados
$query = "
    SELECT c.id AS carrinho_id, p.Nome, p.Preco, p.Imagem, c.quantidade, t.descricao AS tamanho
    FROM carrinho c
    INNER JOIN produtos p ON c.id_Produto = p.id
    INNER JOIN tamanho t ON p.tamanho = t.id
    WHERE c.id_Cliente = ?
";
$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

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

// Consultar o nome do usuário no banco de dados
$query_user = "SELECT Nome FROM users WHERE id = $user_id";
$result_user = $conexao->query($query_user);

// Verificar se o usuário foi encontrado
if ($result_user && $result_user->num_rows > 0) {
    $user_data = $result_user->fetch_assoc();
    $user_name = htmlspecialchars($user_data['Nome']); // Nome do usuário
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
    <table>
        <td><a href="PaginaInicial.php" id="no-style-link">
                <img class="no-style-link" src="Logo.png" alt="Logo" style="width:10rem;">
            </a>
        </td>
        <td><h1>Carrinho de <?php echo $user_name; ?></h1></td>
        <td>
            <div class="icon">
                <a href="logout.php">
                    <svg xmlns="http://www.w3.org/2000/svg" color="black" width="30" height="30" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10.146 12.354a.5.5 0 0 1 0-.708L12.793 9H5.5a.5.5 0 0 1 0-1h7.293l-2.647-2.646a.5.5 0 0 1 .708-.708l3.5 3.5a.5.5 0 0 1 0 .708l-3.5 3.5a.5.5 0 0 1-.708 0z"/>
                        <path fill-rule="evenodd" d="M13 14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7a2 2 0 0 0-2 2v1a.5.5 0 0 1-1 0V4a3 3 0 0 1 3-3h7a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3h-7a3 3 0 0 1-3-3v-1a.5.5 0 0 1 1 0v1a2 2 0 0 0 2 2h7z"/>
                    </svg>
                </a>
            </div>
        </td>
    </table>
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
                        <td><?php echo htmlspecialchars($row['Nome']); ?> (<?php echo htmlspecialchars($row['tamanho']); ?>)</td>
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
                            <a class="hover" href="carrinho.php?remove_from_cart=<?php echo $row['carrinho_id']; ?>">Remover</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="4">Total Geral</td>
                    <td><?php echo number_format($total_geral, 2, ',', '.'); ?>€</td>
                    <td>
                    <a class="hover" href="FinalizarCompra.php">Finalizar Compra</a></td>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
    <br>
    <br>
    <br>
    
    <?php include 'footer.php'; ?>

</body>
</html>
