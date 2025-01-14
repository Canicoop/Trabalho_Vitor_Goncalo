<?php
include 'conexao.php';



// Query para obter os tipos de produtos, agrupados por categoria
$sql_navbar = "SELECT descricao, tipo FROM tipo ORDER BY tipo, descricao";
$result_navbar = mysqli_query($conexao, $sql_navbar);

// Organizar os tipos por categoria
$categorias = [];
if ($result_navbar && $result_navbar->num_rows > 0) {
    while ($row = $result_navbar->fetch_assoc()) {
        $categorias[$row['tipo']][] = $row['descricao'];
    }
}

?>

<nav class="navbar">
    <div class="nav-left">
        <div id="logo">
            <a href="../paginaInicial/PaginaInicial.php">
                <img src="../logo/Logo.png" alt="Logo" style="width:10rem;">
            </a>
        </div>
    </div>

    <div class="nav-center">
        <?php foreach ($categorias as $categoria => $tipos): ?>
            <div class="dropdown">
                <a><?php echo ucfirst($categoria); ?></a>
                <div class="dropdown-content">
                    <?php foreach ($tipos as $tipo): ?>
                        <a href="../paginaProduto/PaginaProduto.php?tipo=<?php echo urlencode($tipo); ?>">
                            <?php echo htmlspecialchars($tipo); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="nav-right">
        <?php if (is_logged_in()): ?>
            <div class="icon">
                <a href="../carrinho/carrinho.php" rel="noopener noreferrer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" color="black" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 14H4a.5.5 0 0 1-.491-.408L1.01 2H.5a.5.5 0 0 1-.5-.5zM4.415 13h8.17l1.2-6.4H3.314L4.415 13zM5 15a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm9 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                </a>
            </div>
            <div class="icon dropdown">
                <span class="menu-toggle">
                    <?php
                    $sql = "SELECT Imagem FROM users WHERE Username = '{$_SESSION['username']}'";
                    $result = $conexao->query($sql);
                    if ($result && $row = $result->fetch_assoc()) {
                        $imageURL = '../imagens/' . $row["Imagem"];
                    ?>
                    <img class="user-photo" src="<?php echo $imageURL; ?>" alt="User Photo">
                    <span class="user-name"> <?php echo $_SESSION['username']; ?> </span> ▼
                </span>
                <div class="dropdown-content">
                    <a href="../minhas_encomendas/minhas_encomendas.php">Minhas Encomendas</a>
                    <a href="../minhaConta/minha_conta.php">Minha Conta</a>
                    <a href="../logout.php">Logout</a>
                </div>
                <?php } ?>
            </div>
        <?php else: ?>
            <div class="icon">
                <a href="../login/login.php">
                    <svg xmlns="http://www.w3.org/2000/svg" color="black" width="30" height="30" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>
