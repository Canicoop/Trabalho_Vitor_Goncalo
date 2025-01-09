<?php
include 'conexao.php';
include 'session_check.php';

// Inicializar variáveis para filtros
$genero = null;
$tipo_nome = null;

// Verificar se o parâmetro "Genero" está na URL
if (isset($_GET['Genero'])) {
    $genero = mysqli_real_escape_string($conexao, $_GET['Genero']); // Escapar o valor do parâmetro para evitar SQL injection
}

// Verificar se o parâmetro "tipo" está na URL
if (isset($_GET['tipo'])) {
    $tipo_nome = mysqli_real_escape_string($conexao, $_GET['tipo']); // Escapar o valor do parâmetro para evitar SQL injection
}

// Inicializa a consulta SQL
$sql = "SELECT * FROM produtos WHERE 1=1"; // 1=1 facilita a adição dinâmica de condições

// Adicionar filtro de gênero, se fornecido
if ($genero) {
    $sql .= " AND genero = '$genero'";
}

// Adicionar filtro de tipo, se fornecido
if ($tipo_nome) {
    // Buscar o ID do tipo correspondente na tabela "tipo"
    $query_tipo = "SELECT id FROM tipo WHERE descricao = '$tipo_nome'";
    $result_tipo = mysqli_query($conexao, $query_tipo);

    if ($result_tipo && mysqli_num_rows($result_tipo) > 0) {
        $row_tipo = mysqli_fetch_assoc($result_tipo);
        $tipo_id = $row_tipo['id'];
        $sql .= " AND tipo_id = $tipo_id"; // Filtra pelos tipos
    } else {
        // Caso o tipo não exista, exibe mensagem
        $tipo_nome = null; // Não filtrar por tipo
    }
}

// Adicionar GROUP BY se necessário
$sql .= " GROUP BY Nome"; // Agrupa pelo nome dos produtos

// Executar a consulta final
$result_produtos = mysqli_query($conexao, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="PaginaInicial.css">
    <link rel="stylesheet" href="paginaproduto.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="free-shipping-bar">
        FREE SHIPPING ON ALL ORDERS OVER 100€
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const dropdown = document.querySelector('.dropdown');

            menuToggle.addEventListener('click', function() {
                dropdown.classList.toggle('show');
            });

            // Fechar o menu se o usuário clicar fora dele
            window.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target) && !menuToggle.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        });
    </script>

    <br><br>
    <?php
    if ($result_produtos && mysqli_num_rows($result_produtos) > 0) {
        echo '<div class="product-container">'; // Adiciona um container para alinhar os produtos

        while ($row = mysqli_fetch_assoc($result_produtos)) {
            ?>
            <div class="product-card">
                <div class="product-image">
                    <a href="ProdutoDetalhes.php?Nome=<?php echo $row['Nome']; ?>">
                        <img src="produtos/<?php echo htmlspecialchars($row['Imagem']); ?>" alt="<?php echo htmlspecialchars($row['Nome']); ?>">
                    </a>
                </div>
                <div class="product-info">
                    <h2 class="product-name"><?php echo htmlspecialchars($row['Nome']); ?></h2>
                    <p class="product-price"><?php echo number_format($row['Preco'], 2, ',', '.'); ?>€</p>
                    <p class="product-stock">Disponível: <?php echo $row['Stock']; ?> Unidades</p>
                </div>
            </div>
            <?php
        }

        echo '</div>'; // Fecha o container
    } else {
        echo "<p>Nenhum produto encontrado com os filtros aplicados.</p>";
    }
    ?>
    <br><br><br><br>

    <?php include 'footer.php'; ?>

</body>
</html>
