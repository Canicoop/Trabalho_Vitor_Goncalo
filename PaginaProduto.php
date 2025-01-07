<?php
include 'conexao.php';
include 'session_check.php';
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Inicial</title>
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
        if (isset($_GET['tipo'])) {
            require 'conexao.php'; // Certifique-se de incluir a conexão com o banco de dados
        
            $tipo_nome = $conexao->real_escape_string($_GET['tipo']); // Evita SQL Injection
        
            // Primeiro, busca o ID do tipo na tabela "tipo"
            $query_tipo = "SELECT id FROM tipo WHERE descricao = '$tipo_nome'";
            $result_tipo = $conexao->query($query_tipo);
        
            if ($result_tipo->num_rows > 0) {
                $row_tipo = $result_tipo->fetch_assoc();
                $tipo_id = $row_tipo['id']; // Obtém o ID do tipo correspondente
        
                // Agora, busca os produtos que possuem esse tipo_id
                $query_produtos = "SELECT * FROM produtos WHERE tipo_id = $tipo_id";
                $result_produtos = $conexao->query($query_produtos);
        
                if ($result_produtos->num_rows > 0) {
                    echo '<div class="product-container">'; // Adiciona um container para alinhar os produtos
        
                    while ($row = $result_produtos->fetch_assoc()) {
                        ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="produtos/<?php echo htmlspecialchars($row['Imagem']); ?>" alt="<?php echo htmlspecialchars($row['Nome']); ?>">
                            </div>
                            <div class="product-info">
                                <h2 class="product-name"><?php echo htmlspecialchars($row['Nome']); ?></h2>
                                <p class="product-price"><?php echo number_format($row['Preco'], 2, ',', '.'); ?>€</p>
                                <p class="product-stock">Disponível: <?php echo $row['Stock']; ?> Unidades</p>
                                <a href="carrinho.php?add_to_cart=<?php echo $row['id']; ?>" class="product-btn">Adicionar ao Carrinho</a>
    
                            </div>
                        </div>
                        <?php
                    }
        
                    echo '</div>'; // Fecha o container
                } else {
                    echo "<p>Nenhum produto encontrado para o tipo: <strong>$tipo_nome</strong>.</p>";
                }
            } else {
                echo "<p>Tipo não encontrado.</p>";
            }
        } else {
            echo "<p>Selecione um tipo de produto.</p>";
        }
        ?>
                    <br><br><br><br>


    <?php include 'footer.php'; ?>

</body>
</html>