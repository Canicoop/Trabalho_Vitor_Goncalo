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
                <a href="carrinho.php" rel="noopener noreferrer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" color="black"fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 14H4a.5.5 0 0 1-.491-.408L1.01 2H.5a.5.5 0 0 1-.5-.5zM4.415 13h8.17l1.2-6.4H3.314L4.415 13zM5 15a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm9 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                </a>

                
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