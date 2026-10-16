<?php
require_once 'conexao.php';

try {
    // SELECT com JOIN nas tabelas paciente e dentista
    $sql = "SELECT c.id_consulta, c.status_consulta, c.data_hora, 
                   p.nome AS paciente_nome, d.nome AS dentista_nome, d.especialidade
            FROM consulta c
            INNER JOIN paciente p ON c.id_paciente = p.id_paciente
            INNER JOIN dentista d ON c.id_dentista = d.id_dentista
            ORDER BY c.data_hora DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $consultas = $stmt->fetchAll();
} catch (PDOException $e) {
    $erro = "Erro ao carregar lista de consultas: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OdontoSys - Consultas</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <header>
        <h1>OdontoSys</h1>
    </header>
    <nav>
        <a href="index.php">Início</a>
        <a href="pacientes_listar.php">Pacientes</a>
        <a href="consultas_listar.php">Consultas</a>
    </nav>
    <div class="container">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Agenda de Consultas</h2>
                <a href="consultas_cadastrar.php" class="btn">Agendar Consulta</a>
            </div>

            <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert alert-success" style="margin-top: 1rem;">
                    <?= htmlspecialchars($_GET['sucesso']) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($erro)): ?>
                <div class="alert alert-error" style="margin-top: 1rem;"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data e Hora</th>
                        <th>Paciente</th>
                        <th>Dentista</th>
                        <th>Especialidade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($consultas) > 0): ?>
                        <?php foreach ($consultas as $consulta): ?>
                            <tr>
                                <td><?= htmlspecialchars($consulta['id_consulta']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($consulta['data_hora'])) ?></td>
                                <td><?= htmlspecialchars($consulta['paciente_nome']) ?></td>
                                <td><?= htmlspecialchars($consulta['dentista_nome']) ?></td>
                                <td><?= htmlspecialchars($consulta['especialidade']) ?></td>
                                <td><?= htmlspecialchars($consulta['status_consulta']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Nenhuma consulta agendada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        <p>&copy; OdontoSys - Sistema de Gestão Odontológica</p>
    </footer>
</body>
</html>