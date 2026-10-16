<?php
require_once 'conexao.php';

$mensagem_erro = '';

// Buscar opções para os selects de paciente e dentista
try {
    $stmtPacientes = $pdo->prepare("SELECT id_paciente, nome FROM paciente ORDER BY nome ASC");
    $stmtPacientes->execute();
    $pacientes = $stmtPacientes->fetchAll();

    $stmtDentistas = $pdo->prepare("SELECT id_dentista, nome, especialidade FROM dentista ORDER BY nome ASC");
    $stmtDentistas->execute();
    $dentistas = $stmtDentistas->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao buscar registros auxiliares: " . $e->getMessage();
}

// Processar formulário de agendamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paciente = $_POST['id_paciente'] ?? '';
    $id_dentista = $_POST['id_dentista'] ?? '';
    $data_hora = $_POST['data_hora'] ?? '';
    $status_consulta = $_POST['status_consulta'] ?? '';

    if (empty($id_paciente) || empty($id_dentista) || empty($data_hora) || empty($status_consulta)) {
        $mensagem_erro = "Preencha todos os campos obrigatórios.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO consulta (id_paciente, id_dentista, data_hora, status_consulta) VALUES (:id_paciente, :id_dentista, :data_hora, :status_consulta)");
            $stmt->execute([
                ':id_paciente' => $id_paciente,
                ':id_dentista' => $id_dentista,
                ':data_hora' => $data_hora,
                ':status_consulta' => $status_consulta
            ]);

            header("Location: consultas_listar.php?sucesso=" . urlencode("Consulta agendada com sucesso!"));
            exit;
        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao agendar consulta: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OdontoSys - Agendar Consulta</title>
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
            <h2>Agendar Nova Consulta</h2>
            <br>
            <?php if ($mensagem_erro): ?>
                <div class="alert alert-error"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="consultas_cadastrar.php" method="POST">
                <div class="form-group">
                    <label for="id_paciente">Paciente *</label>
                    <select name="id_paciente" id="id_paciente" required>
                        <option value="">-- Selecione o Paciente --</option>
                        <?php foreach ($pacientes as $paciente): ?>
                            <option value="<?= $paciente['id_paciente'] ?>" <?= (($_POST['id_paciente'] ?? '') == $paciente['id_paciente']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($paciente['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_dentista">Dentista *</label>
                    <select name="id_dentista" id="id_dentista" required>
                        <option value="">-- Selecione o Dentista --</option>
                        <?php foreach ($dentistas as $dentista): ?>
                            <option value="<?= $dentista['id_dentista'] ?>" <?= (($_POST['id_dentista'] ?? '') == $dentista['id_dentista']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dentista['nome']) ?> (<?= htmlspecialchars($dentista['especialidade']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="data_hora">Data e Hora *</label>
                    <input type="datetime-local" id="data_hora" name="data_hora" value="<?= htmlspecialchars($_POST['data_hora'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="status_consulta">Status *</label>
                    <select name="status_consulta" id="status_consulta" required>
                        <option value="Agendada">Agendada</option>
                        <option value="Realizada">Realizada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>

                <button type="submit" class="btn">Confirmar Agendamento</button>
                <a href="consultas_listar.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; OdontoSys - Sistema de Gestão Odontológica</p>
    </footer>
</body>
</html>