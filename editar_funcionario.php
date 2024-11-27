<?php
require 'config.php'; // Inclui a conexão com o banco de dados

// Verifica se o ID foi enviado via GET
if (!isset($_GET['id'])) {
    die("ID do funcionário não foi fornecido!");
}

$id_funcionario = $_GET['id'];

// Busca os dados do funcionário no banco de dados
try {
    $stmt = $pdo->prepare("SELECT * FROM funcionarios WHERE id_funcionario = :id");
    $stmt->execute(['id' => $id_funcionario]);
    $funcionario = $stmt->fetch();

    if (!$funcionario) {
        die("Funcionário não encontrado!");
    }
} catch (Exception $e) {
    die("Erro ao buscar funcionário: " . $e->getMessage());
}

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    try {
        $stmt = $pdo->prepare("UPDATE funcionarios SET nome = :nome, cargo = :cargo, telefone = :telefone, email = :email WHERE id_funcionario = :id");
        $stmt->execute([
            'nome' => $nome,
            'cargo' => $cargo,
            'telefone' => $telefone,
            'email' => $email,
            'id' => $id_funcionario
        ]);
        echo "<p class='success'>Funcionário atualizado com sucesso!</p>";
        // Redireciona de volta para a página de funcionários
        header("Location: funcionarios.php");
        exit;
    } catch (Exception $e) {
        echo "<p class='error'>Erro ao atualizar funcionário: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Editar Funcionário</title>
</head>
<body>
    <div class="container">
        <h1>Editar Funcionário</h1>

        <!-- Botão de Voltar -->
        <div class="actions">
            <a class="btn btn-back" href="funcionarios.php">Voltar</a>
        </div>

        <form method="post" class="form">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($funcionario['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label for="cargo">Cargo:</label>
                <input type="text" name="cargo" id="cargo" value="<?= htmlspecialchars($funcionario['cargo']) ?>" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone" value="<?= htmlspecialchars($funcionario['telefone']) ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($funcionario['email']) ?>" required>
            </div>
            <button type="submit" class="btn btn-edit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
