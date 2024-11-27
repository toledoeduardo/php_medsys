<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$pacienteIndex = isset($_GET['paciente']) ? (int)$_GET['paciente'] : null;
$atendimentoIndex = isset($_GET['atendimento']) ? (int)$_GET['atendimento'] : null;

$paciente = $_SESSION['pacientes'][$pacienteIndex] ?? null;
$atendimento = $paciente['atendimentos'][$atendimentoIndex] ?? null;

if (!$paciente || !$atendimento) {
    echo "Atendimento ou paciente não encontrado!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Atendimento - MedSys</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8f0;
            margin: 0;
            padding: 0;
        }
        .details-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .details-container h1 {
            color: #58936d;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
        }
        .details-container p {
            font-size: 16px;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        .details-container .back-button {
            display: block;
            margin: 20px auto 0;
            padding: 12px 20px;
            text-align: center;
            background: #58936d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            width: 200px;
            transition: background 0.3s ease;
        }
        .details-container .back-button:hover {
            background: #416e52;
        }
    </style>
</head>
<body>
    <div class="details-container">
        <h1>Detalhes do Atendimento</h1>
        <p><strong>Data:</strong> <?= htmlspecialchars($atendimento['data']) ?></p>
        <p><strong>Descrição:</strong> <?= htmlspecialchars($atendimento['descricao']) ?></p>
        <p><strong>Exames Solicitados:</strong> <?= htmlspecialchars(implode(", ", $atendimento['exames'] ?? [])) ?></p>
        <p><strong>Médico:</strong> <?= htmlspecialchars($atendimento['medico']) ?></p>
        <p><strong>Paciente:</strong> <?= htmlspecialchars($paciente['nome']) ?> (CPF: <?= htmlspecialchars($paciente['cpf']) ?>)</p>
        <p><strong>Sinais Vitais:</strong> <?= htmlspecialchars($atendimento['sinais_vitais'] ?? "Não informados") ?></p>
        <a href="detalhes_paciente.php?index=<?= $pacienteIndex ?>" class="back-button">Voltar</a>
    </div>
</body>
</html>
