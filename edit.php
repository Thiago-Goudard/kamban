<?php

$conn = new mysqli('localhost', 'root', '', 'kamban');
if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}


$usuarios = $conn->query('SELECT id_usuario, nome FROM usuario');


if (!isset($_GET['id'])) {
    die('ID da tarefa não informado.');
}
$id_tarefa = (int)$_GET['id'];
$tarefa = $conn->query("SELECT * FROM tarefa WHERE id_tarefa = $id_tarefa")->fetch_assoc();
if (!$tarefa) {
    die('Tarefa não encontrada.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'];
    $setor = $_POST['setor'];
    $prioridade = $_POST['prioridade'];
    $id_usuario = $_POST['id_usuario'];
    $status = $_POST['status'];

    $stmt = $conn->prepare('UPDATE tarefa SET id_usuario=?, descricao=?, setor=?, prioridade=?, status=? WHERE id_tarefa=?');
    $stmt->bind_param('issssi', $id_usuario, $descricao, $setor, $prioridade, $status, $id_tarefa);
    if ($stmt->execute()) {
        echo '<p>Tarefa atualizada com sucesso!</p>';
        echo '<a href="visualizar.php">Voltar ao Kanban</a>';
        exit;
    } else {
        echo '<p>Erro ao atualizar tarefa: ' . $stmt->error . '</p>';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
</head>
<body>
    <h1>Editar Tarefa</h1>
    <form method="post">
        <label>Descrição:<br>
            <textarea name="descricao" required><?= htmlspecialchars($tarefa['descricao']) ?></textarea>
        </label><br><br>
        <label>Setor:<br>
            <input type="text" name="setor" value="<?= htmlspecialchars($tarefa['setor']) ?>" required>
        </label><br><br>
        <label>Prioridade:<br>
            <select name="prioridade" required>
                <option value="Baixa" <?= $tarefa['prioridade']=='Baixa'?'selected':'' ?>>Baixa</option>
                <option value="Média" <?= $tarefa['prioridade']=='Média'?'selected':'' ?>>Média</option>
                <option value="Alta" <?= $tarefa['prioridade']=='Alta'?'selected':'' ?>>Alta</option>
            </select>
        </label><br><br>
        <label>Usuário responsável:<br>
            <select name="id_usuario" required>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id_usuario'] ?>" <?= $u['id_usuario']==$tarefa['id_usuario']?'selected':'' ?>><?= htmlspecialchars($u['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br><br>
        <label>Status:<br>
            <select name="status" required>
                <option value="A Fazer" <?= $tarefa['status']=='A Fazer'?'selected':'' ?>>A Fazer</option>
                <option value="Fazendo" <?= $tarefa['status']=='Fazendo'?'selected':'' ?>>Fazendo</option>
                <option value="Pronto" <?= $tarefa['status']=='Pronto'?'selected':'' ?>>Pronto</option>
            </select>
        </label><br><br>
        <button type="submit">Salvar</button>
    </form>
    <a href="visualizar.php">Voltar para o Kanban</a>
</body>
</html>
