<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OdontoSys - Painel Principal</title>
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
            <h2>Bem-vindo ao Sistema OdontoSys</h2>
            <p>Selecione uma das opções abaixo para gerenciar pacientes e agendamentos.</p>
            
            <div class="card-grid">
                <div class="dashboard-card">
                    <h3>Pacientes</h3>
                    <p>Cadastre novos pacientes ou edite as informações existentes.</p>
                    <a href="pacientes_cadastrar.php" class="btn">Novo Paciente</a>
                    <a href="pacientes_listar.php" class="btn btn-secondary">Listar</a>
                </div>
                
                <div class="dashboard-card">
                    <h3>Consultas</h3>
                    <p>Agende novas consultas ou visualize a agenda completa.</p>
                    <a href="consultas_cadastrar.php" class="btn">Nova Consulta</a>
                    <a href="consultas_listar.php" class="btn btn-secondary">Listar Agenda</a>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; OdontoSys - Sistema de Gestão Odontológica</p>
    </footer>
</body>
</html>