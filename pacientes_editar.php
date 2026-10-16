<?php
require_once 'conexao.php';

$mensagem_erro = '';
$paciente = null;

// Obter ID via GET
$id_paciente = $_GET['id'] ?? null;

if (!$id_paciente) {
    header('Location: pacientes_listar.php');
    exit;
}

// Buscar dados do paciente
try {
    $stmt = $pdo->prepare("SELECT * FROM paciente WHERE id_paciente = :id");
    $stmt->execute([':id' => $id_paciente]);
    $paciente = $stmt->fetch();

    if (!$paciente) {
        die("<div class='container'><div class='alert alert-error'>Registro não encontrado.</div><a href='pacientes_listar.php' class='btn'>Voltar</a></div>");
    }
} catch (PDOException $e) {
    die("Erro ao consultar registro: " . $e->getMessage());
}

// Processar atualização via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $data_nascimento = trim($_POST['data_nascimento'] ?? '');

    if (empty($nome) || empty($cpf) || empty($telefone) || empty($data_nascimento)) {
        $mensagem_erro = "Preencha todos os campos obrigatórios.";
    } elseif (!preg_match('/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', $cpf)) {
        $mensagem_erro = "Formato de CPF inválido. Utilize o formato 000.000.000-00.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE paciente SET nome = :nome, cpf = :cpf, telefone = :telefone, data_nascimento = :data_nascimento WHERE id_paciente = :id");
            $stmt->execute([
                ':nome' => $nome,
                ':cpf' => $cpf,
                ':telefone' => $telefone,
                ':data_nascimento' => $data_nascimento,
                ':id' => $id_paciente
            ]);

            header("Location: pacientes_listar.php?sucesso=" . urlencode("Paciente atualizado com sucesso!"));
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $mensagem_erro = "Este CPF já pertence a outro paciente registrado.";
            } else {
                $mensagem_erro = "Erro ao atualizar paciente: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OdontoSys - Editar Paciente</title>
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
            <h2>Editar Paciente #<?= htmlspecialchars($paciente['id_paciente']) ?></h2>
            <br>
            <?php if ($mensagem_erro): ?>
                <div class="alert alert-error"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="pacientes_editar.php?id=<?= htmlspecialchars($id_paciente) ?>" method="POST">
                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? $paciente['nome']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="cpf">CPF *</label>
                    <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($_POST['cpf'] ?? $paciente['cpf']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone *</label>
                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($_POST['telefone'] ?? $paciente['telefone']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento *</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($_POST['data_nascimento'] ?? $paciente['data_nascimento']) ?>" required>
                </div>

                <button type="submit" class="btn">Atualizar Paciente</button>
                <a href="pacientes_listar.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; OdontoSys - Sistema de Gestão Odontológica</p>
    </footer>
</body>
</html>