<?php
include '../conexao.php';
include '../session_check.php';

if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para visualizar as encomendas.');
}

// Obtendo o ID do cliente logado
$user_id = $_SESSION['user_id'];

// Query para obter as encomendas e a fatura associada ao cliente logado
$query_encomendas = "
    SELECT id, fatura
    FROM encomendas 
    WHERE id_cliente = ?
";
$stmt_encomendas = $conexao->prepare($query_encomendas);
$stmt_encomendas->bind_param("i", $user_id);
$stmt_encomendas->execute();
$result_encomendas = $stmt_encomendas->get_result();

// Preparando array para armazenar encomendas e produtos associados
$encomendas = [];

// Percorrer todas as encomendas e buscar os produtos associados
if ($result_encomendas->num_rows > 0) {
    while ($encomenda = $result_encomendas->fetch_assoc()) {
        $id_encomenda = $encomenda['id'];
        $fatura = $encomenda['fatura'];

        // Query para buscar os produtos associados à encomenda
        $query_produtos = "
            SELECT p.id AS id_produto, p.nome AS nome_produto
            FROM itens_encomenda ie
            JOIN produtos p ON ie.id_produto = p.id
            WHERE ie.id_encomenda = ?
        ";
        $stmt_produtos = $conexao->prepare($query_produtos);
        $stmt_produtos->bind_param("i", $id_encomenda);
        $stmt_produtos->execute();
        $result_produtos = $stmt_produtos->get_result();

        // Montando a lista de produtos para esta encomenda
        $produtos = [];
        while ($produto = $result_produtos->fetch_assoc()) {
            $produtos[] = $produto['nome_produto'];
        }

        // Adicionando a encomenda e seus produtos ao array
        $encomendas[] = [
            'id_encomenda' => $id_encomenda,
            'produtos' => $produtos,
            'fatura' => $fatura,
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Encomendas</title>
    <link rel="stylesheet" href="../paginaInicial/PaginaInicial.css">
    <link rel="stylesheet" href="minhas_encomendas.css">
</head>
<body>
    <?php include '../navbar.php'; ?>
    <br><br><br>
    <!-- Conteúdo principal -->
    <div class="encomendas-container">
        <h2>Minhas Encomendas</h2>
        
        <?php if (!empty($encomendas)): ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID da Encomenda</th>
                        <th>Produtos</th>
                        <th>Faturas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($encomendas as $encomenda): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($encomenda['id_encomenda']); ?></td>
                            <td><?php echo htmlspecialchars(implode(', ', $encomenda['produtos'])); ?></td>
                            <td>
                                <?php if (!empty($encomenda['fatura']) && file_exists('../faturas/' . $encomenda['fatura'])): ?>
                                    <a href="../faturas/<?php echo htmlspecialchars($encomenda['fatura']); ?>" target="_blank">Baixar Fatura</a>
                                <?php else: ?>
                                    Nenhuma fatura disponível
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma encomenda encontrada.</p>
        <?php endif; ?>
    </div><br><br><br>

    <?php include '../footer.php'; ?>
</body>
</html>
