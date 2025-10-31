<?php

$conn = new mysqli('localhost', 'root', '', 'kanban');
if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $stmt = $conn->prepare('INSERT INTO usuario (nome, email) VALUES (?, ?)');
    $stmt->bind_param('ss', $nome, $email);
    if ($stmt->execute()) {
        echo '<p>Usuário cadastrado com sucesso!</p>';
        echo '<a href="create.php">Cadastrar tarefa</a> | <a href="visualizar.php">Ver Kanban</a>';
        exit;
    } else {
        echo '<p>Erro ao cadastrar usuário: ' . $stmt->error . '</p>';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
</head>
<body>
    <h1>Cadastrar Usuário</h1>
    <form method="post">
        <label>Nome:<br>
            <input type="text" name="nome" required>
        </label><br><br>
        <label>Email:<br>
            <input type="email" name="email" required>
        </label><br><br>
        <button type="submit">Cadastrar</button>
    </form>
    <a href="visualizar.php">Voltar para o Kanban</a>
</body>
</html>
