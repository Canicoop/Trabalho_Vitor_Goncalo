<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Definir nível de usuário
    if ($username === 'gestor') {
        $nivel = 2;
    } elseif ($username === 'administrador') {
        $nivel = 3;
    } else {
        $nivel = 1;
    }

    // Proteção contra SQL Injection
    $nome = mysqli_real_escape_string($conexao, $nome);
    $email = mysqli_real_escape_string($conexao, $email);
    $username = mysqli_real_escape_string($conexao, $username);
    $password = mysqli_real_escape_string($conexao, $password);

    // Hash da senha para segurança
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Verificar se o usuário já existe
    $sql = "SELECT * FROM users WHERE Username = '$username' OR Email = '$email'";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        $error = "Usuário ou email já existe!";
    } else {
        // Inserir novo usuário
        $sql = "INSERT INTO users (Nome, Email, Username, Password, Nível) VALUES ('$nome', '$email', '$username', '$hashed_password', '$nivel')";
        if (mysqli_query($conexao, $sql)) {
            header("Location: PaginaInicial.php");
        } else {
            $error = "Erro ao criar conta: " . mysqli_error($conexao);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="criarcontastyle.css">
    <title>Criar Conta - Loja</title>
</head>
<body>
    <div class="register-container">
        <div class="register-form">
            <h2>Criar Conta</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <form action="" method="POST">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Criar Conta</button>
            </form>
        </div>
    </div>
</body>
</html>
