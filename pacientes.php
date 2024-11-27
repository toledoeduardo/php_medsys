<?php
require 'config.php'; // Inclui a conexão com o banco de dados

try {
    // Consulta SQL para buscar todos os pacientes
    $stmt = $pdo->query("SELECT * FROM pacientes ORDER BY nome ASC");
    $pacientes = $stmt->fetchAll(); // Busca os dados
} catch (Exception $e) {
    die("Erro ao buscar pacientes: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Pacientes</title>
</head>
<body>
    <div class="container">
        <h1>Lista de Pacientes</h1>
        <?php if (empty($pacientes)): ?>
            <p class="error">Nenhum paciente cadastrado no sistema.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Data de Nascimento</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pacientes as $paciente): ?>
                        <tr>
                            <td><?= $paciente['id_paciente'] ?></td>
                            <td><?= htmlspecialchars($paciente['nome']) ?></td>
                            <td><?= $paciente['data_nascimento'] ?></td>
                            <td><?= $paciente['cpf'] ?></td>
                            <td><?= $paciente['telefone'] ?></td>
                            <td><?= htmlspecialchars($paciente['endereco']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
