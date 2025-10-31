<?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'kanban');
if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die('ID da tarefa não informado.');
}
$id_tarefa = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirmar']) && $_POST['confirmar'] === 'Sim') {
        $stmt = $conn->prepare('DELETE FROM tarefa WHERE id_tarefa=?');
        $stmt->bind_param('i', $id_tarefa);
        if ($stmt->execute()) {
            echo '<p>Tarefa excluída com sucesso!</p>';
            echo '<a href="visualizar.php">Voltar ao Kanban</a>';
            exit;
        } else {
            echo '<p>Erro ao excluir tarefa: ' . $stmt->error . '</p>';
        }
        $stmt->close();
    } else {
        header('Location: visualizar.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Tarefa</title>
</head>
<body>
    <h1>Excluir Tarefa</h1>
    <p>Tem certeza que deseja excluir esta tarefa?</p>
    <form method="post">
        <button type="submit" name="confirmar" value="Sim">Sim</button>
        <button type="submit" name="confirmar" value="Não">Não</button>
    </form>
    <a href="visualizar.php">Voltar ao Kanban</a>
</body>
</html>
