<?php
include '../conexao.php';
include '../session_check.php';


$user_id = $_SESSION['user_id'];

// Busca os dados atuais do usuário
$query = "SELECT Nome, Email, Username FROM users WHERE id = ?";
$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome = $_POST['nome'];
    $novo_email = $_POST['email'];
    $novo_username = $_POST['username'];

    $query_update = "UPDATE users SET Nome = ?, Email = ?, Username = ? WHERE id = ?";
    $stmt_update = $conexao->prepare($query_update);
    $stmt_update->bind_param("sssi", $novo_nome, $novo_email, $novo_username, $user_id);

    if ($stmt_update->execute()) {
        echo "<p class='success'>Dados atualizados com sucesso!</p>";
        header("Refresh:2; url=minha_conta.php"); // Redireciona após 2 segundos
    } else {
        echo "<p class='error'>Erro ao atualizar os dados: " . $stmt_update->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Perfil</title>
    <link rel="stylesheet" href="minha_conta.css">
    <link rel="stylesheet" href="../paginaInicial/PaginaInicial.css">
</head>
<body>
    <?php include '../navbar.php'; ?>
    <br><br><br>
    <div class="container">
        <h2>Atualizar Perfil</h2>
        <form action="" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user['Nome']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['Username']); ?>" required>

            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
    <br><br><br>
    <?php include '../footer.php'; ?>
</body>
</html>