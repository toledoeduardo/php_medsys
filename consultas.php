<?php
require 'config.php'; // Inclui a conexão com o banco de dados

// Processa exclusão de consulta
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $stmtDelete = $pdo->prepare("DELETE FROM consultas WHERE id_consulta = :id_consulta");
        $stmtDelete->execute(['id_consulta' => $delete_id]);
        echo "<p class='success'>Consulta excluída com sucesso!</p>";
    } catch (Exception $e) {
        echo "<p class='error'>Erro ao excluir consulta: " . $e->getMessage() . "</p>";
    }
}

// Processa alteração da data/hora de consulta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_consulta']) && isset($_POST['nova_data'])) {
    $id_consulta = $_POST['id_consulta'];
    $nova_data = $_POST['nova_data'];
    try {
        $stmtUpdate = $pdo->prepare("UPDATE consultas SET data_hora = :nova_data WHERE id_consulta = :id_consulta");
        $stmtUpdate->execute([
            'nova_data' => $nova_data,
            'id_consulta' => $id_consulta
        ]);
        echo "<p class='success'>Data/hora da consulta alterada com sucesso!</p>";
    } catch (Exception $e) {
        echo "<p class='error'>Erro ao alterar a data/hora da consulta: " . $e->getMessage() . "</p>";
    }
}

// Consulta para buscar todas as consultas
try {
    $stmt = $pdo->query("SELECT c.id_consulta, p.nome AS paciente, f.nome AS medico, c.data_hora, c.status
                         FROM consultas c
                         JOIN pacientes p ON c.id_paciente = p.id_paciente
                         JOIN funcionarios f ON c.id_funcionario = f.id_funcionario
                         ORDER BY c.data_hora ASC");
    $consultas = $stmt->fetchAll();
} catch (Exception $e) {
    die("Erro ao buscar consultas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Consultas</title>
    <style>
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .btn-back:hover {
            background: #c0392b;
        }
        .btn {
            display: inline-block;
            margin: 5px;
            padding: 10px 15px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #2980b9;
        }
        .btn-delete {
            background: #e74c3c;
        }
        .btn-delete:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Consultas</h1>

        <!-- Botão de Voltar -->
        <a class="btn-back" href="menu.php">Voltar</a>

        <!-- Botão para agendar consulta -->
        <div class="actions">
            <a class="btn btn-add" href="agendamento_consultas.php">Agendar Consulta</a>
        </div>

        <!-- Tabela de Consultas -->
        <?php if (empty($consultas)): ?>
            <p class="error">Nenhuma consulta agendada no sistema.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Data/Hora</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($consultas as $consulta): ?>
                        <tr>
                            <td><?= $consulta['id_consulta'] ?></td>
                            <td><?= htmlspecialchars($consulta['paciente']) ?></td>
                            <td><?= htmlspecialchars($consulta['medico']) ?></td>
                            <td><?= $consulta['data_hora'] ?></td>
                            <td><?= $consulta['status'] ?></td>
                            <td>
                                <!-- Botão para excluir a consulta -->
                                <a href="?delete_id=<?= $consulta['id_consulta'] ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir esta consulta?')">Excluir</a>

                                <!-- Botão para alterar data/hora -->
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id_consulta" value="<?= $consulta['id_consulta'] ?>">
                                    <input type="datetime-local" name="nova_data" required>
                                    <button type="submit" class="btn">Alterar Data</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
