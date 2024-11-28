<?php
require 'config.php';

session_start();

// Verifica permissões de acesso
if ($_SESSION['role'] !== 'medico' && $_SESSION['role'] !== 'administrador') {
    header("Location: menu.php");
    exit();
}

// Processa exclusão de prontuários
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmtDelete = $pdo->prepare("DELETE FROM prontuarios WHERE id_prontuario = :id_prontuario");
    $stmtDelete->execute(['id_prontuario' => $delete_id]);
    echo "<p class='success'>Prontuário excluído com sucesso!</p>";
}

// Processa criação de prontuários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_consulta = isset($_POST['id_consulta']) ? $_POST['id_consulta'] : null;
    $evolucao = isset($_POST['evolucao']) ? $_POST['evolucao'] : '';

    if ($id_consulta && $evolucao) {
        $stmtConsulta = $pdo->prepare("SELECT id_paciente, data_hora FROM consultas WHERE id_consulta = :id_consulta");
        $stmtConsulta->execute(['id_consulta' => $id_consulta]);
        $consulta = $stmtConsulta->fetch();

        if ($consulta) {
            $id_paciente = $consulta['id_paciente'];
            $data_hora = $consulta['data_hora'];

            $stmtProntuario = $pdo->prepare("INSERT INTO prontuarios (id_paciente, data_hora, evolucao) VALUES (:id_paciente, :data_hora, :evolucao)");
            $stmtProntuario->execute([
                'id_paciente' => $id_paciente,
                'data_hora' => $data_hora,
                'evolucao' => $evolucao,
            ]);

            echo "<p class='success'>Prontuário criado com sucesso!</p>";
        } else {
            echo "<p class='error'>Consulta não encontrada!</p>";
        }
    } else {
        echo "<p class='error'>Por favor, preencha todos os campos obrigatórios!</p>";
    }
}

// Busca consultas disponíveis
$stmtConsultas = $pdo->query("
    SELECT c.id_consulta, p.nome AS paciente, c.data_hora
    FROM consultas c
    JOIN pacientes p ON c.id_paciente = p.id_paciente
    ORDER BY c.data_hora ASC
");
$consultas = $stmtConsultas->fetchAll();

// Processa busca de prontuários
$search = isset($_GET['search']) ? $_GET['search'] : '';
$prontuarios = [];
if (!empty($search)) {
    $stmtProntuarios = $pdo->prepare("
        SELECT pr.id_prontuario, p.nome AS paciente, pr.data_hora, pr.evolucao
        FROM prontuarios pr
        JOIN pacientes p ON pr.id_paciente = p.id_paciente
        WHERE pr.id_prontuario = :id_prontuario OR p.nome LIKE :nome
        ORDER BY pr.data_hora DESC
    ");
    $stmtProntuarios->execute([
        'id_prontuario' => is_numeric($search) ? intval($search) : null,
        'nome' => '%' . $search . '%'
    ]);
    $prontuarios = $stmtProntuarios->fetchAll();
} else {
    $stmtProntuarios = $pdo->query("
        SELECT pr.id_prontuario, p.nome AS paciente, pr.data_hora, pr.evolucao
        FROM prontuarios pr
        JOIN pacientes p ON pr.id_paciente = p.id_paciente
        ORDER BY pr.data_hora DESC
    ");
    $prontuarios = $stmtProntuarios->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Gestão de Prontuários</title>
</head>
<body>
    <div class="container">
        <h1>Gestão de Prontuários</h1>

        <a href="menu.php" class="btn btn-back">Voltar</a>

        <h2>Criar Prontuário</h2>
        <?php if (empty($consultas)): ?>
            <p class="error">Não há consultas disponíveis para criar prontuários.</p>
        <?php else: ?>
            <form method="POST" class="form">
                <div class="form-group">
                    <label for="id_consulta">Selecione a Consulta:</label>
                    <select name="id_consulta" id="id_consulta" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($consultas as $consulta): ?>
                            <option value="<?= $consulta['id_consulta'] ?>">
                                <?= htmlspecialchars($consulta['paciente']) ?> - <?= $consulta['data_hora'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="evolucao">Evolução:</label>
                    <textarea name="evolucao" id="evolucao" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-add">Criar Prontuário</button>
            </form>
        <?php endif; ?>

        <h2>Prontuários Existentes</h2>
        <!-- Campo de busca -->
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Buscar por ID ou Nome do Paciente" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-search">Buscar</button>
        </form>

        <?php if (empty($prontuarios)): ?>
            <p class="error">Nenhum prontuário encontrado.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Data/Hora</th>
                        <th>Evolução</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prontuarios as $prontuario): ?>
                        <tr>
                            <td><?= $prontuario['id_prontuario'] ?></td>
                            <td><?= htmlspecialchars($prontuario['paciente']) ?></td>
                            <td><?= $prontuario['data_hora'] ?></td>
                            <td><?= htmlspecialchars($prontuario['evolucao']) ?></td>
                            <td>
                                <a href="?delete_id=<?= $prontuario['id_prontuario'] ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este prontuário?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
