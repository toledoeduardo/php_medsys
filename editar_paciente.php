<?php
require 'config.php'; // Inclui a conexão com o banco de dados

// Verifica se o ID foi enviado via GET
if (!isset($_GET['id'])) {
    die("ID do paciente não foi fornecido!");
}

$id_paciente = $_GET['id'];

// Busca os dados do paciente no banco de dados
try {
    $stmt = $pdo->prepare("SELECT * FROM pacientes WHERE id_paciente = :id");
    $stmt->execute(['id' => $id_paciente]);
    $paciente = $stmt->fetch();

    if (!$paciente) {
        die("Paciente não encontrado!");
    }
} catch (Exception $e) {
    die("Erro ao buscar paciente: " . $e->getMessage());
}

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    try {
        $stmt = $pdo->prepare("UPDATE pacientes SET nome = :nome, data_nascimento = :data_nascimento, cpf = :cpf, telefone = :telefone, endereco = :endereco WHERE id_paciente = :id");
        $stmt->execute([
            'nome' => $nome,
            'data_nascimento' => $data_nascimento,
            'cpf' => $cpf,
            'telefone' => $telefone,
            'endereco' => $endereco,
            'id' => $id_paciente
        ]);
        echo "<p class='success'>Paciente atualizado com sucesso!</p>";
        // Redireciona de volta para a página de gestão de pacientes
        header("Location: gestao_pacientes.php");
        exit;
    } catch (Exception $e) {
        echo "<p class='error'>Erro ao atualizar paciente: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Editar Paciente</title>
</head>
<body>
    <div class="container">
        <h1>Editar Paciente</h1>

        <!-- Botão de Voltar -->
        <div class="actions">
            <a class="btn btn-back" href="gestao_pacientes.php">Voltar</a>
        </div>

        <form method="post" class="form">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($paciente['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" name="data_nascimento" id="data_nascimento" value="<?= $paciente['data_nascimento'] ?>" required>
            </div>
            <div class="form-group">
                <label for="cpf">CPF:</label>
                <input type="text" name="cpf" id="cpf" value="<?= $paciente['cpf'] ?>" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone" value="<?= $paciente['telefone'] ?>">
            </div>
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <textarea name="endereco" id="endereco"><?= htmlspecialchars($paciente['endereco']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-edit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
