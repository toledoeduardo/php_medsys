<?php
require 'config.php'; // Inclua a configuração do banco de dados
require 'fpdf/fpdf.php'; // Inclua a biblioteca FPDF

// Função para buscar número total de médicos
function getQuantidadeMedicos($pdo) {
    $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE tipo = 'medico'";
    $stmt = $pdo->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// Função para buscar número total de pacientes
function getQuantidadePacientes($pdo) {
    $sql = "SELECT COUNT(*) AS total FROM pacientes";
    $stmt = $pdo->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// Função para buscar número total de consultas
function getQuantidadeConsultas($pdo, $data = null, $medico = null, $paciente = null) {
    $sql = "SELECT COUNT(*) AS total FROM consultas WHERE 1=1";
    $params = [];

    if ($data) {
        $sql .= " AND DATE(data_hora) = ?";
        $params[] = $data;
    }

    if ($medico) {
        $sql .= " AND id_funcionario = ?";
        $params[] = $medico;
    }

    if ($paciente) {
        $sql .= " AND id_paciente = ?";
        $params[] = $paciente;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// Função para buscar detalhes das consultas
function getDetalhesConsultas($pdo, $data = null, $medico = null, $paciente = null) {
    $sql = "
        SELECT c.id_consulta, p.nome AS paciente, f.nome AS medico, c.data_hora, c.status, c.observacoes
        FROM consultas c
        JOIN pacientes p ON c.id_paciente = p.id_paciente
        JOIN funcionarios f ON c.id_funcionario = f.id_funcionario
        WHERE 1=1
    ";
    $params = [];

    if ($data) {
        $sql .= " AND DATE(c.data_hora) = ?";
        $params[] = $data;
    }

    if ($medico) {
        $sql .= " AND c.id_funcionario = ?";
        $params[] = $medico;
    }

    if ($paciente) {
        $sql .= " AND c.id_paciente = ?";
        $params[] = $paciente;
    }

    $sql .= " ORDER BY c.data_hora DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Gerar PDF
if (isset($_POST['generate_pdf'])) {
    $data = $_POST['data_consulta'] ?? null;
    $medico = $_POST['id_medico'] ?? null;
    $paciente = $_POST['id_paciente'] ?? null;

    $quantidadeMedicos = getQuantidadeMedicos($pdo);
    $quantidadePacientes = getQuantidadePacientes($pdo);
    $quantidadeConsultas = getQuantidadeConsultas($pdo, $data, $medico, $paciente);
    $detalhesConsultas = getDetalhesConsultas($pdo, $data, $medico, $paciente);

    // Criar o PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Título do relatório
    $pdf->Cell(0, 10, 'Relatório Geral', 0, 1, 'C');
    $pdf->Ln(10);

    // Informações Gerais
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Quantidade de Médicos: $quantidadeMedicos", 0, 1);
    $pdf->Cell(0, 10, "Quantidade de Pacientes: $quantidadePacientes", 0, 1);
    $pdf->Cell(0, 10, "Quantidade de Consultas: $quantidadeConsultas", 0, 1);
    $pdf->Ln(10);

    // Consultas Detalhadas
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Detalhes das Consultas', 0, 1);
    $pdf->SetFont('Arial', '', 12);

    foreach ($detalhesConsultas as $consulta) {
        $pdf->Cell(0, 10, "Paciente: " . $consulta['paciente'], 0, 1);
        $pdf->Cell(0, 10, "Médico: " . $consulta['medico'], 0, 1);
        $pdf->Cell(0, 10, "Data/Hora: " . $consulta['data_hora'], 0, 1);
        $pdf->Cell(0, 10, "Status: " . ucfirst($consulta['status']), 0, 1);
        if (!empty($consulta['observacoes'])) {
            $pdf->MultiCell(0, 10, "Observações: " . $consulta['observacoes']);
        }
        $pdf->Ln(5);
    }

    // Saída do PDF
    $pdf->Output('I', 'Relatorio_Geral.pdf');
    exit;
}

// Obter filtros enviados via formulário
$data = $_POST['data_consulta'] ?? null;
$medico = $_POST['id_medico'] ?? null;
$paciente = $_POST['id_paciente'] ?? null;

// Buscar informações
$quantidadeMedicos = getQuantidadeMedicos($pdo);
$quantidadePacientes = getQuantidadePacientes($pdo);
$quantidadeConsultas = getQuantidadeConsultas($pdo, $data, $medico, $paciente);
$detalhesConsultas = getDetalhesConsultas($pdo, $data, $medico, $paciente);

// Buscar médicos e pacientes para os filtros
$medicos = $pdo->query("SELECT id_funcionario, nome FROM funcionarios WHERE cargo = 'medico'")->fetchAll(PDO::FETCH_ASSOC);
$pacientes = $pdo->query("SELECT id_paciente, nome FROM pacientes")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilização omitida para manter o foco no código PHP */
    </style>
</head>
<body>
    <div class="container">
        <h1>Relatórios</h1>

        <!-- Relatórios Gerais -->
        <h2>Informações Gerais</h2>
        <p><strong>Quantidade de Médicos:</strong> <?php echo $quantidadeMedicos; ?></p>
        <p><strong>Quantidade de Pacientes:</strong> <?php echo $quantidadePacientes; ?></p>
        <p><strong>Quantidade de Consultas:</strong> <?php echo $quantidadeConsultas; ?></p>

        <!-- Formulário de Filtros -->
        <h2>Consultas</h2>
        <form method="post" class="filter-form">
            <label for="data_consulta">Data:</label>
            <input type="date" id="data_consulta" name="data_consulta" value="<?php echo $data; ?>">

            <label for="id_medico">Médico:</label>
            <select id="id_medico" name="id_medico">
                <option value="">Todos</option>
                <?php foreach ($medicos as $med): ?>
                    <option value="<?php echo $med['id_funcionario']; ?>" <?php echo ($medico == $med['id_funcionario']) ? 'selected' : ''; ?>>
                        <?php echo $med['nome']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="id_paciente">Paciente:</label>
            <select id="id_paciente" name="id_paciente">
                <option value="">Todos</option>
                <?php foreach ($pacientes as $pac): ?>
                    <option value="<?php echo $pac['id_paciente']; ?>" <?php echo ($paciente == $pac['id_paciente']) ? 'selected' : ''; ?>>
                        <?php echo $pac['nome']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn">Filtrar</button>
            <button type="submit" name="generate_pdf" class="btn">Exportar PDF</button>
        </form>

        <!-- Detalhes das Consultas -->
        <h3>Detalhes das Consultas</h3>
        <?php if (empty($detalhesConsultas)): ?>
            <p>Nenhuma consulta encontrada.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Data/Hora</th>
                        <th>Status</th>
                        <th>Observações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalhesConsultas as $consulta): ?>
                        <tr>
                            <td><?php echo $consulta['id_consulta']; ?></td>
                            <td><?php echo htmlspecialchars($consulta['paciente']); ?></td>
                            <td><?php echo htmlspecialchars($consulta['medico']); ?></td>
                            <td><?php echo $consulta['data_hora']; ?></td>
                            <td><?php echo ucfirst($consulta['status']); ?></td>
                            <td><?php echo htmlspecialchars($consulta['observacoes']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Botão Voltar -->
        <a href="menu.php" class="back-button">Voltar ao Menu</a>
    </div>
</body>
</html>
