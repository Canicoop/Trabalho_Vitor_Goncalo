<?php
include 'conexao.php';
include 'session_check.php';

// Funções para diferentes ações do gestor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_stock'])) {
        $id_produto = $_POST['id_produto'];
        $novo_stock = $_POST['novo_stock'];

        $query = "UPDATE produtos SET Stock = ? WHERE ID = ?";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param('ii', $novo_stock, $id_produto);
        $stmt->execute();
    }

    if (isset($_POST['add_tendencia'])) {
        $id_produto = $_POST['id_produto'];

        $query = "UPDATE produtos SET Tendencia = 1 WHERE ID = ?";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param('i', $id_produto);
        $stmt->execute();
    }

    if (isset($_POST['add_produto'])) {
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $stock = $_POST['stock'];
        $tipo = $_POST['tipo'];
        $imagem = $_FILES['imagem']['name'];

        $destino = 'imagens/' . $imagem;
        move_uploaded_file($_FILES['imagem']['tmp_name'], $destino);

        $query = "INSERT INTO produtos (Nome, Preco, Stock, Tipo, Imagem) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param('sdiss', $nome, $preco, $stock, $tipo, $imagem);
        $stmt->execute();
    }

    if (isset($_POST['add_colecao'])) {
        $nome_colecao = $_POST['nome_colecao'];

        $query = "INSERT INTO colecoes (Nome) VALUES (?)";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param('s', $nome_colecao);
        $stmt->execute();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Produtos</title>
    <link rel="stylesheet" href="PaginaInicial.css">
    <link rel="stylesheet" href="gestormanagementstyle.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-left">
            <div id="logo">
                <img src="Logo.png" alt="Logo" style="width:10rem;">
            </div>
        </div>
        <h1>Centro de Gestores</h1>
        <div class="nav-right">
            <span class="menu-toggle">
                <?php
                $sql = "SELECT Imagem FROM users WHERE Username = '{$_SESSION['username']}'";
                $result = mysqli_query($conexao, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    $imageURL = 'imagens/' . $row["Imagem"];
                ?>
                    <img class="user-photo" src="<?php echo $imageURL; ?>" alt="User Photo">
                    <span class="user-name"><?php echo $_SESSION['username']; ?></span>
                <?php } ?>
            </span>
        </div>
    </nav>
    <div class="container">
        <h2>Gerenciar Produtos</h2>

        <div class="section">
            <h3>Atualizar Estoque</h3>
            <form method="POST">
                <label for="id_produto">ID do Produto:</label>
                <input type="number" name="id_produto" required>

                <label for="novo_stock">Novo Estoque:</label>
                <input type="number" name="novo_stock" required>

                <button type="submit" name="update_stock">Atualizar</button>
            </form>
        </div>

        <div class="section">
            <h3>Adicionar à Tendência</h3>
            <form method="POST">
                <label for="id_produto">ID do Produto:</label>
                <input type="number" name="id_produto" required>

                <button type="submit" name="add_tendencia">Adicionar</button>
            </form>
        </div>

        <div class="section">
            <h3>Adicionar Novo Produto</h3>
            <form method="POST" enctype="multipart/form-data">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" required>

                <label for="preco">Preço:</label>
                <input type="number" step="0.01" name="preco" required>

                <label for="stock">Estoque:</label>
                <input type="number" name="stock" required>

                <label for="tipo">Tipo:</label>
                <input type="text" name="tipo" required>

                <label for="imagem">Imagem:</label>
                <input type="file" name="imagem" required>

                <button type="submit" name="add_produto">Adicionar</button>
            </form>
        </div>

        <div class="section">
            <h3>Criar Nova Coleção</h3>
            <form method="POST">
                <label for="nome_colecao">Nome da Coleção:</label>
                <input type="text" name="nome_colecao" required>

                <button type="submit" name="add_colecao">Criar</button>
            </form>
        </div>
    </div>
</body>
</html>
