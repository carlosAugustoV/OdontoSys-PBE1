<?php
require_once 'conexao.php';

$mensagem_erro = '';
$mensagem_sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $data_nascimento = trim($_POST['data_nascimento'] ?? '');

    // Validações no PHP
    if (empty($nome) || empty($cpf) || empty($telefone) || empty($data_nascimento)) {
        $mensagem_erro = "Preencha todos os campos obrigatórios.";
    } elseif (!preg_match('/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', $cpf)) {
        $mensagem_erro = "Formato de CPF inválido. Utilize o formato 000.000.000-00.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO paciente (nome, cpf, telefone, data_nascimento) VALUES (:nome, :cpf, :telefone, :data_nascimento)");
            $stmt->execute([
                ':nome' => $nome,
                ':cpf' => $cpf,
                ':telefone' => $telefone,
                ':data_nascimento' => $data_nascimento
            ]);

            header("Location: pacientes_listar.php?sucesso=" . urlencode("Paciente cadastrado com sucesso!"));
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Código para erro de UNIQUE constraint
                $mensagem_erro = "Este CPF já está cadastrado no sistema.";
            } else {
                $mensagem_erro = "Erro ao cadastrar paciente: " . $e->getMessage();
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
    <title>OdontoSys - Cadastrar Paciente</title>
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
            <h2>Cadastrar Novo Paciente</h2>
            <br>
            <?php if ($mensagem_erro): ?>
                <div class="alert alert-error"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="pacientes_cadastrar.php" method="POST">
                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="cpf">CPF (ex: 000.000.000-00) *</label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone *</label>
                    <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento *</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '') ?>" required>
                </div>

                <button type="submit" class="btn">Salvar Paciente</button>
                <a href="pacientes_listar.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; OdontoSys - Sistema de Gestão Odontológica</p>
    </footer>
</body>
</html>