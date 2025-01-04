<?php
include 'conexao.php';
include 'session_check.php';

// Verifica se alguma ação foi solicitada via GET
$acao = isset($_GET['acao']) ? $_GET['acao'] : '';

// Processamento dos formulários
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar_stock'])) {
        $produto = $_POST['produto'];
        $quantidade = $_POST['quantidade'];
        $sql = "UPDATE produtos SET Stock = Stock + $quantidade WHERE Nome = '$produto'";

    } elseif (isset($_POST['eliminar_stock'])) {
        $id = $_POST['id'];
        $sql = "UPDATE produtos SET Stock = Stock - $quantidade WHERE Nome = '$produto'";
    } elseif (isset($_POST['atualizar_stock'])) {
        $id = $_POST['id'];
        $quantidade = $_POST['quantidade'];
        $sql = "UPDATE produtos SET quantidade = $quantidade WHERE Nome = '$produto'";
    } elseif (isset($_POST['adicionar_tendencia'])) {
        $descricao = $_POST['descricao'];
        $sql = "INSERT INTO tendencias (descricao) VALUES ('$descricao')";
    } elseif (isset($_POST['eliminar_tendencia'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM tendencias WHERE id = $id";
    } elseif (isset($_POST['atualizar_tendencia'])) {
        $id = $_POST['id'];
        $descricao = $_POST['descricao'];
        $sql = "UPDATE tendencias SET descricao = '$descricao' WHERE id = $id";
    } elseif (isset($_POST['adicionar_colecao'])) {
        $nome = $_POST['nome'];
        $sql = "INSERT INTO colecoes (nome) VALUES ('$nome')";
    } elseif (isset($_POST['eliminar_colecao'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM colecoes WHERE id = $id";
    } elseif (isset($_POST['atualizar_colecao'])) {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $sql = "UPDATE colecoes SET nome = '$nome' WHERE id = $id";
    } elseif (isset($_POST['adicionar_produto'])) {
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $sql = "INSERT INTO produtos (nome, preco) VALUES ('$nome', $preco)";
    } elseif (isset($_POST['eliminar_produto'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM produtos WHERE id = $id";
    } elseif (isset($_POST['atualizar_produto'])) {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $sql = "UPDATE produtos SET nome = '$nome', preco = $preco WHERE id = $id";
    }
    
    if (isset($sql) && $conexao->query($sql) === TRUE) {
        echo "Operação realizada com sucesso!";
    } elseif (isset($sql)) {
        echo "Erro: " . $conexao->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Produtos</title>
    <link rel="stylesheet" href="PaginaInicial.css">
    <link rel="stylesheet" href="gestormanagementstyle.css">
    <style>
.menu-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    padding: 20px;
    background-color: grey;
}

    .menu-item {
    width: 250px;
    height: 180px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    background-color: #f8f9fa;
    border: 2px solid #ccc;
    border-radius: 10px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
    padding: 10px;
    }

        .menu-item img {
          width: 130px;
            height: 130px;
            object-fit: cover;
        margin-bottom: 10px;
          }

        .menu-item:hover {
    background-color: #e9ecef;
    transform: scale(1.05);
        }
        .section {
            position: relative;
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: red;
        }
        .close-btn:hover {
            color: darkred;
        }

        .as{
            font-size: 17px;
            color: black;
        }
    </style>
    <script>
        function fecharFormulario() {
            window.location.href = 'gestor_management.php';
        }
    </script>
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
                $resultUser = mysqli_query($conexao, $sql);
                while ($rowUser = mysqli_fetch_array($resultUser)) {
                    $imageURL = 'imagens/' . $rowUser["Imagem"];
                ?>
                    <img class="user-photo" src="<?php echo $imageURL; ?>" alt="User Photo">
                    <span class="user-name"><?php echo $_SESSION['username']; ?></span>
                <?php } ?>
            </span>
        </div>
    </nav>

    <div class="container">
        <!-- MENU PRINCIPAL -->
        <div class="menu-container">
        <div class="menu-container">
        <form method="GET"><button type="submit" name="acao" value="adicionar_stock" class="menu-item"><img src="Stock.jpeg"><span class="as">Adicionar Stock</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="eliminar_stock" class="menu-item"><img src="Stock.jpeg"><span class="as">Eliminar Stock</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="atualizar_stock" class="menu-item"><img src="Stock.jpeg"><span class="as">Atualizar Stock</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="adicionar_tendencia" class="menu-item"><img src="Tendencia.jpeg"><span class="as">Adicionar Tendência</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="eliminar_tendencia" class="menu-item"><img src="Tendencia.jpeg"><span class="as">Eliminar Tendência</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="atualizar_tendencia" class="menu-item"><img src="Tendencia.jpeg"><span class="as">Atualizar Tendência</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="nova_colecao" class="menu-item"><img src="Colecao.jpeg"><span class="as">Adicionar Coleção</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="eliminar_colecao" class="menu-item"><img src="Colecao.jpeg"><span class="as">Eliminar Coleção</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="atualizar_colecao" class="menu-item"><img src="Colecao.jpeg"><span class="as">Atualizar Coleção</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="novo_produto" class="menu-item"><img src="Produto.png"><span class="as">Adicionar Produto</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="eliminar_produto" class="menu-item"><img src="Produto.png"><span class="as">Eliminar Produto</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="atualizar_produto" class="menu-item"><img src="Produto.png"><span class="as">Atualizar Produto</span></button></form>
    </div>
</div>

    <?php if (isset($_GET['acao'])) { $acao = $_GET['acao']; ?>
        <div class="section">
            <button class="close-btn" onclick="fecharFormulario()">❌</button>

            <?php if ($acao === 'adicionar_stock') { ?>
                <h3>Adicionar Stocks</h3>
                <form method="POST">
                    <label for="produto">Produto:</label>
                    <select>
                    <option selected>Selecione uma opção</option>
                                <?php
                                $query = "SELECT * FROM produtos";
                                $result = mysqli_query($conexao, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                            
                                    echo '<option value="' . $row['Nome'] .' </option>';
                                }
                                ?>
                    </select>
                    <label for="quantidade">Stock:</label>
                    <input name="quantidade" type="number" name="id" required>            
                    <button type="submit" name="adicionar_stock">Adicionar</button>
                </form>
            <?php } elseif ($acao === 'eliminar_stock') { ?>
                <h3>Eliminar Stock</h3>
                <form method="POST">
                    <label for="id">Produto:</label>
                    <select>
                    <option selected>Selecione uma opção</option>
                                <?php
                                $query = "SELECT * FROM produtos";
                                $result = mysqli_query($conexao, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                            
                                    echo '<option value="' . $row['Nome'] .' </option>';
                                }
                                ?>
                    </select>
                    <label for="quantidade">Stock:</label>
                    <input name="quantidade" value="<?php echo $row['Stock']; ?>" type="number" name="id" required>
                    <button type="submit" name="eliminar_stock">Eliminar</button>
                </form>
            <?php } elseif ($acao === 'atualizar_stock') { ?>
                <h3>Atualizar Estoque</h3>
                <form method="POST">
                <label for="id">Produto:</label>
                    <select>
                    <option selected>Selecione uma opção</option>
                                <?php
                                $query = "SELECT * FROM produtos";
                                $result = mysqli_query($conexao, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                            
                                    echo '<option value="' . $row['Nome'] .' </option>';
                                }
                                ?>
                    </select>
                    <label for="quantidade">Stock:</label>
                    <input name="quantidade" value="<?php echo $row['Stock']; ?>" type="number" name="id" required>
                    <button type="submit" name="atualizar_stock">Atualizar</button>
                </form>
            <?php } elseif ($acao === 'adicionar_tendencia') { ?>
                <h3>Adicionar Tendência</h3>
                <form method="POST">
                    <label for="descricao">Descrição:</label>
                    <input type="text" name="descricao" required>
                    <button type="submit" name="adicionar_tendencia">Adicionar</button>
                </form>
            <?php } elseif ($acao === 'eliminar_tendencia') { ?>
                <h3>Eliminar Tendência</h3>
                <form method="POST">
                    <label for="id">ID da Tendência:</label>
                    <input type="number" name="id" required>
                    <button type="submit" name="eliminar_tendencia">Eliminar</button>
                </form>
            <?php } elseif ($acao === 'atualizar_tendencia') { ?>
                <h3>Atualizar Tendência</h3>
                <form method="POST">
                    <label for="id">ID da Tendência:</label>
                    <input type="number" name="id" required>
                    <label for="descricao">Nova Descrição:</label>
                    <input type="text" name="descricao" required>
                    <button type="submit" name="atualizar_tendencia">Atualizar</button>
                </form>
            <?php } elseif ($acao === 'nova_colecao') { ?>
                <h3>Criar Nova Coleção</h3>
                <form method="POST">
                    <label for="nome_colecao">Nome da Coleção:</label>
                    <input type="text" name="nome_colecao" required>
                    <button type="submit" name="adicionar_colecao">Criar</button>
                </form>
            <?php } elseif ($acao === 'eliminar_colecao') { ?>
                <h3>Eliminar Coleção</h3>
                <form method="POST">
                    <label for="id">ID da Coleção:</label>
                    <input type="number" name="id" required>
                    <button type="submit" name="eliminar_colecao">Eliminar</button>
                </form>
            <?php } elseif ($acao === 'atualizar_colecao') { ?>
                <h3>Atualizar Coleção</h3>
                <form method="POST">
                    <label for="id">ID da Coleção:</label>
                    <input type="number" name="id" required>
                    <label for="nome">Novo Nome:</label>
                    <input type="text" name="nome" required>
                    <button type="submit" name="atualizar_colecao">Atualizar</button>
                </form>
            <?php } elseif ($acao === 'novo_produto') { ?>
                <h3>Adicionar Novo Produto</h3>
                <form method="POST" enctype="multipart/form-data">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" required>
                    <label for="preco">Preço:</label>
                    <input type="number" step="0.01" name="preco" required>
                    <label for="stock">Stock:</label>
                    <input type="number" name="stock" required>
                    <label for="tipo">Tipo:</label>
                    <select name="tipo" required>
                        <option value="">Selecione</option>
                        <option value="eletronico">Eletrônico</option>
                        <option value="vestuario">Vestuário</option>
                    </select>
                    <label for="genero">Género:</label>
                    <select name="genero">
                        <option value="">Selecione</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Feminino">Feminino</option>
                    </select>
                    <label for="colecao">Coleção:</label>
                    <select name="colecao">
                        <option value="">Selecione</option>
                    </select>
                    <label for="imagem">Imagem:</label>
                    <input type="file" name="imagem" required>
                    <button type="submit" name="adicionar_produto">Adicionar</button>
                </form>
            <?php } elseif ($acao === 'atualizar_produto') { ?>
                <h3>Atuaizar Produto</h3>
                <form method="POST">
                    <label for="id">ID do Produto:</label>
                    <input type="number" name="id" required>
                    <button type="submit" name="atualizar_produto">Eliminar</button>
                </form>
            <?php } ?>
        </div>
    <?php } ?>
</body>
</html>

