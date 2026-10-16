<?php
require_once 'conexao.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM paciente ORDER BY nome ASC");
    $stmt->execute();
    $pacientes = $stmt->fetchAll();
} catch (PDOException $e) {
    $erro = "Erro ao buscar pacientes: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OdontoSys - Pacientes</title>
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
                <h2>Pacientes Cadastrados</h2>
                <a href="pacientes_cadastrar.php" class="btn">Novo Paciente</a>
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
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Data de Nascimento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pacientes) > 0): ?>
                        <?php foreach ($pacientes as $paciente): ?>
                            <tr>
                                <td><?= htmlspecialchars($paciente['id_paciente']) ?></td>
                                <td><?= htmlspecialchars($paciente['nome']) ?></td>
                                <td><?= htmlspecialchars($paciente['cpf']) ?></td>
                                <td><?= htmlspecialchars($paciente['telefone']) ?></td>
                                <td><?= date('d/m/Y', strtotime($paciente['data_nascimento'])) ?></td>
                                <td>
                                    <a href="pacientes_editar.php?id=<?= $paciente['id_paciente'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Nenhum paciente encontrado.</td>
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