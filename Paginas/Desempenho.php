<?php

session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../Login.php");
    exit();
}

require_once '../Configs/Conexao.php';

$anoAtual = date('Y');

$sql = "SELECT SUM(OrcPreco) AS totalPreco FROM OrcProdutos WHERE OrcStatus IN ('Venda', 'Produção', 'Entrega', 'Finalizado') AND YEAR(OrcDataVenda) = $anoAtual";


$result = $conexao->query($sql);

$total = 0;

if ($result && $row = $result->fetch_assoc()) {
    $total = $row['totalPreco'] ?? 0;
}

$totalFormatado = "R$ " . number_format($total, 2, ",", ".");

$sqlClientes = "SELECT COUNT(*) AS totalClientes FROM CliClientes";
$resultClientes = $conexao->query($sqlClientes);

$totalClientes = 0;
if ($resultClientes) {
    $rowClientes = $resultClientes->fetch_assoc();
    $totalClientes = $rowClientes['totalClientes'] ?? 0;
}

$sqlTotalVendas = "SELECT COUNT(*) AS totalVendas FROM OrcProdutos WHERE OrcStatus IN ('Venda', 'Produção', 'Entrega', 'Finalizado') AND YEAR(OrcDataVenda) = $anoAtual";
$resultVendas = $conexao->query($sqlTotalVendas);

$totalVendas = 0;
if ($resultVendas) {
    $rowVendas = $resultVendas->fetch_assoc();
    $totalVendas = $rowVendas['totalVendas'] ?? 0;
}

$sqlTotalOportunidades = "SELECT COUNT(OrcId) AS totalOportunidades FROM OrcProdutos WHERE OrcStatus NOT IN ('Cancelado', 'Desacordo')";
$resultOportunidades = $conexao->query($sqlTotalOportunidades);

$totalOportunidades = 0;
if ($resultOportunidades) {
    $rowOportunidades = $resultOportunidades->fetch_assoc();
    $totalOportunidades = $rowOportunidades['totalOportunidades'] ?? 0;
}

$taxaConversao = 0;
if ($totalOportunidades > 0) {
    $taxaConversao = ($totalVendas / $totalOportunidades) * 100;
    $taxaFormatada = number_format($taxaConversao, 2, ',', '.');
    $taxaFinal = $taxaFormatada . ' %';
}

$sqlTicketMedio = "SELECT AVG(OrcPreco) AS TicketMedio FROM OrcProdutos WHERE OrcStatus IN ('Venda', 'Finalizado') AND YEAR(OrcDataVenda) = $anoAtual";
$resultTicket = $conexao->query($sqlTicketMedio);

$ticketMedioBruto = 0.00;
if ($resultTicket) {
    $rowTicket = $resultTicket->fetch_assoc();
    $ticketMedioBruto = $rowTicket['TicketMedio'] ?? 0.00;
    $ticketMedioFormatado = number_format($ticketMedioBruto, 2, ',', '.');
    $ticketMedioFinal = 'R$ ' . $ticketMedioFormatado;
}

$sqlVolumeProducao = "SELECT COUNT(OrcId) AS volumeProducao FROM OrcProdutos WHERE OrcStatus IN ('Produção', 'Entrega')";
$resultVolume = $conexao->query($sqlVolumeProducao);

$volumeProducao = 0;
if ($resultVolume) {
    $rowVolume = $resultVolume->fetch_assoc();
    $volumeProducao = $rowVolume['volumeProducao'] ?? 0;
}

$sqlVendasOnline = "SELECT COUNT(OrcId) AS totalOnline FROM OrcProdutos WHERE OrcStatus IN ('Venda', 'Produção', 'Entrega', 'Finalizado') AND OrcOnline = 1 AND YEAR(OrcDataVenda) = $anoAtual";
$resultOnline = $conexao->query($sqlVendasOnline);

$totalOnline = 0;
if ($resultOnline) {
    $rowOnline = $resultOnline->fetch_assoc();
    $totalOnline = $rowOnline['totalOnline'] ?? 0;
}

$sqlTotalVendas = "SELECT COUNT(OrcId) AS totalGeral FROM OrcProdutos WHERE OrcStatus IN ('Venda', 'Produção', 'Entrega', 'Finalizado') AND YEAR(OrcDataVenda) = $anoAtual";
$resultTotal = $conexao->query($sqlTotalVendas);

$totalGeral = 0;
if ($resultTotal) {
    $rowTotal = $resultTotal->fetch_assoc();
    $totalGeral = $rowTotal['totalGeral'] ?? 0;
}

$percentualVendasOnlineBruto = 0;
if ($totalGeral > 0) {
    $percentualVendasOnlineBruto = ($totalOnline / $totalGeral) * 100;
    $percentualVendasOnlineFormatado = number_format($percentualVendasOnlineBruto, 2, ',', '.');
    $percentualVendasOnlineFinal = $percentualVendasOnlineFormatado . ' %';
}

$sqlTotalFalhas = "SELECT COUNT(OrcId) AS totalFalhas FROM OrcProdutos WHERE OrcStatus IN ('Desacordo', 'Cancelado')";
$resultFalhas = $conexao->query($sqlTotalFalhas);

$totalFalhas = 0;
if ($resultFalhas) {
    $rowFalhas = $resultFalhas->fetch_assoc();
    $totalFalhas = $rowFalhas['totalFalhas'] ?? 0;
}

$sqlTotalGeral = "SELECT COUNT(OrcId) AS totalGeral FROM OrcProdutos";
$resultGeral = $conexao->query($sqlTotalGeral);

$totalGeral = 0;
if ($resultGeral) {
    $rowGeral = $resultGeral->fetch_assoc();
    $totalGeral = $rowGeral['totalGeral'] ?? 0;
}

$taxaFalhaBruta = 0;
if ($totalGeral > 0) {
    $taxaFalhaBruta = ($totalFalhas / $totalGeral) * 100;
    $taxaFalhaFormatada = number_format($taxaFalhaBruta, 2, ',', '.') . '%';
}




$sql = "SELECT TIMESTAMPDIFF(DAY, OrcDataInicio, OrcDataEntrega) AS dias_diferenca FROM OrcProdutos WHERE OrcDataInicio IS NOT NULL AND OrcDataEntrega IS NOT NULL ORDER BY dias_diferenca ASC LIMIT 1";

$result = $conexao->query($sql);

$menorTempo = "Sem dados"; // valor padrão

if ($result && $row = $result->fetch_assoc()) {
    $menorTempo = $row['dias_diferenca'] . ' Dias';
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Desempenho</title>
    <link rel="stylesheet" href="../Css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div id="dashboard" class="dashboard">
        <header class="dashboard-header">
            <h1><i class="fas fa-chart-line"></i> Desempenho</h1>
            <button id="logoutBtn" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Sair
            </button>
        </header>

        <!-- Conteúdo Principal do Dashboard -->
        <main class="dashboard-content">

            <div id="desempenhoSection" class="desempenho-section">
                <div class="section-header">
                    <h2> Controle de Dados da Loja</h2>
                    <button id="backToDashboard" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </button>
                </div>

                <!-- Grid de KPIs -->
                <div class="kpi-grid" id="resumoKpis">
                    <div class="kpi-card revenue" id="receitaCard">
                        <div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div>
                        <div class="kpi-content">
                            <h3>Receita Bruta Total</h3>
                            <div class="kpi-value"><?php echo $totalFormatado; ?></div>
                            <div class="kpi-change positive">Valor Anual
                                <!--<i class="fas fa-arrow-up"></i>-->
                            </div>
                        </div>
                    </div>
                    <div class="kpi-card inventory" id="TicketCard">
                        <div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div>
                        <div class="kpi-content">
                            <h3>Ticket Médio</h3>
                            <div class="kpi-value"><?php echo $ticketMedioFinal; ?></div>
                            <div class="kpi-change neutral"></div>
                        </div>
                    </div>
                    <div class="kpi-card sales" id="vendaCard">
                        <div class="kpi-icon"><i class="fas fa-shopping-cart"></i></div>
                        <div class="kpi-content">
                            <h3>Vendas Realizadas</h3>
                            <div class="kpi-value"><?php echo $totalVendas; ?></div>
                            <div class="kpi-change positive">Vendas Anuais
                                <!--<i class="fas fa-arrow-up"></i>-->
                            </div>
                        </div>
                    </div>
                    <div class="kpi-card average-ticket" id="tempoCard">
                        <div class="kpi-icon"><i class="fas fa-hourglass"></i></div>
                        <div class="kpi-content">
                            <h3>Melhor Lead Time</h3>
                            <div class="kpi-value"><?php echo $menorTempo; ?></div>
                            <div class="kpi-change positive">
                                <!--<i class="fas fa-arrow-up"></i>-->
                            </div>
                        </div>
                    </div>
                    <div class="kpi-card customers" id="clientesCard">
                        <div class="kpi-icon"><i class="fas fa-users"></i></div>
                        <div class="kpi-content">
                            <h3>Total de Clientes</h3>
                            <div class="kpi-value"><?php echo $totalClientes; ?></div>
                            <div class="kpi-change positive">
                                <!--<i class="fas fa-arrow-up"></i>-->
                            </div>
                        </div>
                    </div>
                    <div class="kpi-card inventory" id="volumeCard">
                        <div class="kpi-icon"><i class="fas fa-cogs"></i></div>
                        <div class="kpi-content">
                            <h3>Volume de Produção</h3>
                            <div class="kpi-value"><?php echo $volumeProducao; ?></div>
                            <div class="kpi-change neutral"></div>
                        </div>
                    </div>
                    <div class="kpi-card conversion" id="conversaoCard">
                        <div class="kpi-icon"><i class="fas fa-percentage"></i></div>
                        <div class="kpi-content">
                            <h3>Taxa de Conversão</h3>
                            <div class="kpi-value"><?php echo $taxaFinal; ?></div>
                            <div class="kpi-change positive"></div>
                        </div>
                    </div>
                    <div class="kpi-card inventory" id="vendasPCard">
                        <div class="kpi-icon"><i class="fas fa-percentage"></i></div>
                        <div class="kpi-content">
                            <h3>% de Vendas Online</h3>
                            <div class="kpi-value"><?php echo $percentualVendasOnlineFinal; ?></div>
                            <div class="kpi-change neutral"></div>
                        </div>
                    </div>
                    <div class="kpi-card inventory" id="falhasCard">
                        <div class="kpi-icon"><i class="fa-solid fa-thumbs-down"></i></div>
                        <div class="kpi-content">
                            <h3>Total de Falhas</h3>
                            <div class="kpi-value"><?php echo  $taxaFalhaFormatada; ?></div>
                            <div class="kpi-change neutral"></div>
                        </div>
                    </div>
                </div>

                <div id="detalhesContainer">
                    <section id="cardReceitaDetalhes" class="Section hidden">
                        <div class="section-header">
                            <h2>Detalhes da Receita Total</h2>
                        </div>
                        <p><iframe title="Movelaria" width="100%" height="573.5"
                                src="https://app.powerbi.com/view?r=eyJrIjoiYzdmMDJmNzgtNjk5ZS00ZTg0LWFkYjYtOGZlNGE0ZTJkMjdmIiwidCI6ImNmNzJlMmJkLTdhMmItNDc4My1iZGViLTM5ZDU3YjA3Zjc2ZiIsImMiOjR9"
                                frameborder="0" allowFullScreen="true"></iframe></p>
                    </section>

                    <section id="cardTicketDetalhe" class="Section hidden">
                        <div class="section-header">
                            <h2>Detalhes do Ticket Médio</h2>
                        </div>
                        <p><iframe title="Movelaria" width="100%" height="573.5"
                                src="https://app.powerbi.com/view?r=eyJrIjoiYzdmMDJmNzgtNjk5ZS00ZTg0LWFkYjYtOGZlNGE0ZTJkMjdmIiwidCI6ImNmNzJlMmJkLTdhMmItNDc4My1iZGViLTM5ZDU3YjA3Zjc2ZiIsImMiOjR9"
                                frameborder="0" allowFullScreen="true"></iframe></p>
                    </section>

                    <section id="cardVendasDetalhe" class="Section hidden">
                        <div class="section-header">
                            <h2>Detalhes de Vendas Realizadas</h2>
                        </div>
                        <p><iframe title="Movelaria" width="100%" height="573.5"
                                src="https://app.powerbi.com/view?r=eyJrIjoiYzdmMDJmNzgtNjk5ZS00ZTg0LWFkYjYtOGZlNGE0ZTJkMjdmIiwidCI6ImNmNzJlMmJkLTdhMmItNDc4My1iZGViLTM5ZDU3YjA3Zjc2ZiIsImMiOjR9"
                                frameborder="0" allowFullScreen="true"></iframe></p>
                    </section>

                    <section id="cardTempoDetalhe" class="Section hidden">
                        <div class="section-header">
                            <h2>Detalhes do Melhor Tempo</h2>

                        </div>
                        <p><iframe title="Movelaria" width="100%" height="573.5"
                                src="https://app.powerbi.com/view?r=eyJrIjoiYzdmMDJmNzgtNjk5ZS00ZTg0LWFkYjYtOGZlNGE0ZTJkMjdmIiwidCI6ImNmNzJlMmJkLTdhMmItNDc4My1iZGViLTM5ZDU3YjA3Zjc2ZiIsImMiOjR9"
                                frameborder="0" allowFullScreen="true"></iframe></p>
                    </section>

                    <section id="cardClientesDetalhe" class="Section hidden">
                        <div class="section-header">
                            <h2>Detalhes de Total de Clientes</h2>
                        </div>
                        <p><iframe title="Movelaria" width="100%" height="573.5"
                                src="https://app.powerbi.com/view?r=eyJrIjoiYzdmMDJmNzgtNjk5ZS00ZTg0LWFkYjYtOGZlNGE0ZTJkMjdmIiwidCI6ImNmNzJlMmJkLTdhMmItNDc4My1iZGViLTM5ZDU3YjA3Zjc2ZiIsImMiOjR9"
                                frameborder="0" allowFullScreen="true"></iframe></p>
                    </section>

                    <section id="cardConversaoDetalhe" class="Section hidden">
                        <div class="section-header">
                            <h2>Detalhes da Taxa de Conversão</h2>
                        </div>
                        <p><iframe title="Movelaria" width="100%" height="573.5"
                                src="https://app.powerbi.com/view?r=eyJrIjoiYzdmMDJmNzgtNjk5ZS00ZTg0LWFkYjYtOGZlNGE0ZTJkMjdmIiwidCI6ImNmNzJlMmJkLTdhMmItNDc4My1iZGViLTM5ZDU3YjA3Zjc2ZiIsImMiOjR9"
                                frameborder="0" allowFullScreen="true"></iframe></p>
                    </section>

                    <section id="cardVolumeDetalhe" class="Section hidden">
                        <div class="section-header">
                            <h2>Detalhes do Volume de Produção</h2>
                        </div>
                        <p><iframe title="Movelaria" width="100%" height="573.5"
                                src="https://app.powerbi.com/view?r=eyJrIjoiYzdmMDJmNzgtNjk5ZS00ZTg0LWFkYjYtOGZlNGE0ZTJkMjdmIiwidCI6ImNmNzJlMmJkLTdhMmItNDc4My1iZGViLTM5ZDU3YjA3Zjc2ZiIsImMiOjR9"
                                frameborder="0" allowFullScreen="true"></iframe></p>
                    </section>

                    <section id="cardVendasPDetalhe" class="Section hidden">
                        <div class="section-header">
                            <h2>Detalhes de % de Vendas</h2>
                        </div>
                        <p><iframe title="Movelaria" width="100%" height="573.5"
                                src="https://app.powerbi.com/view?r=eyJrIjoiYzdmMDJmNzgtNjk5ZS00ZTg0LWFkYjYtOGZlNGE0ZTJkMjdmIiwidCI6ImNmNzJlMmJkLTdhMmItNDc4My1iZGViLTM5ZDU3YjA3Zjc2ZiIsImMiOjR9"
                                frameborder="0" allowFullScreen="true"></iframe></p>
                    </section>

                    <section id="cardFalhasDetalhe" class="Section hidden">
                        <div class="section-header">
                            <h2>Detalhes das Falhas de Venda</h2>
                        </div>
                        <p><iframe title="Movelaria" width="100%" height="573.5"
                                src="https://app.powerbi.com/view?r=eyJrIjoiYzdmMDJmNzgtNjk5ZS00ZTg0LWFkYjYtOGZlNGE0ZTJkMjdmIiwidCI6ImNmNzJlMmJkLTdhMmItNDc4My1iZGViLTM5ZDU3YjA3Zjc2ZiIsImMiOjR9"
                                frameborder="0" allowFullScreen="true"></iframe></p>
                    </section>

                </div>
        </main>
    </div>

    <script src="../Js/script.js"></script>

</body>

</html>