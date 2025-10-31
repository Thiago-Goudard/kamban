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
$tarefa = $conn->query("SELECT * FROM tarefa WHERE id_tarefa = $id_tarefa")->fetch_assoc();
if (!$tarefa) {
    die('Tarefa não encontrada.');
}

// Próximo status
$status_atual = $tarefa['status'];
$proximos = [
    'A Fazer' => 'Fazendo',
    'Fazendo' => 'Pronto',
    'Pronto' => 'A Fazer'
];
$novo_status = $proximos[$status_atual];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare('UPDATE tarefa SET status=? WHERE id_tarefa=?');
    $stmt->bind_param('si', $novo_status, $id_tarefa);
    if ($stmt->execute()) {
        header('Location: visualizar.php');
        exit;
    } else {
        echo '<p>Erro ao atualizar status: ' . $stmt->error . '</p>';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Status da Tarefa</title>
</head>
<body>
    <h1>Atualizar Status da Tarefa</h1>
    <p>Status atual: <strong><?= htmlspecialchars($status_atual) ?></strong></p>
    <p>Novo status: <strong><?= htmlspecialchars($novo_status) ?></strong></p>
    <form method="post">
        <button type="submit">Confirmar mudança</button>
    </form>
    <a href="visualizar.php">Voltar para o Kanban</a>
</body>
</html>
