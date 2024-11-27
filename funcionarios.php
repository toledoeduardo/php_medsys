<?php
require 'config.php'; // Inclui a conexão com o banco de dados

// Processa exclusão se houver um ID enviado via GET
if (isset($_GET['excluir'])) {
    $id_funcionario = $_GET['excluir'];
    try {
        $stmt = $pdo->prepare("DELETE FROM funcionarios WHERE id_funcionario = :id");
        $stmt->execute(['id' => $id_funcionario]);
        echo "<p class='success'>Funcionário excluído com sucesso!</p>";
    } catch (Exception $e) {
        echo "<p class='error'>Erro ao excluir funcionário: " . $e->getMessage() . "</p>";
    }
}

// Processa o formulário de adição de funcionário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    try {
        $stmt = $pdo->prepare("INSERT INTO funcionarios (nome, cargo, telefone, email) VALUES (:nome, :cargo, :telefone, :email)");
        $stmt->execute([
            'nome' => $nome,
            'cargo' => $cargo,
            'telefone' => $telefone,
            'email' => $email
        ]);
        echo "<p class='success'>Funcionário adicionado com sucesso!</p>";
    } catch (Exception $e) {
        echo "<p class='error'>Erro ao adicionar funcionário: " . $e->getMessage() . "</p>";
    }
}

// Consulta SQL para buscar todos os funcionários
try {
    $stmt = $pdo->query("SELECT * FROM funcionarios ORDER BY nome ASC");
    $funcionarios = $stmt->fetchAll(); // Busca os dados
} catch (Exception $e) {
    die("Erro ao buscar funcionários: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Funcionários</title>
</head>
<body>
    <div class="container">
        <h1>Gestão de Funcionários</h1>

        <!-- Botão de Voltar -->
        <div class="actions">
            <a class="btn btn-back" href="menu.php">Voltar</a>
        </div>

        <!-- Formulário para adicionar novo funcionário -->
        <form method="post" class="form">
            <h2>Adicionar Funcionário</h2>
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" required>
            </div>
            <div class="form-group">
                <label for="cargo">Cargo:</label>
                <input type="text" name="cargo" id="cargo" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <button type="submit" name="adicionar" class="btn btn-add">Adicionar</button>
        </form>

        <!-- Tabela para exibir funcionários -->
        <?php if (empty($funcionarios)): ?>
            <p class="error">Nenhum funcionário cadastrado no sistema.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cargo</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($funcionarios as $funcionario): ?>
                        <tr>
                            <td><?= $funcionario['id_funcionario'] ?></td>
                            <td><?= htmlspecialchars($funcionario['nome']) ?></td>
                            <td><?= htmlspecialchars($funcionario['cargo']) ?></td>
                            <td><?= $funcionario['telefone'] ?></td>
                            <td><?= $funcionario['email'] ?></td>
                            <td>
                                <a class="btn btn-edit" href="editar_funcionario.php?id=<?= $funcionario['id_funcionario'] ?>">Editar</a>
                                <a class="btn btn-delete" href="funcionarios.php?excluir=<?= $funcionario['id_funcionario'] ?>" 
                                   onclick="return confirm('Tem certeza que deseja excluir este funcionário?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
