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
            <p><strong>Nome:</strong> <?= $paciente['nome'] ?></p>
            <p><strong>Data de Nascimento:</strong> <?= $paciente['data_nascimento'] ?></p>
            <p><strong>CPF:</strong> <?= $paciente['cpf'] ?></p>
            <p><strong>Telefone:</strong> <?= $paciente['telefone'] ?></p>
            <p><strong>Endereço:</strong> <?= $paciente['endereco'] ?></p>
            <a class="btn" href="editar_paciente.php?id=<?= $paciente['id_paciente'] ?>">Editar Paciente</a>
        </div>
    </div>
</body>
</html>
