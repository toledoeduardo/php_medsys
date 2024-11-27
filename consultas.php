<?php
require 'config.php'; // Inclui a conexão com o banco de dados

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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
