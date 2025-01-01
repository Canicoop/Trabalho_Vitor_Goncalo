<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $targetDir = "imagens/";
$fileName = basename($_FILES["imagem"]["name"]);
$targetFilePath = $targetDir . $fileName;




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
    $fileName = mysqli_real_escape_string($conexao, $fileName);

    // Hash da senha para segurança
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if(move_uploaded_file($_FILES["imagem"]["tmp_name"], $targetFilePath)){

    // Verificar se o usuário já existe
    $sql = "SELECT * FROM users WHERE Username = ? OR Email = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $error = "Utilizador ou email já existe!";
    } else {
        // Inserir novo usuário
        $sql = "INSERT INTO users (Nome, Email, Username, Password, Nível, Imagem) VALUES ('$nome', '$email', '$username', '$hashed_password', '$nivel', '$fileName')";
        $stmt = mysqli_prepare($conexao, $sql);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: PaginaInicial.php");
        } else {
            $error = "Erro ao criar conta: " . mysqli_error($conexao);
        }
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
            <form enctype="multipart/form-data" action="" method="POST">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <div class="form-group">
                    <label for="imagem" class="custom-file-upload">
                        Selecione uma imagem
                        <input type="file" id="imagem" name="imagem" accept="image/png, image/jpg, image/jpeg" required>
                    </label>
                </div>
                <button type="submit">Criar Conta</button>
            </form>
        </div>
    </div>
</body>
</html>
