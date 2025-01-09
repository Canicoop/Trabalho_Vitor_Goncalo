<?php
include '../conexao.php';
include '../session_check.php';

$sql_tendencia = "SELECT produtos.nome, produtos.preco, produtos.Imagem 
                  FROM tendencia 
                  INNER JOIN produtos 
                  ON tendencia.id_produto = produtos.id;";
$result_tendencia = mysqli_query($conexao, $sql_tendencia);

$sql_colecao = "SELECT descricao, Imagem FROM colecoes";
$result_colecao = mysqli_query($conexao, $sql_colecao);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Inicial</title>
    <link rel="stylesheet" href="PaginaInicial.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

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

    <br><br><br><br>

    <div class="banner"></div>

    <br><br><br><br>

    <h2 class="texto">Coleções</h2>
    <br><br>
    <div class="container">
        <?php if ($result_colecao && mysqli_num_rows($result_colecao) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result_colecao)): ?>
                <div class="item">
                    <img src="../colecoes/<?php echo htmlspecialchars($row['Imagem']); ?>" alt="<?php echo htmlspecialchars($row['descricao']); ?>">
                    <div class="label"><?php echo htmlspecialchars($row['descricao']); ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Não há coleções disponíveis no momento.</p>
        <?php endif; ?>
    </div>

    <br><br><br><br>

    <h1 class="texto">Tendências desta semana</h1>
    <br><br>
    <div class="container">
        <?php if ($result_tendencia && $result_tendencia->num_rows > 0): ?>
            <?php while ($produto_tendencia = $result_tendencia->fetch_assoc()): ?>
                <div class="item">
                <img src="../produtos/<?php echo htmlspecialchars($produto_tendencia['Imagem']); ?>" alt="<?php echo $produto_tendencia['nome']; ?>">
                    <div class="label"><?php echo $produto_tendencia['nome']; ?></div>
                    <div class="price"><?php echo number_format($produto_tendencia['preco'], 2, ',', ' ') . ' €'; ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Não há tendências disponíveis no momento.</p>
        <?php endif; ?>
    </div>

    <br><br><br><br>

   

    <h1 class="texto">Explorar Mais</h1>
    <br><br>
    <div class="containerb">
        <div class="category">
            <a href="../paginaProduto/PaginaProduto.php?Genero=unisex">
                <img src="../Unisexo.webp" alt="Unisexo">
            </a>
                <p>Unisexo</p>
            </div>
            <div class="category">
                <a href="../paginaProduto/PaginaProduto.php?Genero=Masculino">
                    <img src="../homem.png" alt="Homem">
                </a>
                <p>Homem</p>
            </div>
            <div class="category">
                <a href="../paginaProduto/PaginaProduto.php?Genero=feminino">
                    <img src="../Mulher.png" alt="Mulher">
                </a>
                <p>Mulher</p>
            </div>
        </div>
    </div>

    <br><br><br><br>

    <?php include '../footer.php'; ?>



</body>
</html>
