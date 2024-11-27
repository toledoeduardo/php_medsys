<?php
require 'config.php'; // Inclui a conexão com o banco de dados

// Busca todos os pacientes e médicos para os selects
try {
    $stmtPacientes = $pdo->query("SELECT id_paciente, nome FROM pacientes");
    $pacientes = $stmtPacientes->fetchAll();

    $stmtFuncionarios = $pdo->query("SELECT id_funcionario, nome FROM funcionarios");
    $funcionarios = $stmtFuncionarios->fetchAll();
} catch (Exception $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}

// Processa o formulário de agendamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paciente = $_POST['id_paciente'];
    $id_funcionario = $_POST['id_funcionario'];
    $data_hora = $_POST['data_hora'];
    $status = 'agendada'; // Status inicial é "agendada"
    $observacoes = $_POST['observacoes'];

    try {
        $stmt = $pdo->prepare("INSERT INTO consultas (id_paciente, id_funcionario, data_hora, status, observacoes)
                               VALUES (:id_paciente, :id_funcionario, :data_hora, :status, :observacoes)");
        $stmt->execute([
            'id_paciente' => $id_paciente,
            'id_funcionario' => $id_funcionario,
            'data_hora' => $data_hora,
            'status' => $status,
            'observacoes' => $observacoes
        ]);
        echo "<p class='success'>Consulta agendada com sucesso!</p>";
        header("Location: consultas.php");
        exit;
    } catch (Exception $e) {
        echo "<p class='error'>Erro ao agendar consulta: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Agendamento de Consultas</title>
</head>
<body>
    <div class="container">
        <h1>Agendar Consulta</h1>

        <!-- Botão de Voltar -->
        <div class="actions">
            <a class="btn btn-back" href="consultas.php">Voltar</a>
        </div>

        <!-- Formulário de Agendamento -->
        <form method="post" class="form">
            <div class="form-group">
                <label for="id_paciente">Paciente:</label>
                <select name="id_paciente" id="id_paciente" required>
                    <option value="">Selecione um paciente</option>
                    <?php foreach ($pacientes as $paciente): ?>
                        <option value="<?= $paciente['id_paciente'] ?>"><?= htmlspecialchars($paciente['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_funcionario">Médico:</label>
                <select name="id_funcionario" id="id_funcionario" required>
                    <option value="">Selecione um médico</option>
                    <?php foreach ($funcionarios as $funcionario): ?>
                        <option value="<?= $funcionario['id_funcionario'] ?>"><?= htmlspecialchars($funcionario['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="data_hora">Data e Hora:</label>
                <input type="datetime-local" name="data_hora" id="data_hora" required>
            </div>
            <div class="form-group">
                <label for="observacoes">Observações:</label>
                <textarea name="observacoes" id="observacoes"></textarea>
            </div>
            <button type="submit" class="btn btn-add">Agendar</button>
        </form>
    </div>
</body>
</html>
