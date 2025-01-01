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
                <img src="Logo.png" alt="" style="width:10rem;">
            </div>
        </div>

        <div class="nav-center">
            <a href="#">Home</a>
            <div class="dropdown">
                <a href="#">Clothing</a>
                <div class="dropdown-content">
                    <a href="#">Option 1</a>
                    <a href="#">Option 2</a>
                </div>
            </div>
            <div class="dropdown">
                <a href="#">Sneakers</a>
                <div class="dropdown-content">
                    <a href="#">Option 1</a>
                    <a href="#">Option 2</a>
                </div>
            </div>
            <div class="dropdown">
                <a href="#">Accessories</a>
                <div class="dropdown-content">
                    <a href="#">Option 1</a>
                    <a href="#">Option 2</a>
                </div>
            </div>
        </div>

        <div class="nav-right">
            <?php if (is_logged_in()): ?>
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
</body>
</html>

