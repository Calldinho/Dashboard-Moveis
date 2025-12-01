<?php

session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../Login.php");
    exit();
}

require_once '../Configs/Conexao.php';

$anoAtual = date('Y');
$anoAnterior = $anoAtual - 1;

$sql = "SELECT SUM(OrcPreco) AS totalPreco FROM OrcProdutos WHERE OrcStatus IN ('Venda', 'Produção', 'Entrega', 'Finalizado') AND YEAR(OrcDataVenda) = $anoAtual";


$result = $conexao->query($sql);

$total = 0;

if ($result && $row = $result->fetch_assoc()) {
    $total = $row['totalPreco'] ?? 0;
}

$totalFormatado = "R$ " . number_format($total, 2, ",", ".");

$sqlClientes = "SELECT COUNT(*) AS totalClientes FROM CliClientes WHERE YEAR(CliCadastro) = $anoAtual";
$resultClientes = $conexao->query($sqlClientes);

$totalClientes = 0;
if ($resultClientes) {
    $rowClientes = $resultClientes->fetch_assoc();
    $totalClientes = $rowClientes['totalClientes'] ?? 0;
}

// Total de clientes no ano anterior
$sqlClientesPrev = "SELECT COUNT(*) AS totalClientesPrev FROM CliClientes WHERE YEAR(CliCadastro) = $anoAnterior";
$resultClientesPrev = $conexao->query($sqlClientesPrev);

$totalClientesPrev = 0;
if ($resultClientesPrev) {
    $rowClientesPrev = $resultClientesPrev->fetch_assoc();
    $totalClientesPrev = $rowClientesPrev['totalClientesPrev'] ?? 0;
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

// Total de Falhas (ano atual) - MOVE ANTES das comparações
$sqlTotalFalhas = "SELECT COUNT(OrcId) AS totalFalhas FROM OrcProdutos WHERE OrcStatus IN ('Desacordo', 'Cancelado') AND YEAR(OrcDataVenda) = $anoAtual";
$resultFalhas = $conexao->query($sqlTotalFalhas);

$totalFalhas = 0;
if ($resultFalhas) {
    $rowFalhas = $resultFalhas->fetch_assoc();
    $totalFalhas = $rowFalhas['totalFalhas'] ?? 0;
}

$sqlTotalGeralCurrent = "SELECT COUNT(OrcId) AS totalGeral FROM OrcProdutos WHERE YEAR(OrcDataVenda) = $anoAtual";
$resultGeralCurrent = $conexao->query($sqlTotalGeralCurrent);

$totalGeralCurrent = 0;
if ($resultGeralCurrent) {
    $rowGeralCurrent = $resultGeralCurrent->fetch_assoc();
    $totalGeralCurrent = $rowGeralCurrent['totalGeral'] ?? 0;
}

$taxaFalhaBruta = 0;
if ($totalGeralCurrent > 0) {
    $taxaFalhaBruta = ($totalFalhas / $totalGeralCurrent) * 100;
    $taxaFalhaFormatada = number_format($taxaFalhaBruta, 2, ',', '.') . '%';
}

// Volume de Produção (ano atual)
$sqlVolumeProducao = "SELECT COUNT(OrcId) AS volumeProducao FROM OrcProdutos WHERE OrcStatus IN ('Produção', 'Entrega') AND YEAR(OrcDataInicio) = $anoAtual";
$resultVolume = $conexao->query($sqlVolumeProducao);

$volumeProducao = 0;
if ($resultVolume) {
    $rowVolume = $resultVolume->fetch_assoc();
    $volumeProducao = $rowVolume['volumeProducao'] ?? 0;
}

// Volume de Produção (ano anterior)
$sqlVolumeProducaoPrev = "SELECT COUNT(OrcId) AS volumeProducaoPrev FROM OrcProdutos WHERE OrcStatus IN ('Produção', 'Entrega') AND YEAR(OrcDataInicio) = $anoAnterior";
$resultVolumePrev = $conexao->query($sqlVolumeProducaoPrev);

$volumeProducaoPrev = 0;
if ($resultVolumePrev) {
    $rowVolumePrev = $resultVolumePrev->fetch_assoc();
    $volumeProducaoPrev = $rowVolumePrev['volumeProducaoPrev'] ?? 0;
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

// --- Valores do ano anterior (quando aplicável) ---
// Receita ano anterior
$sqlReceitaPrev = "SELECT SUM(OrcPreco) AS totalPrecoPrev FROM OrcProdutos WHERE OrcStatus IN ('Venda', 'Produção', 'Entrega', 'Finalizado') AND YEAR(OrcDataVenda) = $anoAnterior";
$resultReceitaPrev = $conexao->query($sqlReceitaPrev);
$receitaPrev = 0;
if ($resultReceitaPrev && $row = $resultReceitaPrev->fetch_assoc()) {
    $receitaPrev = $row['totalPrecoPrev'] ?? 0;
}

// Ticket médio ano anterior
$sqlTicketPrev = "SELECT AVG(OrcPreco) AS TicketMedioPrev FROM OrcProdutos WHERE OrcStatus IN ('Venda', 'Finalizado') AND YEAR(OrcDataVenda) = $anoAnterior";
$resultTicketPrev = $conexao->query($sqlTicketPrev);
$ticketPrev = 0.00;
if ($resultTicketPrev && $row = $resultTicketPrev->fetch_assoc()) {
    $ticketPrev = $row['TicketMedioPrev'] ?? 0.00;
}

// Vendas realizadas ano anterior
$sqlVendasPrev = "SELECT COUNT(*) AS totalVendasPrev FROM OrcProdutos WHERE OrcStatus IN ('Venda', 'Produção', 'Entrega', 'Finalizado') AND YEAR(OrcDataVenda) = $anoAnterior";
$resultVendasPrev = $conexao->query($sqlVendasPrev);
$vendasPrev = 0;
if ($resultVendasPrev && $row = $resultVendasPrev->fetch_assoc()) {
    $vendasPrev = $row['totalVendasPrev'] ?? 0;
}

// Conversão ano anterior
$sqlOportPrev = "SELECT COUNT(OrcId) AS totalOportunidadesPrev FROM OrcProdutos WHERE OrcStatus NOT IN ('Cancelado', 'Desacordo') AND YEAR(OrcDataVenda) = $anoAnterior";
$resultOportPrev = $conexao->query($sqlOportPrev);
$oportPrev = 0;
if ($resultOportPrev && $row = $resultOportPrev->fetch_assoc()) {
    $oportPrev = $row['totalOportunidadesPrev'] ?? 0;
}

$taxaConversaoPrev = null;
if ($oportPrev > 0) {
    $taxaConversaoPrev = ($vendasPrev / $oportPrev) * 100;
}

// Vendas online ano anterior
$sqlOnlinePrev = "SELECT COUNT(OrcId) AS totalOnlinePrev FROM OrcProdutos WHERE OrcStatus IN ('Venda', 'Produção', 'Entrega', 'Finalizado') AND OrcOnline = 1 AND YEAR(OrcDataVenda) = $anoAnterior";
$resultOnlinePrev = $conexao->query($sqlOnlinePrev);
$onlinePrev = 0;
if ($resultOnlinePrev && $row = $resultOnlinePrev->fetch_assoc()) {
    $onlinePrev = $row['totalOnlinePrev'] ?? 0;
}

$totalGeralPrev = 0;
$sqlTotalPrev = "SELECT COUNT(OrcId) AS totalGeralPrev FROM OrcProdutos WHERE OrcStatus IN ('Venda', 'Produção', 'Entrega', 'Finalizado') AND YEAR(OrcDataVenda) = $anoAnterior";
$resultTotalPrev = $conexao->query($sqlTotalPrev);
if ($resultTotalPrev && $row = $resultTotalPrev->fetch_assoc()) {
    $totalGeralPrev = $row['totalGeralPrev'] ?? 0;
}

$percentualVendasOnlinePrev = null;
if ($totalGeralPrev > 0) {
    $percentualVendasOnlinePrev = ($onlinePrev / $totalGeralPrev) * 100;
}

// Taxa de falha ano anterior
$sqlFalhasPrev = "SELECT COUNT(OrcId) AS totalFalhasPrev FROM OrcProdutos WHERE OrcStatus IN ('Desacordo', 'Cancelado') AND YEAR(OrcDataVenda) = $anoAnterior";
$resultFalhasPrev = $conexao->query($sqlFalhasPrev);
$falhasPrev = 0;
if ($resultFalhasPrev && $row = $resultFalhasPrev->fetch_assoc()) {
    $falhasPrev = $row['totalFalhasPrev'] ?? 0;
}

$taxaFalhaPrev = null;
if ($totalGeralPrev > 0) {
    $taxaFalhaPrev = ($falhasPrev / $totalGeralPrev) * 100;
}

// Função auxiliar de comparação (retorna classe e texto)
function kpi_compare($current, $previous, $format = null) {
    $cls = 'neutral';
    $text = '';

    // Se previous for NULL, tratar como 0 para exibir comparação
    if ($previous === null) {
        $previous = 0;
    }

    if ($current > $previous) {
        $cls = 'positive';
        $text = '<i class="fas fa-arrow-up"></i> Maior que ano anterior';
    } elseif ($current < $previous) {
        $cls = 'negative';
        $text = '<i class="fas fa-arrow-down"></i> Menor que ano anterior';
    } else {
        $cls = 'neutral';
        $text = 'Igual ao ano anterior';
    }

    return [$cls, $text];
}

// Função de comparação INVERSA para KPIs onde MENOS é melhor (ex: falhas)
function kpi_compare_inverse($current, $previous, $format = null) {
    $cls = 'neutral';
    $text = '';

    if ($previous === null) {
        $previous = 0;
    }

    if ($current > $previous) {
        // Mais falhas que antes = RUIM (vermelho/negative)
        $cls = 'negative';
        $text = '<i class="fas fa-arrow-up"></i> Maior que ano anterior';
    } elseif ($current < $previous) {
        // Menos falhas que antes = BOM (verde/positive)
        $cls = 'positive';
        $text = '<i class="fas fa-arrow-down"></i> Menor que ano anterior';
    } else {
        $cls = 'neutral';
        $text = 'Igual ao ano anterior';
    }

    return [$cls, $text];
}

// Preparar comparações
list($receita_change_class, $receita_change_text) = kpi_compare((float)$total, (float)$receitaPrev);
list($ticket_change_class, $ticket_change_text) = kpi_compare((float)$ticketMedioBruto, (float)$ticketPrev);
list($vendas_change_class, $vendas_change_text) = kpi_compare((int)$totalVendas, (int)$vendasPrev);

// Lead time (menor é melhor - lógica inversa)
$sqlMenorTempoAtual = "SELECT TIMESTAMPDIFF(DAY, OrcDataInicio, OrcDataEntrega) AS dias_diferenca FROM OrcProdutos WHERE OrcDataInicio IS NOT NULL AND OrcDataEntrega IS NOT NULL AND YEAR(OrcDataInicio) = $anoAtual ORDER BY dias_diferenca ASC LIMIT 1";
$resultTempoAtual = $conexao->query($sqlMenorTempoAtual);
$menorTempoAtual = null;
if ($resultTempoAtual && $row = $resultTempoAtual->fetch_assoc()) {
    $menorTempoAtual = $row['dias_diferenca'];
}

// Se não houver dados do ano atual, buscar o melhor geral
if ($menorTempoAtual === null) {
    $sqlMenorTempoGeralAtual = "SELECT TIMESTAMPDIFF(DAY, OrcDataInicio, OrcDataEntrega) AS dias_diferenca FROM OrcProdutos WHERE OrcDataInicio IS NOT NULL AND OrcDataEntrega IS NOT NULL ORDER BY dias_diferenca ASC LIMIT 1";
    $resultTempoGeralAtual = $conexao->query($sqlMenorTempoGeralAtual);
    if ($resultTempoGeralAtual && $row = $resultTempoGeralAtual->fetch_assoc()) {
        $menorTempoAtual = $row['dias_diferenca'];
    }
}

$sqlMenorTempoPrev = "SELECT TIMESTAMPDIFF(DAY, OrcDataInicio, OrcDataEntrega) AS dias_diferenca FROM OrcProdutos WHERE OrcDataInicio IS NOT NULL AND OrcDataEntrega IS NOT NULL AND YEAR(OrcDataInicio) = $anoAnterior ORDER BY dias_diferenca ASC LIMIT 1";
$resultTempoPrev = $conexao->query($sqlMenorTempoPrev);
$menorTempoPrev = null;
if ($resultTempoPrev && $row = $resultTempoPrev->fetch_assoc()) {
    $menorTempoPrev = $row['dias_diferenca'];
}

// Comparar Lead Time (invertido: menos é melhor)
if ($menorTempoAtual !== null && $menorTempoPrev !== null) {
    list($tempo_change_class, $tempo_change_text) = kpi_compare_inverse((int)$menorTempoAtual, (int)$menorTempoPrev);
} else {
    list($tempo_change_class, $tempo_change_text) = ['neutral', ''];
}

// Volume de Produção (mais é melhor)
list($volume_change_class, $volume_change_text) = kpi_compare((int)$volumeProducao, (int)$volumeProducaoPrev);

// Clientes com comparação por data de cadastro
list($clientes_change_class, $clientes_change_text) = kpi_compare((int)$totalClientes, (int)$totalClientesPrev);

$taxaAtualVal = ($taxaConversao ?? 0);
list($conversao_change_class, $conversao_change_text) = kpi_compare($taxaAtualVal, ($taxaConversaoPrev ?? null));

$vendasPAtualVal = ($percentualVendasOnlineBruto ?? 0);
list($vendasP_change_class, $vendasP_change_text) = kpi_compare($vendasPAtualVal, ($percentualVendasOnlinePrev ?? null));

// FALHAS COM LÓGICA INVERSA (menos é melhor)
$falhaAtualVal = ($taxaFalhaBruta ?? 0);
list($falhas_change_class, $falhas_change_text) = kpi_compare_inverse($falhaAtualVal, ($taxaFalhaPrev ?? null));

// Lead Time - valor formatado para exibição
$menorTempo = "Sem dados";
if ($menorTempoAtual !== null) {
    $menorTempo = $menorTempoAtual . ' Dias';
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
                            <div class="kpi-change <?php echo $receita_change_class; ?>"><?php echo $receita_change_text; ?></div>
                        </div>
                    </div>
                    <div class="kpi-card inventory" id="TicketCard">
                        <div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div>
                        <div class="kpi-content">
                            <h3>Ticket Médio</h3>
                            <div class="kpi-value"><?php echo $ticketMedioFinal; ?></div>
                            <div class="kpi-change <?php echo $ticket_change_class; ?>"><?php echo $ticket_change_text; ?></div>
                        </div>
                    </div>
                    <div class="kpi-card sales" id="vendaCard">
                        <div class="kpi-icon"><i class="fas fa-shopping-cart"></i></div>
                        <div class="kpi-content">
                            <h3>Vendas Realizadas</h3>
                            <div class="kpi-value"><?php echo $totalVendas; ?></div>
                            <div class="kpi-change <?php echo $vendas_change_class; ?>"><?php echo $vendas_change_text; ?></div>
                        </div>
                    </div>
                    <div class="kpi-card average-ticket" id="tempoCard">
                        <div class="kpi-icon"><i class="fas fa-hourglass"></i></div>
                        <div class="kpi-content">
                            <h3>Melhor Lead Time</h3>
                            <div class="kpi-value"><?php echo $menorTempo; ?></div>
                            <div class="kpi-change <?php echo $tempo_change_class; ?>"><?php echo $tempo_change_text; ?></div>
                        </div>
                    </div>
                    <div class="kpi-card customers" id="clientesCard">
                        <div class="kpi-icon"><i class="fas fa-users"></i></div>
                        <div class="kpi-content">
                            <h3>Total de Clientes</h3>
                            <div class="kpi-value"><?php echo $totalClientes; ?></div>
                            <div class="kpi-change <?php echo $clientes_change_class; ?>"><?php echo $clientes_change_text; ?></div>
                        </div>
                    </div>
                    <div class="kpi-card inventory" id="volumeCard">
                        <div class="kpi-icon"><i class="fas fa-cogs"></i></div>
                        <div class="kpi-content">
                            <h3>Volume de Produção</h3>
                            <div class="kpi-value"><?php echo $volumeProducao; ?></div>
                            <div class="kpi-change <?php echo $volume_change_class; ?>"><?php echo $volume_change_text; ?></div>
                        </div>
                    </div>
                    <div class="kpi-card conversion" id="conversaoCard">
                        <div class="kpi-icon"><i class="fas fa-percentage"></i></div>
                        <div class="kpi-content">
                            <h3>Taxa de Conversão</h3>
                            <div class="kpi-value"><?php echo $taxaFinal; ?></div>
                            <div class="kpi-change <?php echo $conversao_change_class; ?>"><?php echo $conversao_change_text; ?></div>
                        </div>
                    </div>
                    <div class="kpi-card inventory" id="vendasPCard">
                        <div class="kpi-icon"><i class="fas fa-percentage"></i></div>
                        <div class="kpi-content">
                            <h3>% de Vendas Online</h3>
                            <div class="kpi-value"><?php echo $percentualVendasOnlineFinal; ?></div>
                            <div class="kpi-change <?php echo $vendasP_change_class; ?>"><?php echo $vendasP_change_text; ?></div>
                        </div>
                    </div>
                    <div class="kpi-card inventory" id="falhasCard">
                        <div class="kpi-icon"><i class="fa-solid fa-thumbs-down"></i></div>
                        <div class="kpi-content">
                            <h3>Total de Falhas</h3>
                            <div class="kpi-value"><?php echo  $taxaFalhaFormatada; ?></div>
                            <div class="kpi-change <?php echo $falhas_change_class; ?>"><?php echo $falhas_change_text; ?></div>
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