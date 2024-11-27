<?php
require 'config.php'; // Inclua a configuração do banco de dados

// Adicionar novo usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_user') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = md5($_POST['senha']); // Criptografa a senha com MD5
    $tipo = $_POST['tipo'];

    try {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $senha, $tipo]);
        echo "<p style='color: green;'>Usuário adicionado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erro ao adicionar usuário: " . $e->getMessage() . "</p>";
    }
}

// Editar usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit_user') {
    $id = $_POST['id_usuario'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];

    try {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, tipo = ? WHERE id_usuario = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $tipo, $id]);
        echo "<p style='color: green;'>Usuário atualizado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erro ao atualizar usuário: " . $e->getMessage() . "</p>";
    }
}

// Excluir usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_user') {
    $id = $_POST['id_usuario'];

    try {
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo "<p style='color: green;'>Usuário excluído com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erro ao excluir usuário: " . $e->getMessage() . "</p>";
    }
}

// Trocar senha
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'change_password') {
    $id = $_POST['id_usuario'];
    $nova_senha = md5($_POST['nova_senha']); // Criptografa a nova senha com MD5

    try {
        $sql = "UPDATE usuarios SET senha = ? WHERE id_usuario = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nova_senha, $id]);
        echo "<p style='color: green;'>Senha atualizada com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erro ao atualizar senha: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table th {
            background: #58936d;
            color: white;
        }
        .btn {
            padding: 5px 10px;
            background: #58936d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background: #416e52;
        }
        .back-button {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        .back-button:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gerenciar Usuários</h1>
        
        <!-- Formulário para adicionar novo usuário -->
        <h2>Adicionar Novo Usuário</h2>
        <form method="post">
            <input type="hidden" name="action" value="add_user">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required>
                <option value="administrador">Administrador</option>
                <option value="medico">Médico</option>
                <option value="recepcionista">Recepcionista</option>
            </select>
            <button class="btn" type="submit">Adicionar Usuário</button>
        </form>

        <!-- Lista de usuários existentes -->
        <h2>Usuários Cadastrados</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
            <?php
            $usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($usuarios as $usuario) {
                echo "<tr>
                    <td>{$usuario['id_usuario']}</td>
                    <td>{$usuario['nome']}</td>
                    <td>{$usuario['email']}</td>
                    <td>{$usuario['tipo']}</td>
                    <td>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='action' value='edit_user'>
                            <input type='hidden' name='id_usuario' value='{$usuario['id_usuario']}'>
                            <input type='text' name='nome' value='{$usuario['nome']}' required>
                            <input type='email' name='email' value='{$usuario['email']}' required>
                            <select name='tipo' required>
                                <option value='administrador' " . ($usuario['tipo'] == 'administrador' ? 'selected' : '') . ">Administrador</option>
                                <option value='medico' " . ($usuario['tipo'] == 'medico' ? 'selected' : '') . ">Médico</option>
                                <option value='recepcionista' " . ($usuario['tipo'] == 'recepcionista' ? 'selected' : '') . ">Recepcionista</option>
                            </select>
                            <button class='btn' type='submit'>Editar</button>
                        </form>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='action' value='delete_user'>
                            <input type='hidden' name='id_usuario' value='{$usuario['id_usuario']}'>
                            <button class='btn' type='submit'>Excluir</button>
                        </form>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='action' value='change_password'>
                            <input type='hidden' name='id_usuario' value='{$usuario['id_usuario']}'>
                            <input type='password' name='nova_senha' placeholder='Nova Senha' required>
                            <button class='btn' type='submit'>Alterar Senha</button>
                        </form>
                    </td>
                </tr>";
            }
            ?>
        </table>

        <!-- Botão Voltar -->
        <a href="menu.php" class="back-button">Voltar ao Menu</a>
    </div>
</body>
</html>
