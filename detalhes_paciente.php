<?php
// Inclui a configuração do banco de dados
include 'config.php';

// Inicializa a variável $paciente para evitar erros
$paciente = null;

// Verifica se o ID do paciente foi passado via GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_paciente = intval($_GET['id']); // Converte o ID para inteiro

    // Consulta para buscar os dados do paciente pelo ID
    $query = "SELECT * FROM pacientes WHERE id_paciente = :id_paciente";
    
    try {
        $stmt = $pdo->prepare($query); // Prepara a consulta com PDO
        $stmt->bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
        $stmt->execute();

        // Verifica se encontrou o paciente
        if ($stmt->rowCount() > 0) {
            $paciente = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém os dados como um array associativo
        } else {
            die("Paciente não encontrado.");
        }
    } catch (PDOException $e) {
        die("Erro ao executar a consulta: " . $e->getMessage());
    }
} else {
    die("ID do paciente inválido ou não fornecido.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Detalhes do Paciente</title>
</head>
<body>
    <div class="container">
        <h1>Detalhes do Paciente</h1>
        <div class="card">
            <?php if ($paciente): ?>
                <p><strong>Nome:</strong> <?= htmlspecialchars($paciente['nome']) ?></p>
                <p><strong>Data de Nascimento:</strong> <?= htmlspecialchars($paciente['data_nascimento']) ?></p>
                <p><strong>CPF:</strong> <?= htmlspecialchars($paciente['cpf']) ?></p>
                <p><strong>Telefone:</strong> <?= htmlspecialchars($paciente['telefone']) ?></p>
                <p><strong>Endereço:</strong> <?= htmlspecialchars($paciente['endereco']) ?></p>
                <a class="btn" href="editar_paciente.php?id=<?= htmlspecialchars($paciente['id_paciente']) ?>">Editar Paciente</a>
                <a class="btn" href="javascript:history.back()">Voltar</a> <!-- Botão de Voltar -->
            <?php else: ?>
                <p>Erro ao carregar os detalhes do paciente.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
