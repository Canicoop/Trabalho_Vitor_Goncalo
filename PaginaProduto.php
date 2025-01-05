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
    <nav class="navbar">
        <div class="nav-left">
            <div id="logo">
            <a  href="PaginaInicial.php"> <img  src="Logo.png" alt="" style="width:10rem;">
            </div>
        </div>

                <div class="nav-center">
            <div class="dropdown">
                <a>Roupa</a>
                <div class="dropdown-content">
                    <a href="PaginaProduto.php?tipo=Casacos">Casacos</a>
                    <a href="PaginaProduto.php?tipo=Calças de Ganga">Calças de Ganga</a>
                    <a href="PaginaProduto.php?tipo=Calças de Fato de Treino">Calças de Fato de Treino</a>
                    <a href="PaginaProduto.php?tipo=Camisolas">Camisolas</a>
                    <a href="PaginaProduto.php?tipo=Sweats">Sweats</a>
                    <a href="PaginaProduto.php?tipo=Fatos de Treino">Fatos de Treino</a>
                </div>
            </div>
            <div class="dropdown">
                <a>Calçado</a>
                <div class="dropdown-content">
                    <a href="PaginaProduto.php?tipo=Caminhada e Outdoor">Caminhada e Outdoor</a>
                    <a href="PaginaProduto.php?tipo=Futebol">Futebol</a>
                    <a href="PaginaProduto.php?tipo=Futsal">Futsal</a>
                    <a href="PaginaProduto.php?tipo=Ciclismo">Ciclismo</a>
                </div>
            </div>
            <div class="dropdown">
                <a>Acessórios</a>
                <div class="dropdown-content">
                    <a href="PaginaProduto.php?tipo=Chapéus">Chapéus</a>
                    <a href="PaginaProduto.php?tipo=Gorros">Gorros</a>
                    <a href="PaginaProduto.php?tipo=Bolsas">Bolsas</a>
                    <a href="PaginaProduto.php?tipo=Mochilas">Mochilas</a>
                    <a href="PaginaProduto.php?tipo=Meias">Meias</a>
                    <a href="PaginaProduto.php?tipo=Óculos">Óculos</a>
                </div>
            </div>
        </div>
        <div class="nav-right">
            <?php if (is_logged_in()): ?>

                <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 14H4a.5.5 0 0 1-.491-.408L1.01 2H.5a.5.5 0 0 1-.5-.5zM4.415 13h8.17l1.2-6.4H3.314L4.415 13zM5 15a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm9 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </svg>
                </div>

                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </div> 
                <div class="icon dropdown">
                <span class="menu-toggle">
                <?php
                $sql = "SELECT Imagem FROM users WHERE Username = '{$_SESSION['username']}'";
                $result = mysqli_query($conexao, $sql);
                while($row = mysqli_fetch_array($result)) {
                $imageURL = 'imagens/'.$row["Imagem"];
                        ?>
                         <!-- Foto do usuário dentro de um círculo -->
                    <img class="user-photo" src="<?php echo $imageURL; }?>" alt="User Photo">
                    <span class="user-name"><?php echo $_SESSION['username']; ?></span>
                    ▼
                </span>
                <!-- Menu suspenso -->
                <div class="dropdown-content">
                    <a href="minhas_encomendas.php">Minhas Encomendas</a>
                    <a href="minha_conta.php">Minha Conta</a>
                    <a href="sobre_nos.php">Sobre Nós</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
                <?php else: ?>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                    </div>
                    <div class="icon">
                        <a href="login.php">
                            <svg xmlns="http://www.w3.org/2000/svg" color="black" width="30" height="30" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
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
                                <button class="product-btn">Adicionar ao Carrinho</button>
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