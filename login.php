<?php
session_start();

$conn = new mysqli('localhost', 'root', 'root', 'kamban');
if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $nome = $_POST['nome'];
   
    $stmt->bind_param('ss', $nome, $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 1) {
        $usuario = $res->fetch_assoc();
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header('Location: visualizar.php');
        exit;
    } else {
        $erro = 'Usuário não encontrado. Verifique nome e e-mail.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if ($erro): ?>
        <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Nome:<br>
            <input type="text" name="nome" required>
        </label><br><br>
        <label>Email:<br>
            <input type="email" name="email" required>
        </label><br><br>
        <button type="submit">Entrar</button>
    </form>
    <a href="cadastrar_usuario.php">Cadastrar novo usuário</a>
</body>
</html>
