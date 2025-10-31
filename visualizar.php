<?php

$conn = new mysqli('localhost', 'root', 'root', 'kanban');
if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}

$tarefas = [
    'A Fazer' => [],
    'Fazendo' => [],
    'Pronto' => []
];
$sql = "SELECT t.*, u.nome as usuario_nome FROM tarefa t JOIN usuario u ON t.id_usuario = u.id_usuario ORDER BY t.prioridade DESC, t.data_cadastro DESC";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $tarefas[$row['status']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Kanban - Visualizar Tarefas</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .kanban { display: flex; gap: 20px; }
        .col { background: #f4f4f4; padding: 10px; border-radius: 8px; width: 30%; }
        .col h2 { text-align: center; }
        .tarefa { background: #fff; margin-bottom: 10px; padding: 10px; border-radius: 6px; box-shadow: 0 1px 3px #ccc; }
        .prioridade-Alta { color: #c00; font-weight: bold; }
        .prioridade-Média { color: #e6a100; font-weight: bold; }
        .prioridade-Baixa { color: #007700; font-weight: bold; }
        .acoes { margin-top: 8px; }
        .acoes a { margin-right: 8px; }
    </style>
</head>
<body>
    <h1>Quadro Kanban</h1>
    <a href="create.php">Cadastrar nova tarefa</a>
    <div class="kanban">
        <?php foreach ($tarefas as $status => $lista): ?>
            <div class="col">
                <h2><?= htmlspecialchars($status) ?></h2>
                <?php if (count($lista) === 0): ?>
                    <p>Nenhuma tarefa.</p>
                <?php else: ?>
                    <?php foreach ($lista as $t): ?>
                        <div class="tarefa">
                            <div><strong><?= htmlspecialchars($t['descricao']) ?></strong></div>
                            <div>Setor: <?= htmlspecialchars($t['setor']) ?></div>
                            <div>Responsável: <?= htmlspecialchars($t['usuario_nome']) ?></div>
                            <div class="prioridade-<?= htmlspecialchars($t['prioridade']) ?>">Prioridade: <?= htmlspecialchars($t['prioridade']) ?></div>
                            <div>Data: <?= htmlspecialchars($t['data_cadastro']) ?></div>
                            <div class="acoes">
                                <a href="edit.php?id=<?= $t['id_tarefa'] ?>">Editar</a>
                                <a href="delete.php?id=<?= $t['id_tarefa'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                                <a href="atualizar_tarefa.php?id=<?= $t['id_tarefa'] ?>">Atualizar Status</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
