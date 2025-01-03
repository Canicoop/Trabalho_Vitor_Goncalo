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
                <a >Roupa</a>
                <div class="dropdown-content">
                    <a href="#">Casacos</a>
                    <a href="#">Calças de Ganga</a>
                    <a href="#">Calças de Fato de Treino</a>
                    <a href="#">Camisolas</a>
                    <a href="#">Sweats</a>
                    <a href="#">Fatos de Treino</a>
                </div>
            </div>
            <div class="dropdown">
                <a >Calçado</a>
                <div class="dropdown-content">
                    <a href="#">Caminhada e Outdoor</a>
                    <a href="#">Futebol</a>
                    <a href="#">Futsal</a>
                    <a href="#">Ciclismo</a>
                </div>
            </div>
            <div class="dropdown">
                <a >Acessórios</a>
                <div class="dropdown-content">
                    <a href="#">Chapéus</a>
                    <a href="#">Gorros</a>
                    <a href="#">Bolsas</a>
                    <a href="#">Mochilas</a>
                    <a href="#">Meias</a>
                    <a href="#">Óculos</a>
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

<br><br><br><br>
<?php
if (isset($_GET['tipo'])) {
    $tipo = $conexao->real_escape_string($_GET['tipo']); // Previne SQL Injection

    $query = "SELECT * FROM produtos WHERE Tipo = '$tipo'";
    $result = $conexao->query($query);

    if ($result->num_rows > 0) {
        ?>
<div class="product-card">
    <div class="product-image">
        <img <?php echo$row['Imagem']; ?> alt="<?php echo $row['Nome']; ?>">
    </div>
    <div class="product-info">
        <h2 class="product-name"><?php echo $row['Nome']; ?></h2>
        <p class="product-price"><?php echo number_format($row['Preco'], 2, ',', '.'); ?>€</p>
        <p class="product-stock">Disponível: <?php echo $row['Stock']; ?> unidades</p>
        <button class="product-btn">Adicionar ao Carrinho</button>
    </div>
</div>
<?php
    }       
}
?>
</body>
</html>