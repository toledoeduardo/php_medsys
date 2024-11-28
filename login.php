<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #58936d, #416e52);
            flex-direction: column;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #fff;
        }
        .login-card {
            background: #fff;
            color: #333;
            width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .login-card h1 {
            font-size: 24px;
            color: #58936d;
        }
        .login-card input {
            width: calc(100% - 20px);
            margin: 10px auto;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .login-card button {
            padding: 10px 20px;
            background: #58936d;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .login-card button:hover {
            background: #416e52;
        }
        .login-card .error {
            color: red;
            font-size: 14px;
        }
        
        .logo {
            max-width: 200px; /* Ajuste a largura máxima */
            height: auto; /* Mantém a proporção da imagem */
            margin-bottom: 10px; /* Espaçamento abaixo da imagem */
            display: block; /* Garante que a imagem seja tratada como bloco */
            margin-left: auto; /* Centraliza horizontalmente */
            margin-right: auto; /* Centraliza horizontalmente */
}
    </style>
</head>
<body>
    <img src="img/logomedsys.png" alt="Logo" class="logo">
    <div class="container">      
        <h1>Login</h1>
        <?php
        require 'config.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome']);
            $senha = trim($_POST['senha']);
            $senha_hash = md5($senha);

            // Consulta para validar o login
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nome = :nome AND senha = :senha");
            $stmt->execute(['nome' => $nome, 'senha' => $senha_hash]);
            $usuario = $stmt->fetch();

            if ($usuario) {
                session_start(); // Inicia a sessão
                $_SESSION['logged_in'] = true; // Flag de login
                $_SESSION['id_usuario'] = $usuario['id_usuario']; // ID do usuário
                $_SESSION['nome'] = $usuario['nome']; // Nome do usuário
                $_SESSION['role'] = $usuario['tipo']; // Papel do usuário

                // Redireciona para o menu
                header("Location: menu.php");
                exit;
            } else {
                echo "<p class='error'>Nome ou senha inválidos!</p>";
            }
        }
        ?>
        <form method="post" class="form">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>
            </div>
            <button type="submit" class="btn">Entrar</button>
        </form>
    </div>
</body>
</html>
