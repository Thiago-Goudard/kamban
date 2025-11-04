<?php

$conn = new mysqli('localhost', 'root', 'root', 'kanban');
if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}


$usuarios = $conn->query('SELECT id_usuario, nome FROM usuario');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'];
    $setor = $_POST['setor'];
    $prioridade = $_POST['prioridade'];
    $id_usuario = $_POST['id_usuario'];
    $data_cadastro = date('Y-m-d');
    $status = 'A Fazer';

    $stmt = $conn->prepare('INSERT INTO tarefa (id_usuario, descricao, setor, prioridade, data_cadastro, status) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('isssss', $id_usuario, $descricao, $setor, $prioridade, $data_cadastro, $status);
    if ($stmt->execute()) {
        echo '<p>Tarefa cadastrada com sucesso!</p>';
    } else {
        echo '<p>Erro ao cadastrar tarefa: ' . $stmt->error . '</p>';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Tarefa</title>
</head>
<body>
    <h1>Cadastrar Tarefa</h1>
    <form method="post" id="form-tarefa">
        <label>Descrição:<br>
            <textarea name="descricao" required></textarea>
        </label><br><br>
        <label>Setor:<br>
            <input type="text" name="setor" required>
        </label><br><br>
        <label>Prioridade:<br>
            <select name="prioridade" required>
                <option value="Baixa">Baixa</option>
                <option value="Média">Média</option>
                <option value="Alta">Alta</option>
            </select>
        </label><br><br>
        <label>Usuário responsável:<br>
            <select name="id_usuario" required>
                <?php while($u = $usuarios->fetch_assoc()): ?>
                    <option value="<?= $u['id_usuario'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
                <?php endwhile; ?>
            </select>
        </label><br><br>
        <fieldset style="border:1px solid #ccc;padding:10px;max-width:350px;">
            <legend>Verificar localização e horário local (opcional)</legend>
            <label>Latitude:<br>
                <input type="text" id="lat" placeholder="Ex: -25.5026">
            </label><br>
            <label>Longitude:<br>
                <input type="text" id="lng" placeholder="Ex: -49.2908">
            </label><br>
            <button type="button" onclick="buscarGeo()">Ver informações</button>
            <div id="resultadoGeo" style="margin-top:8px;color:blue;"></div>
        </fieldset><br>
        <button type="submit">Cadastrar</button>
    </form>
    <a href="visualizar.php">Voltar para o Kanban</a>
    <script>
    function buscarGeo() {
        const lat = document.getElementById('lat').value.trim();
        const lng = document.getElementById('lng').value.trim();
        const resDiv = document.getElementById('resultadoGeo');
        if (!lat || !lng) {
            resDiv.textContent = 'Informe latitude e longitude.';
            return;
        }
        resDiv.textContent = 'Consultando...';
        // Usando ip-api.com para latitude/longitude
        fetch(`http://ip-api.com/json/?lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lng)}`)
            .then(r => r.json())
            .then(d => {
                if (d.status !== 'success') {
                    resDiv.textContent = 'Erro: ' + (d.message || 'Não foi possível obter dados.');
                    return;
                }
                let info = '';
                if (d.city) info += `Cidade: ${d.city}<br>`;
                if (d.regionName) info += `Estado: ${d.regionName}<br>`;
                if (d.country) info += `País: ${d.country}<br>`;
                if (d.timezone) {
                    // Calcular horário local
                    try {
                        const now = new Date();
                        const localTime = now.toLocaleString('pt-BR', {timeZone: d.timezone});
                        info += `Horário local: ${localTime}<br>`;
                        info += `Fuso horário: ${d.timezone}<br>`;
                    } catch(e) {
                        info += `Fuso horário: ${d.timezone}<br>`;
                    }
                }
                if (!info) info = 'Nenhuma informação encontrada.';
                resDiv.innerHTML = info;
            })
            .catch(() => {
                resDiv.textContent = 'Erro ao consultar localização.';
            });
    }
    </script>
</body>
</html>
