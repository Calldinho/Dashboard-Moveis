<?php
require_once '../Configs/Conexao.php';

// Variável para mensagem de sucesso
$mensagem = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['orcClienteNome'])) {
        $CliNome = trim($_POST['orcClienteNome'] ?? '');
        $CliCPFPJ = trim($_POST['orcClienteCPFPJ'] ?? '');
        $CliEndereço = trim($_POST['orcClienteEnde'] ?? '');
        $CliBairro = trim($_POST['orcClienteBai'] ?? '');
        $CliCidade = trim($_POST['orcClienteCid'] ?? '');
        $CliCEP = trim($_POST['orcClienteCEP'] ?? '');
        $CliTelefone = trim($_POST['orcClienteTEL'] ?? '');
        $CliCelular = trim($_POST['orcClienteCEL'] ?? '');
        $CliEmail = trim($_POST['orcClienteEmail'] ?? '');

        $OrcNome = trim($_POST['ProNome'] ?? '');
        $OrcPreco = trim($_POST['ProPreco'] ?? '');
        $OrcDescricao = trim($_POST['ProDescricao'] ?? '');
        $OrcTipo = trim($_POST['itemTipo'] ?? '');
        $OrcMaterial = trim($_POST['ProMaterial'] ?? '');
        $OrcVendaOnline = isset($_POST['ProOnline']) ? 1 : 0;

        // Checa se já existe cliente com o mesmo CPF/CNPJ
        $sqlBusca = "SELECT CliId FROM CliClientes WHERE CliCpfCnpj = ? LIMIT 1";
        $stmtBusca = $conexao->prepare($sqlBusca);
        $stmtBusca->bind_param("s", $CliCPFPJ);
        $stmtBusca->execute();
        $stmtBusca->store_result();
        if ($stmtBusca->num_rows > 0) {
            // Cliente já existe, pega o ID
            $stmtBusca->bind_result($CliId);
            $stmtBusca->fetch();
            $stmtBusca->close();
        } else {
            // Cliente não existe, cadastra
            $stmtBusca->close();
            $sqlCliente = "INSERT INTO CliClientes (CliNome, CliCpfCnpj, CliEndereco, CliBairro, CliCidade, CliCEP, CliTelefone, CliCelular, CliEmail) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtCliente = $conexao->prepare($sqlCliente);    
            $stmtCliente->bind_param("sssssssss", $CliNome, $CliCPFPJ, $CliEndereço, $CliBairro, $CliCidade, $CliCEP, $CliTelefone, $CliCelular, $CliEmail);
            $stmtCliente->execute();      
            $CliId = $conexao->insert_id;
            $stmtCliente->close();
        }

        // Cadastra o produto vinculado ao cliente
        $sqlOrcamento = "INSERT INTO OrcProdutos (OrcNome, OrcPreco, OrcDescricao, OrcTipo, OrcMaterial, OrcOnline, OrcCliente) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtProduto = $conexao->prepare($sqlOrcamento);
        $stmtProduto->bind_param("sdsssii", $OrcNome, $OrcPreco, $OrcDescricao, $OrcTipo, $OrcMaterial, $OrcVendaOnline, $CliId);
        $stmtProduto->execute();
        $stmtProduto->close();
        $conexao->close();    

        $mensagem = "Cadastro de Orçamento concluído!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Autenticação</title>
    <link rel="stylesheet" href="../Css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div id="dashboard" class="dashboard">
        <header class="dashboard-header">
            <h1><i class="fas fa-file-invoice-dollar"></i>Cadastro Orçamentos</h1>
            <button id="logoutBtn" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Sair
            </button>
        </header>

        <main class="dashboard-content">
            <?php if (!empty($mensagem)) { ?>
            <div class="alert-success"
                style="background:#d4edda;color:#155724;padding:10px;margin-bottom:15px;border-radius:5px;border:1px solid #c3e6cb;">
                <i class="fas fa-check-circle"></i> <?php echo $mensagem; ?>
            </div>
            <?php } ?>
            <form method="POST" action="">
                <h4>Dados do Cliente</h4>
                <div class="form-row">
                    <div class="form-group"><label for="orcClienteNome">Nome do Cliente</label><input type="text"
                            id="orcClienteNome" name="orcClienteNome" required></div>
                    <div class="form-group"><label for="orcClienteCPFPJ">CPF/CNPJ</label><input type="text"
                            id="orcClienteCPFPJ" name="orcClienteCPFPJ" required></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label for="orcClienteCEP">CEP</label><input type="text" id="orcClienteCEP"
                            name="orcClienteCEP" required></div>
                </div>
                <div class="form-group"><label for="orcClienteEnde">Endereço</label><input type="text"
                        id="orcClienteEnde" name="orcClienteEnde" required></div>
                <div class="form-row">
                    <div class="form-group"><label for="orcClienteBai">Bairro</label><input type="text"
                            id="orcClienteBai" name="orcClienteBai" required></div>
                    <div class="form-group"><label for="orcClienteCid">Cidade</label><input type="text"
                            id="orcClienteCid" name="orcClienteCid" required></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label for="orcClienteTEL">Telefone</label><input type="text"
                            id="orcClienteTEL" name="orcClienteTEL"></div>
                    <div class="form-group"><label for="orcClienteCEL">Celular</label><input type="text"
                            id="orcClienteCEL" name="orcClienteCEL"></div>
                </div>
                <div class="form-group"><label for="orcClienteEmail">E-mail</label><input type="email"
                        id="orcClienteEmail" name="orcClienteEmail" required></div>

                <h4>Dados do Produto</h4>
                <div class="form-row">
                    <div class="form-group"><label for="ProNome">Nome do Produto</label><input type="text" id="ProNome"
                            name="ProNome" required></div>
                    <div class="form-group"><label for="ProPreco">Preço (R$)</label><input type="text" id="ProPreco"
                            name="ProPreco" step="0.01" min="0" required></div>
                </div>
                <div class="form-group"><label for="ProDescricao">Descrição</label><textarea id="ProDescricao"
                        name="ProDescricao" rows="3" required></textarea></div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="itemTipo">Tipo</label>
                        <select id="itemTipo" name="itemTipo" required>
                            <option value="">Selecione o tipo</option>
                            <option value="Quarto">Quarto</option>
                            <option value="Escritório">Escritório</option>
                            <option value="Sala de Jantar">Sala de Jantar</option>
                            <option value="Sala de Estar">Sala de Estar</option>
                            <option value="Banheiro">Banheiro</option>
                            <option value="Varanda">Varanda</option>

                        </select>
                    </div>
                </div>
                <div class="form-group"><label for="ProMaterial">Material</label><input type="text" id="ProMaterial"
                        name="ProMaterial" required></div>
                <div class="form-group checkbox-group">
                    <label class="checkbox-label"><input type="checkbox" id="ProOnline" name="ProOnline"><span
                            class="checkmark"></span>Venda iniciada online</label>
                    <small>Marque se a venda foi iniciada através do site</small>
                </div>
                <div class="form-buttons">
                    <button type="button" id="VoltOrca" class="cancel-btn">Voltar</button>
                    <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Salvar</button>
                </div>
            </form>
        </main>



        <script src="../Js/script.js"></script>

</body>

</html>