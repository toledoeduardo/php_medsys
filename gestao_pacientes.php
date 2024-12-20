<?php
require 'config.php'; // Inclui a conexão com o banco de dados

// Processa exclusão se houver um ID enviado via GET
if (isset($_GET['excluir'])) {
    $id_paciente = $_GET['excluir'];
    try {
        $stmt = $pdo->prepare("DELETE FROM pacientes WHERE id_paciente = :id");
        $stmt->execute(['id' => $id_paciente]);
        echo "<p class='success'>Paciente excluído com sucesso!</p>";
    } catch (Exception $e) {
        echo "<p class='error'>Erro ao excluir paciente: " . $e->getMessage() . "</p>";
    }
}

// Variável para armazenar pacientes encontrados
$pacientes = [];

// Processa a busca se houver termo enviado via GET
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    try {
        // Busca por nome ou ID
        $stmt = $pdo->prepare("SELECT * FROM pacientes WHERE nome LIKE :search OR id_paciente = :id ORDER BY nome ASC");
        $stmt->execute([
            'search' => '%' . $search . '%', // Busca parcial no nome
            'id' => is_numeric($search) ? intval($search) : null // Busca exata no ID
        ]);
        $pacientes = $stmt->fetchAll();
    } catch (Exception $e) {
        echo "<p class='error'>Erro ao buscar pacientes: " . $e->getMessage() . "</p>";
    }
} else {
    // Consulta SQL para buscar todos os pacientes caso não haja busca
    try {
        $stmt = $pdo->query("SELECT * FROM pacientes ORDER BY nome ASC");
        $pacientes = $stmt->fetchAll(); // Busca os dados
    } catch (Exception $e) {
        die("Erro ao buscar pacientes: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Gestão de Pacientes</title>
</head>
<body>
    <div class="container">
        <h1>Gestão de Pacientes</h1>

        <!-- Botões de Voltar e Cadastrar -->
        <div class="actions">
            <a class="btn btn-back" href="menu.php">Voltar</a>
            <a class="btn btn-add" href="cadastro_pacientes.php">Cadastrar Paciente</a>
        </div>

        <!-- Formulário de busca -->
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Buscar por nome ou ID" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit" class="btn btn-search">Buscar</button>
        </form>

        <?php if (empty($pacientes)): ?>
            <p class="error">Nenhum paciente encontrado.</p>
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
                        <th>Ações</th>
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
                            <td>
                                <a class="btn" href="detalhes_paciente.php?id=<?= $paciente['id_paciente'] ?>">Ver Detalhes</a>
                                <a class="btn btn-edit" href="editar_paciente.php?id=<?= $paciente['id_paciente'] ?>">Editar</a>
                                <a class="btn btn-delete" href="gestao_pacientes.php?excluir=<?= $paciente['id_paciente'] ?>" 
                                   onclick="return confirm('Tem certeza que deseja excluir este paciente?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
