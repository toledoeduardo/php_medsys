<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Cadastro de Pacientes</title>
</head>
<body>
    <div class="container">
        <h1>Cadastrar Paciente</h1>

        <!-- Botão de Voltar -->
        <div class="actions">
            <a class="btn btn-back" href="gestao_pacientes.php">Voltar</a>
        </div>

        <?php
        require 'config.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'];
            $data_nascimento = $_POST['data_nascimento'];
            $cpf = $_POST['cpf'];
            $telefone = $_POST['telefone'];
            $endereco = $_POST['endereco'];

            try {
                $stmt = $pdo->prepare("INSERT INTO pacientes (nome, data_nascimento, cpf, telefone, endereco) VALUES (:nome, :data_nascimento, :cpf, :telefone, :endereco)");
                $stmt->execute([
                    'nome' => $nome,
                    'data_nascimento' => $data_nascimento,
                    'cpf' => $cpf,
                    'telefone' => $telefone,
                    'endereco' => $endereco
                ]);
                echo "<p class='success'>Paciente cadastrado com sucesso!</p>";
            } catch (Exception $e) {
                echo "<p class='error'>Erro ao cadastrar paciente: " . $e->getMessage() . "</p>";
            }
        }
        ?>

        <form method="post" class="form">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" required>
            </div>
            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" name="data_nascimento" id="data_nascimento" required>
            </div>
            <div class="form-group">
                <label for="cpf">CPF:</label>
                <input type="text" name="cpf" id="cpf" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone">
            </div>
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <textarea name="endereco" id="endereco"></textarea>
            </div>
            <button type="submit" class="btn btn-add">Cadastrar</button>
        </form>
    </div>
</body>
</html>
