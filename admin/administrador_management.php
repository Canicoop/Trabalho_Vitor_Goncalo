<?php
include '../conexao.php';
include '../session_check.php';

// Verifica se alguma ação foi solicitada via GET
$acao = isset($_GET['acao']) ? $_GET['acao'] : '';

// Processamento dos formulários
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    if (isset($_POST['adicionar_stock'])) {
        $produto_id = $_POST['produto_id'];  
        $quantidade = $_POST['quantidade'];
    
        $sql = "UPDATE produtos SET Stock = Stock + ? WHERE id = ?";
    
        $stmt = mysqli_prepare($conexao, $sql);
    
        mysqli_stmt_bind_param($stmt, "ii", $quantidade, $produto_id);
    
        mysqli_stmt_execute($stmt);
    
        if (mysqli_stmt_execute($stmt)) {
            echo "<p class='success'>Dados atualizados com sucesso!</p>";
            header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
        } else {
            $error = "Erro ao adicionar Stock: " . mysqli_error($conexao);
        }
    
    
        mysqli_stmt_close($stmt);
    }
        
        elseif (isset($_POST['eliminar_stock'])) {
            $produto_id = $_POST['produto_id'];  // Usando o nome correto da variável
            $quantidade = $_POST['quantidade'];
        
            // Consulta SQL para atualizar o stock
            $sql = "UPDATE produtos SET Stock = Stock - ? WHERE id = ?";
        
            $stmt = mysqli_prepare($conexao, $sql);
        
            // Associar os parâmetros corretamente (quantidade e id do produto)
            mysqli_stmt_bind_param($stmt, "ii", $quantidade, $produto_id);
        
            // Executar a consulta
            mysqli_stmt_execute($stmt);
        
            if (mysqli_stmt_execute($stmt)) {
                echo "<p class='success'>Dados atualizados com sucesso!</p>";
                header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
            } else {
                $error = "Erro ao eliminar stock: " . mysqli_error($conexao);
            }
        
        
            // Fechar a declaração
            mysqli_stmt_close($stmt);
        } 
        
        elseif (isset($_POST['adicionar_tendencia'])) {
            // Verificar se um produto foi selecionado
            if (!isset($_POST['produto_id']) || empty($_POST['produto_id'])) {
                die("Erro: Selecione um produto.");
            }
        
            $produto_id = intval($_POST['produto_id']); // Converter para número inteiro
        
            // Verificar se o produto existe na tabela 'produtos'
            $checkProduto = mysqli_query($conexao, "SELECT id FROM produtos WHERE id = $produto_id");
            if (mysqli_num_rows($checkProduto) == 0) {
            }
        
            // Inserir na tabela tendencias
            $sql = "INSERT INTO tendencia (id_produto) VALUES (?)";
            $stmt = mysqli_prepare($conexao, $sql);
            mysqli_stmt_bind_param($stmt, "i", $produto_id);
            if (mysqli_stmt_execute($stmt)) {
                echo "<p class='success'>Dados atualizados com sucesso!</p>";
                header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
            } else {
                $error = "Erro ao adicionar tendencia: " . mysqli_error($conexao);
            }
            mysqli_stmt_close($stmt);
    
        }
    
        elseif (isset($_POST['adicionar_tipo'])) {
            $tipo = trim($_POST['tipo']);
            $tipo_tipo = trim($_POST['tipo_tipo']);
            
            if (!empty($tipo) && !empty($tipo_tipo)) { // Verifica se os campos não estão vazios
                
                // Verificar se já existe um tipo com a mesma descrição
                $sql = "SELECT id FROM tipo WHERE descricao = ?";
                $stmt = mysqli_prepare($conexao, $sql);
                mysqli_stmt_bind_param($stmt, "s", $tipo);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt); // Necessário para verificar o número de linhas retornadas
                
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $error = "Erro: O tipo já existe!";
                } else {
                    // Inserir novo tipo
                    $sql = "INSERT INTO tipo (descricao, tipo) VALUES (?, ?)";
                    $stmt = mysqli_prepare($conexao, $sql);
                    mysqli_stmt_bind_param($stmt, "ss", $tipo, $tipo_tipo);
        
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<p class='success'>Dados atualizados com sucesso!</p>";
                        header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
                    } else {
                        $error = "Erro ao adicionar tipo: " . mysqli_error($conexao);
                    }
                }
                
                mysqli_stmt_close($stmt);
            } 
            
        }
        
        // Eliminar Tipo
        elseif (isset($_POST['eliminar_tipo'])) {
            if (!isset($_POST['tipo']) || empty($_POST['tipo'])) {
                echo "<p style='color: red;'>Erro: Nenhum tipo selecionado!</p>";
            } else {
                $tipo_id = intval($_POST['tipo']);
        
                $sql = "DELETE FROM tipo WHERE id = ?";
                $stmt = mysqli_prepare($conexao, $sql);
                mysqli_stmt_bind_param($stmt, "i", $tipo_id);
        
                if (mysqli_stmt_execute($stmt)) {
                    echo "<p class='success'>Dados atualizados com sucesso!</p>";
            header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
                } else {
                    $error = "Erro ao adicionar tipo: " . mysqli_error($conexao);
                }
        
                mysqli_stmt_close($stmt);
            }
        }
        
        elseif (isset($_POST['atualizar_tipo'])) {
        
            $tipo_id = intval($_POST['tipo_id']);
            $descricao = trim($_POST['descricao']);
            $tipo_tipo = trim($_POST['tipo_tipo']);
        
            if ($tipo_id > 0 && !empty($descricao) && !empty($tipo_tipo)) {
                // Verificar se já existe outro tipo com a mesma descrição
                $sql = "SELECT id FROM tipo WHERE descricao = ? AND id != ?";
                $stmt = mysqli_prepare($conexao, $sql);
                mysqli_stmt_bind_param($stmt, "si", $descricao, $tipo_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
        
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    echo "<p style='color:red;'>Erro: Já existe um tipo com esta descrição!</p>";
                } else {
                    // Atualizar o tipo
                    $sql = "UPDATE tipo SET descricao = ?, tipo = ? WHERE id = ?";
                    $stmt = mysqli_prepare($conexao, $sql);
                    mysqli_stmt_bind_param($stmt, "ssi", $descricao, $tipo_tipo, $tipo_id);
        
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<p class='success'>Dados atualizados com sucesso!</p>";
                        header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
                    } else {
                        echo "<p class='success'>Dados atualizados com sucesso!</p>";
                        header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos                
                        }
                }
        
                mysqli_stmt_close($stmt);
            } 
         }
        
                
        
        elseif (isset($_POST['eliminar_tendencia'])) {
            // Verificar se um produto foi selecionado
            if (!isset($_POST['produto_id']) || empty($_POST['produto_id'])) {
            }
        
            $produto_id = intval($_POST['produto_id']); // Converter para número inteiro
        
            // Verificar se o produto existe na tabela 'produtos'
            $checkProduto = mysqli_query($conexao, "SELECT id FROM produtos WHERE id = $produto_id");
            if (mysqli_num_rows($checkProduto) == 0) {
            }
        
            // Inserir na tabela tendencias
            $sql = "DELETE FROM tendencia WHERE id_produto = ?";
            $stmt = mysqli_prepare($conexao, $sql);
            mysqli_stmt_bind_param($stmt, "i", $produto_id);
            if (mysqli_stmt_execute($stmt)) {
                echo "<p class='success'>Dados atualizados com sucesso!</p>";
            header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
            } else {
                $error = "Erro ao adicionar tendência: " . mysqli_error($conexao);
            }
            mysqli_stmt_close($stmt);
        
        }
        
        elseif (isset($_POST['adicionar_colecao'])) {
            $descricao = $_POST['nome_colecao'];
            $targetDir = "colecoes/";
            $fileName = basename($_FILES["imagem_colecao"]["name"]);
            $targetFilePath = $targetDir . $fileName;
    
            if(move_uploaded_file($_FILES["imagem_colecao"]["tmp_name"], $targetFilePath)){
    
                        $sql = "SELECT * FROM colecoes WHERE descricao = ?";
                        $stmt = mysqli_prepare($conexao, $sql);
                        mysqli_stmt_bind_param($stmt, "s", $descricao);
                        if (mysqli_stmt_execute($stmt)) {
                            $success = "Tipo adicionado com sucesso!";
                        } else {
                            $error = "Erro ao adicionar tipo: " . mysqli_error($conexao);
                        }
                        $result = mysqli_stmt_get_result($stmt);
                        
                       
                if (mysqli_num_rows($result) > 0) {
                    $error = "Coleção já existe!";
                } else {
                    $sql = "INSERT INTO colecoes (descricao, Imagem) VALUES (?, ?)";
                    $stmt = mysqli_prepare($conexao, $sql);
                        mysqli_stmt_bind_param($stmt, "ss", $descricao, $fileName);
                       mysqli_stmt_execute($stmt);
                       echo "<p class='success'>Dados atualizados com sucesso!</p>";
            header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
                }
        }
    }
    
    
                    elseif (isset($_POST['eliminar_colecao'])) {
    
                        $id = intval($_POST['nome_colecao_eliminar']); // Certifica-se de que seja um número inteiro
    
                        // Buscar o nome do arquivo da imagem
                    $query = "SELECT Imagem FROM colecoes WHERE id = $id";
                        $result = mysqli_query($conexao, $query);
    
                        if ($row = mysqli_fetch_assoc($result)) {
                            $imagem = $row['Imagem'];
                            $caminho_imagem = "colecoes/" . $imagem;
    
                            // Verifica se a imagem existe e exclui
                            if (file_exists($caminho_imagem)) {
                                unlink($caminho_imagem);
                            }
                        }
    
                        // Excluir a coleção
                        $sql = "DELETE FROM colecoes WHERE id = $id";
                        if (mysqli_query($conexao, $sql)) {
                            echo "<p class='success'>Dados atualizados com sucesso!</p>";
            header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
                        } else {
                            $error = "Erro ao eliminar a coleção!";
                        }
                    }
    
                    
                    if (isset($_POST['atualizar_colecao'])) {
                        $id = intval($_POST['colecao_id']);
                        $nome = mysqli_real_escape_string($conexao, $_POST['nome_colecao_atualizar']);
                    
                        // Buscar a imagem atual
                        $query = "SELECT imagem FROM colecoes WHERE id = ?";
                        $stmt = mysqli_prepare($conexao, $query);
                        mysqli_stmt_bind_param($stmt, "i", $id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                    
                        if ($row = mysqli_fetch_assoc($result)) {
                            $imagem_antiga = $row['imagem'];
                            $caminho_imagem_antiga = "colecoes/" . $imagem_antiga;
                        }
                    
                        $fileName = !empty($_FILES["imagem"]["name"]) ? basename($_FILES["imagem"]["name"]) : $imagem_antiga;
                        $targetFilePath = "colecoes/" . $fileName;
                    
                        if (!empty($_FILES["imagem"]["name"])) {
                            if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $targetFilePath)) {
                                if (!empty($imagem_antiga) && file_exists($caminho_imagem_antiga)) {
                                    unlink($caminho_imagem_antiga);
                                }
                            }
                        }
                    
                        // Atualizar a coleção no banco de dados usando Prepared Statements
                        $sql = "UPDATE colecoes SET descricao = ?, imagem = ? WHERE id = ?";
                        $stmt = mysqli_prepare($conexao, $sql);
                        
                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "ssi", $nome, $fileName, $id);
                            if (mysqli_stmt_execute($stmt)) {
                                echo "<p class='success'>Dados atualizados com sucesso!</p>";
                             header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
                            } else {
                                $error = "Erro ao atualizar Coleção: " . mysqli_error($conexao);
                            }
                        } 
                    }
                    
    
                    
                    if (isset($_POST['adicionar_produto'])) {
    
                                            // Sanitização e Validação dos dados
                                            $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
                                            $preco = floatval($_POST['preco']);
                                            $stock = intval($_POST['stock']);
                                            $tipo_id = intval($_POST['tipo']);
                                            $genero = mysqli_real_escape_string($conexao, $_POST['genero']);
                                            $colecao_id = intval($_POST['colecao']);
                                            $tamanho = intval($_POST['tamanho']);
    
    
                 
    
    
                    
                        // Validação e upload da imagem
                                $targetDir = "produtos/";
                                $fileName = basename($_FILES["imagem"]["name"]);
                                $targetFilePath = $targetDir . $fileName;
    
                                $sql = "SELECT * FROM produtos WHERE Nome = ? AND tamanho = ?";
                                $stmt = mysqli_prepare($conexao, $sql);
                                mysqli_stmt_bind_param($stmt, "si", $nome, $tamanho);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                
                                if (mysqli_num_rows($result) > 0) {
                                    echo "Erro: Produto já existe!";
                
                                }
    
                                // Move a imagem para a pasta
                                elseif (move_uploaded_file($_FILES["imagem"]["tmp_name"], $targetFilePath)) {
                                    // Consulta corrigida
                                    $sql = "INSERT INTO produtos (Nome, Preco, tipo_id, Imagem, Stock, Colecao, Genero, tamanho) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
                                    if ($stmt = mysqli_prepare($conexao, $sql)) {
                                        // Correção na ordem dos parâmetros e tipos
                                        mysqli_stmt_bind_param($stmt, "sdissisi",  $nome,$preco,$tipo_id, $fileName,$stock,$colecao_id,$genero,$tamanho);
                                        
                                        if (mysqli_stmt_execute($stmt)) {
                                            echo "<p class='success'>Dados atualizados com sucesso!</p>";
            header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
                                        } else {
                                            $error = "Erro ao adicionar produto: " . mysqli_error($conexao);
                                        } 
                                        mysqli_stmt_close($stmt);
                                    } 
                                }
                            }
                    
                    elseif (isset($_POST['eliminar_produto'])) {
                        // Verificar se o ID do produto foi passado
                        if (isset($_POST['id'])) {
                            $id = $_POST['id'];  // Garantir que seja um valor inteiro
                    
                            // Usando prepared statement para prevenir SQL injection
                            $sql = "DELETE FROM produtos WHERE id = ?";
                            if ($stmt = mysqli_prepare($conexao, $sql)) {
                                mysqli_stmt_bind_param($stmt, "i", $id);  // "i" para inteiro
                    
                                // Executar a consulta
                                if (mysqli_stmt_execute($stmt)) {
                                    echo "<p class='success'>Dados atualizados com sucesso!</p>";
            header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
                                } else {
                                    $error = "Erro ao eliminar produto: " . mysqli_error($conexao);
                                }
                    
                                // Fechar a declaração
                                mysqli_stmt_close($stmt);
                    }
                }
            }
    
            elseif (isset($_POST['atualizar_produto'])) {
        $produto_id = $_POST['produto_id'];
        $nome_produto = $_POST['nome_produto_atualizar'];
        $preco = $_POST['preco'];
        $tipo_id = $_POST['tipo_id'];
        $stock = $_POST['stock'];
        $colecao = $_POST['colecao'];
        $genero = $_POST['genero'];
        $tamanho = $_POST['tamanho'];
    
        
        // Buscar a imagem atual
        $query = "SELECT Imagem FROM produtos WHERE id = ?";
        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "i", $produto_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $imagem_antiga = $row['Imagem'];
            $caminho_imagem_antiga = "produtos/" . $imagem_antiga;
        }
        
        // Definir o nome do arquivo da imagem
        $fileName = $imagem_antiga; // Inicializa com o nome da imagem antiga, caso não haja nova imagem
    
        // Verificar se uma nova imagem foi enviada
        if (!empty($_FILES["imagem"]["name"])) {
            $fileName = basename($_FILES["imagem"]["name"]); // Pega o nome da nova imagem
            $targetFilePath = "produtos/" . $fileName;
    
            // Verificar se a imagem foi movida corretamente para a pasta
            if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $targetFilePath)) {
                // Se a imagem antiga existir, apaga-a da pasta
                if (!empty($imagem_antiga) && file_exists($caminho_imagem_antiga)) {
                    unlink($caminho_imagem_antiga);
                }
            }
        } 
    
        $sql = "SELECT * FROM produtos WHERE Nome = ? AND tamanho = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "si", $nome_produto, $tamanho);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if (mysqli_num_rows($result) > 0) {
            echo "Erro: Produto já existe!";
    
        }
    
        // Verifique se o nome do arquivo não está vazio
        elseif (!empty($fileName)) {
            // Atualiza o produto no banco de dados com a nova imagem
            $sql = "UPDATE produtos SET Nome = ?, Preco = ?, tipo_id = ?, Stock = ?, Colecao = ?, Genero = ?, Imagem = ?, tamanho = ? WHERE id = ?";
            $stmt = mysqli_prepare($conexao, $sql);
            mysqli_stmt_bind_param($stmt, "sdiiisssi", $nome_produto, $preco, $tipo_id, $stock, $colecao, $genero, $fileName, $tamanho, $produto_id);
    
            if (mysqli_stmt_execute($stmt)) {
                echo "<p class='success'>Dados atualizados com sucesso!</p>";
                header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
            } else {
                $error = "Erro ao atualizado produto: " . mysqli_error($conexao);
            
            }}}
    }

    elseif (isset($_POST['novo_utilizador'])) {
        // Capturar os dados do formulário
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Criptografa a senha
        $nivel = 1;
    
        // Caminho da imagem
        $targetDir = "imagens/";
        $fileName = basename($_FILES["imagem"]["name"]);
        $targetFilePath = $targetDir . $fileName;
    
        // Verificar se o usuário já existe
        $sql = "SELECT * FROM users WHERE Username = ? OR Email = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if (mysqli_num_rows($result) > 0) {
            echo "Utilizador ou email já existe!";
        } else {
            // Mover a imagem para o diretório
            if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $targetFilePath)) {
                $sql = "INSERT INTO users (Nome, Email, Username, Password, Nível, Imagem) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conexao, $sql);
                mysqli_stmt_bind_param($stmt, "ssssis", $nome, $email, $username, $password, $nivel, $fileName);
    
                if (mysqli_stmt_execute($stmt)) {
                    echo "<p class='success'>Dados atualizados com sucesso!</p>";
                header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
                } 
                mysqli_stmt_close($stmt);

            } 
        }
    }
    
    
    elseif (isset($_POST['eliminar_utilizador'])) {
        if (!empty($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
    
    
           
                $sql = "DELETE FROM users WHERE id = ?";
                $stmt = mysqli_prepare($conexao, $sql);
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                
                if (mysqli_stmt_execute($stmt)) {
                    echo "<p class='success'>Dados atualizados com sucesso!</p>";
                header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
                } 
    
                mysqli_stmt_close($stmt);
            } 
            } 
    
    
            elseif (isset($_POST['atualizar_utilizador'])) {
                $id = intval($_POST['user_id']);
                $nome = $_POST['nome'];
                $email = $_POST['email'];
                $username = $_POST['username'];
            
                $query = "SELECT Imagem FROM users WHERE id = ?";
                $stmt = mysqli_prepare($conexao, $query);
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
            
                $imagem_antiga = null;
                if ($row = mysqli_fetch_assoc($result)) {
                    $imagem_antiga = $row['Imagem'];
                    $caminho_imagem_antiga = "imagens/" . $imagem_antiga;
                }
            
                if (!empty($_FILES["imagem"]["name"]) && $_FILES["imagem"]["error"] == 0) {
                    $fileName = basename($_FILES["imagem"]["name"]);
                    $targetFilePath = "imagens/" . $fileName;
            
                    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $targetFilePath)) {
                        if (!empty($imagem_antiga) && file_exists($caminho_imagem_antiga)) {
                            unlink($caminho_imagem_antiga);
                        }
                    }
                } else {
                    $fileName = $imagem_antiga;
                }
            
                $sql = "UPDATE users SET Nome = ?, Email = ?, Username = ?, Imagem = ? WHERE id = ?";
                $stmt = mysqli_prepare($conexao, $sql);
                mysqli_stmt_bind_param($stmt, "ssssi", $nome, $email, $username, $fileName, $id);
            
                if (mysqli_stmt_execute($stmt)) {
                    echo "<p class='success'>Dados atualizados com sucesso!</p>";
                    header("Refresh:2; url=administrador_management.php"); // Redireciona após 2 segundos
                                    } 
            
                mysqli_stmt_close($stmt);
            }
            
                
        
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Produtos</title>
    <link rel="stylesheet" href="../paginaInicial/PaginaInicial.css">
    <link rel="stylesheet" href="../gestor/gestormanagementstyle.css">
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
            window.location.href = 'administrador_management.php';
        }
    </script>
</head>
<body>
    <nav class="navbar">
        <div class="nav-left">
            <div id="logo">
                <img src="../logo/Logo.png" alt="Logo" style="width:10rem;">
            </div>
        </div>
        <h1>Centro de Gestores</h1>
        <div class="nav-right">
        <div class="icon dropdown">
                <span class="menu-toggle">
                <?php
                $sql = "SELECT Imagem FROM users WHERE Username = '{$_SESSION['username']}'";
                $result = mysqli_query($conexao, $sql);
                while($row = mysqli_fetch_array($result)) {
                $imageURL = '../imagens/'.$row["Imagem"];
                        ?>
                         <!-- Foto do usuário dentro de um círculo -->
                    <img class="user-photo" src="<?php echo $imageURL; }?>" alt="User Photo">
                    <span class="user-name"><?php echo $_SESSION['username']; ?></span>
                    ▼
                </span>
                <!-- Menu suspenso -->
                <div class="dropdown-content">
                    <a href="../logout.php">Logout</a>
                </div>
            </div>
            </div>
    </nav>
    <script>
            document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.menu-toggle');
        const dropdown = document.querySelector('.dropdown');

        menuToggle.addEventListener('click', function() {
            dropdown.classList.toggle('show');
        });

        // Fechar o menu se o utilizador clicar fora dele
        window.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && !menuToggle.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    });
        </script>

    <div class="container">
        <!-- MENU PRINCIPAL -->
        <div class="menu-container">
        <form method="GET"><button type="submit" name="acao" value="adicionar_stock" class="menu-item"><img src="../imagemoperacoes/Stock.jpeg"><span class="as">Adicionar Stock</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="eliminar_stock" class="menu-item"><img src="../imagemoperacoes/Stock.jpeg"><span class="as">Eliminar Stock</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="adicionar_tendencia" class="menu-item"><img src="../imagemoperacoes/Tendencia.jpeg"><span class="as">Adicionar Tendência</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="eliminar_tendencia" class="menu-item"><img src="../imagemoperacoes/Tendencia.jpeg"><span class="as">Eliminar Tendência</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="adicionar_tipo" class="menu-item"><img src="../imagemoperacoes/tipo.jpeg"><span class="as">Adicionar Tipo</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="eliminar_tipo" class="menu-item"><img src="../imagemoperacoes/tipo.jpeg"><span class="as">Eliminar Tipo</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="atualizar_tipo" class="menu-item"><img src="../imagemoperacoes/tipo.jpeg"><span class="as">Atualizar Tipo</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="nova_colecao" class="menu-item"><img src="../imagemoperacoes/Colecao.jpeg"><span class="as">Adicionar Coleção</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="eliminar_colecao" class="menu-item"><img src="../imagemoperacoes/Colecao.jpeg"><span class="as">Eliminar Coleção</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="atualizar_colecao" class="menu-item"><img src="../imagemoperacoes/Colecao.jpeg"><span class="as">Atualizar Coleção</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="novo_produto" class="menu-item"><img src="../imagemoperacoes/Produto.png"><span class="as">Adicionar Produto</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="eliminar_produto" class="menu-item"><img src="../imagemoperacoes/Produto.png"><span class="as">Eliminar Produto</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="atualizar_produto" class="menu-item"><img src="../imagemoperacoes/Produto.png"><span class="as">Atualizar Produto</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="novo_utilizador" class="menu-item"><img src="../imagemoperacoes/user.png"><span class="as">Adicionar Utilizador</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="eliminar_utilizador" class="menu-item"><img src="../imagemoperacoes/user.png"><span class="as">Eliminar Utilizador</span></button></form>
        <form method="GET"><button type="submit" name="acao" value="atualizar_utilizador" class="menu-item"><img src="../imagemoperacoes/user.png"><span class="as">Atualizar Utilizador</span></button></form>
    </div>
</div>

<?php if (isset($_GET['acao'])) { 
    $acao = $_GET['acao']; 
?>

<div class="section">
    <button class="close-btn" onclick="fecharFormulario()">❌</button>

    <?php if ($acao === 'adicionar_stock') { ?>
        <h3>Adicionar Stocks</h3>
    <form method="POST">
        <label for="produto">Produto:</label>
        <?php
            $query = "SELECT * FROM produtos";
            $result = mysqli_query($conexao, $query);
        ?>
        <select name="produto_id" id="produto_id" required>
            <option selected>Selecione uma opção</option>
            <?php
                // Listar todos os produtos com seu stock
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['id'] . '" data-stock="' . $row['Stock'] . '">' . $row['Nome'] . '</option>';
                }
            ?>
        </select>

        <label for="quantidade">Quantidade:</label>
        <input name="quantidade" id="quantidade" type="number" step="1" required value="" min="0">

        <button type="submit" name="adicionar_stock">Adicionar</button>
    </form>

    <!-- Exibir o estoque do produto selecionado em outro local -->
    <div id="estoque_info">
        <p><strong>Stock disponível:</strong> <span id="stock_display">0</span></p>
    </div>

    <script>
        // Função para atualizar o valor de quantidade com o stock do produto selecionado
        document.getElementById('produto_id').addEventListener('change', function() {
            // Obter o produto selecionado
            var produtoSelecionado = this.options[this.selectedIndex];
            var stock = produtoSelecionado.getAttribute('data-stock');

            // Atualizar o valor do campo de quantidade com o stock do produto
            var quantidadeInput = document.getElementById('quantidade');
            quantidadeInput.value = 0;  
            quantidadeInput.max = 100;

            // Atualizar o valor de stock exibido em outro local
            document.getElementById('stock_display').innerText = stock;  // Exibe o stock no lugar desejado
        });
    </script>
            <?php } elseif ($acao === 'eliminar_stock') { ?>
                <h3>Eliminar Stock</h3>
                <form method="POST">
        <label for="produto">Produto:</label>
        <?php
            $query = "SELECT * FROM produtos";
            $result = mysqli_query($conexao, $query);
        ?>
        <select name="produto_id" id="produto_id" required>
            <option selected>Selecione uma opção</option>
            <?php
                // Listar todos os produtos com seu stock
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['id'] . '" data-stock="' . $row['Stock'] . '">' . $row['Nome'] . '</option>';
                }
            ?>
        </select>

        <label for="quantidade">Quantidade:</label>
        <input name="quantidade" id="quantidade" type="number" step="1" required value="" min="0">

        <button type="submit" name="eliminar_stock">Eliminar</button>
    </form>

    <!-- Exibir o stock do produto selecionado em outro local -->
    <div id="estoque_info">
        <p><strong>Stock disponível:</strong> <span id="stock_display">0</span></p>
    </div>

    <script>
        // Função para atualizar o valor de quantidade com o stock do produto selecionado
        document.getElementById('produto_id').addEventListener('change', function() {
            // Obter o produto selecionado
            var produtoSelecionado = this.options[this.selectedIndex];
            var stock = produtoSelecionado.getAttribute('data-stock');

            // Atualizar o valor do campo de quantidade com o stock do produto
            var quantidadeInput = document.getElementById('quantidade');
            quantidadeInput.value = 0;  
            quantidadeInput.max = stock;

            // Atualizar o valor de stock exibido em outro local
            document.getElementById('stock_display').innerText = stock;  // Exibe o stock no lugar desejado
        });
    </script>
            <?php } elseif ($acao === 'adicionar_tipo') { ?>
                <h3>Adicionar Tipo</h3>
                <form method="POST">
                <label for="id">Tipo:</label>
                    <input name="tipo" type="text" name="id" required>
                <select name="tipo_tipo">
                <option selected>Selecione uma opção</option>
                <?php
                        $query = "SELECT * FROM tipo GROUP BY tipo;";
                        $result = mysqli_query($conexao, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option >' . $row['tipo'] . '</option>';
                        }
                        ?>
                    </select>
                    <button type="submit" name="adicionar_tipo">Adicionar</button>
                </form>

                <?php } elseif ($acao === 'eliminar_tipo') { ?>
                <h3>Eliminar Tipo</h3>
                <form method="POST">
                <label for="id">Tipo:</label>
                <select name="tipo">
                        <option selected>Selecione uma opção</option>
                        <?php
                        $query = "SELECT * FROM tipo";
                        $result = mysqli_query($conexao, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['id'] . '">' . $row['descricao'] . '</option>';
                        }
                        ?>
                    </select>
                    <button type="submit" name="eliminar_tipo">Eliminar</button>
                </form>

                <?php } elseif ($acao === 'novo_utilizador') { ?>
                <h3>Adicionar Utilizador</h3>
                <form method="POST" enctype="multipart/form-data"   >
                    <label>Nome:</label>
                    <input type="text" name="nome" required>
                    
                    <label>Email:</label>
                    <input type="email" name="email" required>
                    
                    <label>Username:</label>
                    <input type="text" name="username" required>
                    
                    <label>Password:</label>
                    <input type="password" name="password" required>

                    <label for="imagem">Imagem:</label>
                    <input type="file" accept="image/png, image/jpg, image/jpeg" name="imagem" required>
                    
                    <button type="submit" name="novo_utilizador">Criar Utilizador</button>
                </form>

                <?php } elseif ($acao === 'eliminar_utilizador') { ?>
                <h3>Eliminar Utilizador</h3>
                <form method="POST">
                <label for="id">Nome:</label>
                <select name="user_id">
                        <option selected>Selecione uma opção</option>
                        <?php
                        $query = "SELECT * FROM users where Nível = 1";
                        $result = mysqli_query($conexao, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['id'] . '">' . $row['Nome'] . '</option>';
                        }
                        ?>
                    </select>
                    <button type="submit" name="eliminar_utilizador">Eliminar</button>
                </form>

                <?php } elseif ($acao === 'atualizar_utilizador') { ?>
                    <h3>Atualizar Utilizador</h3>
                    <form method="POST">
                        <label for="user_id">Selecionar Utilizador:</label>
                        <select name="user_id" id="user_id" onchange="this.form.submit()">
                            <option selected>Selecione um utilizador</option>
                            <?php
                            $query = "SELECT id, Nome FROM users WHERE Nível = 1";
                            $result = mysqli_query($conexao, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $selected = (isset($_POST['user_id']) && $_POST['user_id'] == $row['id']) ? 'selected' : '';
                                echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['Nome'] . '</option>';
                            }
                            ?>
                        </select>
                    </form>

                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && !empty($_POST['user_id'])) {
                        $id = intval($_POST['user_id']);

                        // Busca os dados do utilizador
                        $query = "SELECT Nome, Email, Username, Imagem FROM users WHERE id = ?";
                        $stmt = mysqli_prepare($conexao, $query);
                        mysqli_stmt_bind_param($stmt, "i", $id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if ($row = mysqli_fetch_assoc($result)) {
                            $nome = $row['Nome'];
                            $email = $row['Email'];
                            $username = $row['Username'];
                            $imagem = $row['Imagem'];
                        }
                        mysqli_stmt_close($stmt);
                    }

                    if (!empty($nome)): ?>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="user_id" value="<?php echo $id; ?>">

                            <label for="nome">Nome:</label>
                            <input id="nome" name="nome" type="text" value="<?php echo htmlspecialchars($nome); ?>" required>

                            <label for="email">Email:</label>
                            <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($email); ?>" required>

                            <label for="username">Username:</label>
                            <input id="username" name="username" type="text" value="<?php echo htmlspecialchars($username); ?>" required>

                            <label for="imagem">Imagem de Perfil:</label>
                            <div>
                                <img src="imagens/<?php echo htmlspecialchars($imagem); ?>" alt="Imagem do Utilizador" width="100">
                                <br>
                                <small>Imagem atual</small>
                            </div>
                            <input type="file" id="imagem" name="imagem" accept="image/png, image/jpg, image/jpeg">

                            <button type="submit" name="atualizar_utilizador">Atualizar</button>
                        </form>
                    <?php endif; ?>


                <?php } elseif ($acao === 'atualizar_tipo') { ?>
    <h3>Atualizar Tipo</h3>

    <!-- Formulário para selecionar o tipo -->
    <form method="POST">
        <label for="id">Tipo:</label>
        <select name="tipo" onchange="this.form.submit()">
            <option selected>Selecione uma opção</option>
            <?php
            $query = "SELECT * FROM tipo";
            $result = mysqli_query($conexao, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $selected = (isset($_POST['tipo']) && $_POST['tipo'] == $row['id']) ? 'selected' : '';
                echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['descricao'] . '</option>';
            }
            ?>
        </select>
    </form>

    <?php
    $descricao = "";
    $tipo_tipo = "";
    $tipo_id = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tipo'])) {
        $tipo_id = intval($_POST['tipo']);

        // Buscar a descrição e o tipo do item selecionado
        $query = "SELECT descricao, tipo FROM tipo WHERE id = ?";
        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "i", $tipo_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $descricao = $row['descricao'];
            $tipo_tipo = $row['tipo'];
        }
        mysqli_stmt_close($stmt);
    }
    ?>

    <?php if (!empty($descricao)): ?>
        <form method="POST">
            <input type="hidden" name="tipo_id" value="<?php echo htmlspecialchars($tipo_id); ?>">

            <label for="descricao">Nova Descrição do Tipo:</label>
            <input id="descricao" name="descricao" type="text" value="<?php echo htmlspecialchars($descricao); ?>" required>

            <label for="tipo_tipo">Novo Nome do Tipo:</label>
            <select name="tipo_tipo">
                <option selected>Selecione uma opção</option>
                <?php
                $query = "SELECT DISTINCT tipo FROM tipo";
                $result = mysqli_query($conexao, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    $selected = ($row['tipo'] == $tipo_tipo) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($row['tipo']) . '" ' . $selected . '>' . htmlspecialchars($row['tipo']) . '</option>';
                }
                ?>
            </select>

            <button type="submit" name="atualizar_tipo">Atualizar</button>
        </form>
    <?php endif; ?>

                
            <?php } elseif ($acao === 'adicionar_tendencia') { ?>
                <h3>Adicionar Tendência</h3>
                <form method="POST">
                    <label for="produto_id">Produto:</label>
                    <select name="produto_id" id="produto_id" required>
                        <option selected>Selecione uma opção</option>
                        <?php
                        $query = "SELECT id, Nome FROM produtos"; 
                        $result = mysqli_query($conexao, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['id'] . '">' . $row['Nome'] . '</option>';
                        }
                        ?>
                    </select><br>

                    <button type="submit" name="adicionar_tendencia">Adicionar</button>
                </form>

                <?php } elseif ($acao === 'eliminar_tendencia') { ?>
                    <h3>Eliminar Tendência</h3>
                    <form method="POST">
                        <label for="produto">Produto:</label>
                        <select name="produto_id" id="produto_id">
                            <option selected>Selecione uma opção</option>
                            <?php
                            // Query para listar produtos que estão associados na tabela tendencias
                            $query = "SELECT p.id, p.Nome 
                                        FROM produtos p 
                                        INNER JOIN tendencia t ON t.id_produto = p.id"; 

      
                            $result = mysqli_query($conexao, $query);

                           
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['id'] . '">' . $row['Nome'] . '</option>'; 
                            } 
                            ?>
                        </select><br>
                        <button type="submit" name="eliminar_tendencia">Eliminar</button>
                    </form>

            <?php } elseif ($acao === 'nova_colecao') { ?>
                <h3>Adicionar Coleção</h3>
                <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <label for="nome_colecao">Coleção:</label>
                    <input type="text" name="nome_colecao" required>
                    <label for="imagem_colecao">Imagem:</label>
                    <input type="file" accept="image/png, image/jpg, image/jpeg" name="imagem_colecao" required>
                    <button type="submit" name="adicionar_colecao">Adicionar</button>
                </form>

                <?php } elseif ($acao === 'eliminar_colecao') { ?>
                <h3>Eliminar Coleção</h3>
                <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">                    
                <label for="nome_colecao_eliminar">Nome da Coleção:</label>
                    <select name="nome_colecao_eliminar">
                    <option selected>Selecione uma opção</option>
                                <?php
                                $query = "SELECT * FROM colecoes";
                                $result = mysqli_query($conexao, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                            
                                    echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['descricao'] . '</option>';
                                }
                                ?>
                    </select>
                    <button type="submit" name="eliminar_colecao">Eliminar</button>
                </form>
                                
                <?php } elseif ($acao === 'atualizar_colecao') { ?>
                    <h3>Atualizar Coleção</h3>
                    <form method="POST" enctype="multipart/form-data">
                        <label for="id">Nome Coleção:</label>
                        <select name="colecao" onchange="this.form.submit()">
                            <option selected>Selecione uma opção</option>
                            <?php
                            $query = "SELECT * FROM colecoes";
                            $result = mysqli_query($conexao, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $selected = (isset($_POST['colecao']) && $_POST['colecao'] == $row['id']) ? 'selected' : '';
                                echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['descricao'] . '</option>';
                            }
                            ?>
                        </select>
                    </form>

    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['colecao']) && !empty($_POST['colecao'])) {
                        $id = intval($_POST['colecao']); // Garante que é um número inteiro

                        // Busca os dados da coleção
                        $query = "SELECT descricao, imagem FROM colecoes WHERE id = $id";
                        $result = mysqli_query($conexao, $query);

                        if ($row = mysqli_fetch_assoc($result)) {
                            $descricao = $row['descricao'];
                            $imagem = $row['imagem'];
                        }
                    }

                    if (!empty($descricao)): ?>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="colecao_id" value="<?php echo $id; ?>">

                            <label for="nome_colecao_atualizar">Nome Coleção:</label>
                            <input id="descricao" name="nome_colecao_atualizar" type="text" value="<?php echo htmlspecialchars($descricao); ?>" required>

                            <label for="imagem">Imagem Coleção:</label>
                            <div>
                                <img src="colecoes/<?php echo htmlspecialchars($imagem); ?>" alt="Imagem da Coleção" width="100"> 
                                <br>
                                <small>Imagem atual</small>
                            </div>
                            <input type="file" id="imagem" name="imagem">

                            <button type="submit" name="atualizar_colecao">Atualizar</button>
                        </form>
                    <?php endif; ?>


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
        <select name="tipo">
            <option selected>Selecione uma opção</option>
            <?php
            $query = "SELECT * FROM tipo";
            $result = mysqli_query($conexao, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['id'] . '">' . $row['descricao'] . '</option>';
            }
            ?>
        </select>

        <label for="genero">Género:</label>
        <select name="genero">
            <option value="">Selecione uma opção</option>
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
            <option value="Unisexo">Unisexo</option>
        </select>

        <label for="colecao">Coleção:</label>
        <select name="colecao">
            <option selected>Selecione uma opção</option>
            <?php
            $query = "SELECT * FROM colecoes";
            $result = mysqli_query($conexao, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['id'] . '">' . $row['descricao'] . '</option>';
            }
            ?>
        </select>

        <label for="tamanho">Tamanho:</label>
        <select name="tamanho">
            <option selected>Selecione uma opção</option>
            <?php
            $query = "SELECT * FROM tamanho";
            $result = mysqli_query($conexao, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['id'] . '">' . $row['descricao'] . '</option>';
            }
            ?>
        </select>

        <label for="imagem">Imagem:</label>
        <input type="file" name="imagem" required>

        <button type="submit" name="adicionar_produto">Adicionar</button>
    </form>

            <?php } elseif ($acao === 'eliminar_produto') { ?>
                <h3>Eliminar Produto</h3>
                <form method="POST">
                    <label for="id">Produto:</label>
                    <select name="id">
                    <option selected>Selecione uma opção</option>
                                <?php
                                $query = "SELECT * FROM produtos";
                                $result = mysqli_query($conexao, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                            
                                    echo '<option value="' . $row['id'] . '">' . $row['Nome'] . '</option>';

                                }
                                ?>
                    </select>
                    <button type="submit" name="eliminar_produto">Eliminar</button>
                </form>


                <?php } elseif ($acao === 'atualizar_produto') { ?>
                    <h3>Atualizar Produto</h3>
                    <form method="POST">
                        <label for="id">Produto:</label>
                        <select name="colecao" onchange="this.form.submit()">
                            <option selected>Selecione uma opção</option>
                            <?php
                            $query = "SELECT * FROM produtos";
                            $result = mysqli_query($conexao, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Se foi selecionado, manter a seleção
                                $selected = (isset($_POST['colecao']) && $_POST['colecao'] == $row['id']) ? 'selected' : '';
                                echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['Nome'] . '</option>';
                            }
                            ?>
                        </select>
                    </form>

                    <?php
                    // Inicializa variáveis vazias
                    $descricao = "";
                    $preco = "";
                    $tipo_id = "";
                    $imagem = "";
                    $stock = "";
                    $colecao = "";
                    $genero = "";
                    $tamanho ="";

                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['colecao']) && !empty($_POST['colecao'])) {
                        $id = intval($_POST['colecao']); // Garante que é um número inteiro

                        // Busca os dados do produto
                        $query = "SELECT Nome, Preco, tipo_id, Imagem, Stock, Colecao, Genero,tamanho FROM produtos WHERE id = $id";
                        $result = mysqli_query($conexao, $query);

                        if ($row = mysqli_fetch_assoc($result)) {
                            $descricao = $row['Nome']; // Nome do produto
                            $preco = $row['Preco'];
                            $tipo_id = $row['tipo_id'];
                            $imagem = $row['Imagem'];
                            $stock = $row['Stock'];
                            $colecao = $row['Colecao'];
                            $genero = $row['Genero'];
                            $tamanho =$row['tamanho'];
                        }
                    }

                    if (!empty($descricao)): ?>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="produto_id" value="<?php echo $id; ?>">

                            <label for="nome_produto_atualizar">Nome do Produto:</label>
                            <input id="descricao" name="nome_produto_atualizar" type="text" value="<?php echo htmlspecialchars($descricao); ?>" required>

                            <label for="preco">Preço:</label>
                            <input id="preco" name="preco" type="number" step="0.01" value="<?php echo htmlspecialchars($preco); ?>" required>

                            <label for="tipo_id">Tipo:</label>
                            <select id="tipo_id" name="tipo_id">
                                <?php
                                $query = "SELECT id, descricao FROM tipo";
                                $result = mysqli_query($conexao, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $selected = ($tipo_id == $row['id']) ? 'selected' : '';
                                    echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['descricao'] . '</option>';
                                }
                                ?>
                            </select>

                            <label for="stock">Stock:</label>
                            <input id="stock" name="stock" type="number" value="<?php echo htmlspecialchars($stock); ?>" required>

                            <label for="colecao">Coleção:</label>
                            <select id="colecao" name="colecao">
                                <option value="">Nenhuma</option>
                                <?php
                                $query = "SELECT id, descricao FROM colecoes";
                                $result = mysqli_query($conexao, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $selected = ($colecao == $row['id']) ? 'selected' : '';
                                    echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['descricao'] . '</option>';
                                }
                                ?>
                            </select>

                            <label for="genero">Gênero:</label>
                            <input id="genero" name="genero" type="text" value="<?php echo htmlspecialchars($genero); ?>" required>

                            <label for="tamanho">Tipo:</label>
                            <select id="tamanho" name="tamanho">
                                <?php
                                $query = "SELECT id, descricao FROM tamanho";
                                $result = mysqli_query($conexao, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $selected = ($tamanho == $row['id']) ? 'selected' : '';
                                    echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['descricao'] . '</option>';
                                }
                                ?>
                            </select>

                            <label for="imagem">Imagem Atual:</label>
                            <div>
                                <?php if ($imagem): ?>
                                    <img src="produtos/<?php echo htmlspecialchars($imagem); ?>" alt="Imagem do Produto" width="100">
                                    <br>
                                    <small>Imagem atual</small>
                                <?php else: ?>
                                    <small>Sem imagem</small>
                                <?php endif; ?>
                            </div>
                            <input type="file" id="imagem" name="imagem">

                            <button type="submit" name="atualizar_produto">Atualizar</button>
                        </form>
                    <?php endif; ?>
                <?php } ?>

    <?php } ?>
</body>
</html>

