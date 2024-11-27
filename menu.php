<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal - MedSys</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8f0;
            margin: 0;
            padding: 0;
        }
        .menu-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .menu-container h1 {
            color: #58936d;
            text-align: center;
            font-size: 32px;
            font-weight: bold;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .menu-card {
            background: #58936d;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
        }
        .menu-card h2 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: white; /* Título sempre visível */
        }
        /* Subtítulo escondido inicialmente */
        .menu-card p {
            margin: 10px 0 0;
            font-size: 14px;
            opacity: 0; /* Subtítulo invisível por padrão */
            transition: opacity 0.3s ease;
        }
        /* Mostrar subtítulo ao passar o mouse */
        .menu-card:hover p {
            opacity: 1; /* Subtítulo visível */
        }
        .logout-button {
            display: block;
            margin: 20px auto 0;
            padding: 10px 20px;
            text-align: center;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        .logout-button:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <h1>Menu Principal</h1>
        <div class="menu-grid">
            <?php if ($role === 'administrador' || $role === 'recepcionista'): ?>
            <a href="gestao_pacientes.php" class="menu-card">
                <h2>Gestão de Pacientes</h2>
                <p>Gerencie os dados dos pacientes.</p>
            </a>
            <?php endif; ?>

            <?php if ($role === 'administrador' || $role === 'recepcionista' || $role === 'medico'): ?>
            <a href="consultas.php" class="menu-card">
                <h2>Gestão de Consultas</h2>
                <p>Controle os agendamentos de consultas.</p>
            </a>
            <?php endif; ?>

            <?php if ($role === 'administrador' || $role === 'medico'): ?>
            <a href="prontuarios.php" class="menu-card">
                <h2>Gestão de Prontuários</h2>
                <p>Veja os prontuários médicos.</p>
            </a>
            <?php endif; ?>

            <?php if ($role === 'administrador'): ?>
            <a href="funcionarios.php" class="menu-card">
                <h2>Gestão de Funcionários</h2>
                <p>Gerencie a equipe médica e administrativa.</p>
            </a>
            <a href="relatorios.php" class="menu-card">
                <h2>Relatórios</h2>
                <p>Gere relatórios detalhados.</p>
            </a>
            <a href="adicionar_usuario.php" class="menu-card">
                <h2>Adicionar Usuário</h2>
                <p>Insira novos usuários no sistema.</p>
            </a>
            <?php endif; ?>
        </div>
        <a href="logout.php" class="logout-button">Sair</a>
    </div>
</body>
</html>
