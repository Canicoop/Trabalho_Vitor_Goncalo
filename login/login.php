<?php
include '../conexao.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['rememberMe']);

    // Proteção contra SQL Injection
    $username = mysqli_real_escape_string($conexao, $username);
    $password = mysqli_real_escape_string($conexao, $password);

    // Consulta SQL
    $sql = "SELECT * FROM users WHERE Username = '$username'";
    $result = mysqli_query($conexao, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['Password'])) {
        // Utilizador encontrado e senha verificada, iniciar sessão
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['Username'];
        $_SESSION['nivel'] = $row['Nível'];

        // Verificar se 'Lembrar-me' foi marcado
        if ($rememberMe) {
            setcookie('username', $username, time() + (86400 * 30), "/"); // 30 dias
            setcookie('password', $password, time() + (86400 * 30), "/"); // 30 dias
        }

        // Redirecionar com base no nível do usuário
        if ($row['Nível'] == 1) {
            header("Location: ../paginaInicial/PaginaInicial.php");
        } elseif ($row['Nível'] == 2) {
            header("Location: ../gestor/gestor_management.php");
        } elseif ($row['Nível'] == 3) {
            header("Location: ../admin/administrador_management.php");
        }
        exit();
    } else {
        $error = "Utilizador ou senha inválidos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginstyle.css">
    <title>Login - Loja</title>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <img src="Logo.png" alt="" style="width:10rem;">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <div class="checkbox-container">
                    <input type="checkbox" id="rememberMe" name="rememberMe">
                    <label for="rememberMe">Lembrar-me</label>
                </div>

                <button type="submit">Entrar</button>
                <a href="../criarconta/create_account.php" class="create-account">Criar Conta</a>
            </form>
        </div>
    </div>
</body>
</html>
