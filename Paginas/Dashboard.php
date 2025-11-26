<?php
    session_start();
    if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../Login.php");
    exit();
    }

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Controle de Negocio</title>
    <link rel="stylesheet" href="../Css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div id="dashboard" class="dashboard">
        <header class="dashboard-header">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
            <button id="logoutBtn" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Sair
            </button>
        </header>

        <!-- Conteúdo Principal do Dashboard -->
        <main class="dashboard-content">
            <!-- Card de Boas-Vindas -->
            <div class="welcome-card">
                <h2>Bem-vindo,
                    <?php echo isset($_SESSION['usuario_logado']) ? htmlspecialchars($_SESSION['usuario_logado']) : 'Usuário'; ?>!
                </h2>
                <p>Você está logado com sucesso no sistema.</p>
            </div>

            <!-- Grid de Navegação Principal -->
            <div class="dashboard-grid">
                <!-- Card de Orçamento -->
                <div class="dashboard-card" id="OrcamentoCard">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <h3>Orçamento</h3>
                    <p>Gerencie seus Pedidos</p>
                </div>
                <!-- Card de Vendas -->
                <div class="dashboard-card" id="vendasCard">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Vendas</h3>
                    <p>Gerencie suas Vendas </p>
                </div>
                <!-- Card de Produção -->
                <div class="dashboard-card" id="ProducaoCard">
                    <i class="fas fa-cogs"></i>
                    <h3>Produção</h3>
                    <p>Controle de Produção</p>
                </div>
                <!-- Card de Estoque -->
                <div class="dashboard-card" id="entregaCard">
                    <i class="fas fa-boxes"></i>
                    <h3>Entrega</h3>
                    <p>Controle de Entregas</p>
                </div>

                <!-- Card de Cliente -->
                <div class="dashboard-card" id="clienteCard">
                    <i class="fas fa-user"></i>
                    <h3>Cliente</h3>
                    <p>Controle de clientes</p>
                </div>

                <!-- Card de Desempenho -->
                <div class="dashboard-card" id="desempenhoCard">
                    <i class="fas fa-chart-line"></i>
                    <h3>Desempenho</h3>
                    <p>Visualize métricas e relatórios</p>
                </div>

                <!-- Card de Vitrine -->
                <div class="dashboard-card" id="vitrineCard">
                    <i class="fas fa-store"></i>
                    <h3>Vitrine</h3>
                    <p>Cadastre Vitrine</p>
                </div>

                <!-- Card de Configurações -->
                <div class="dashboard-card" id="configuracoesCard">
                    <i class="fas fa-cog"></i>
                    <h3>Configurações</h3>
                    <p>Gerencie suas preferências</p>
                </div>
            </div>
    </div>
    </main>
    </div>


    <script src="../Js/script.js"></script>

</body>

</html>