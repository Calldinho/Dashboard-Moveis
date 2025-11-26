<?php

    session_start(); 
    if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../Login.php");
    exit();
}

    require_once '../Configs/Conexao.php';
    // Lógica para deletar um móvel

    if (isset($_POST['delete_item'])) {
        $id = intval($_POST['delete_item']);
        $conexao->query("UPDATE OrcProdutos SET OrcStatus = 'Desacordo' WHERE OrcId = $id");
    }

           
    if (isset($_POST['busca-btn'])) {
        $campPesquisa = $_POST['busca-item'] ?? '';

        $sql = "SELECT OrcId, OrcNome, OrcDescricao, OrcDataInicio, OrcPreco, OrcTipo,
                   CliNome, CliEndereco, CliCidade, CliEmail, CliTelefone, CliCelular
            FROM OrcProdutos
            JOIN CliClientes ON OrcCliente = CliId
            WHERE (OrcNome LIKE ? OR CliNome LIKE ?)
            AND OrcStatus = 'Orçamento'";

        $stmt = $conexao->prepare($sql);
        $like = "%$campPesquisa%";
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
        
    }

    if (!isset($_POST['busca-btn'])) {
    $sql = "SELECT CliId, CliNome, CliEndereco, CliCidade, CliEmail, CliTelefone, CliCelular FROM CliClientes";
    $result = $conexao->query($sql); 
       
    }
    ?>

 <!DOCTYPE html>
 <html lang="pt-BR">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Dashboard - Cliente</title>
     <link rel="stylesheet" href="../Css/styles.css">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
 </head>

 <body>
     <div id="dashboard" class="dashboard">
         <header class="dashboard-header">
             <h1><i class="fas fa-boxes"></i>Cliente</h1>
             <button id="logoutBtn" class="logout-btn">
                 <i class="fas fa-sign-out-alt"></i>
                 Sair
             </button>
         </header>

         <main class="dashboard-content">
             <div class="section-header">
                 <h2>Controle de clientes</h2>
                 <div class="header-buttons">
                     <button id="backToDashboard" class="back-btn">
                         <i class="fas fa-arrow-left"></i> Voltar
                     </button>
                 </div>
             </div>

             <!-- Filtros da Tabela de Vendas -->
             <div class="filters-container">
                 <div class="filter-group">
                     <form method="POST">
                         <label>Buscar:</label>
                         <input type="text" name="busca-item" class="filter-select" placeholder="Pesquisar">
                         <button type="submit" class="busca-btn" name="busca-btn"
                             style="background:#FFFFFF;color:#000000;border:2px solid #e1e5e9;padding:11px 13px;border-radius:10px;cursor:pointer;">
                             <i class="fas fa-search"></i>
                         </button>
                     </form>
                 </div>

                 <div class="filter-group">
                     <form method="POST">
                         <label for="data-filtro">Data:</label>
                         <input type="date" name="buscadata-item" class="filter-select">
                         <button type="submit" class="buscadata-btn" name="busca-btn"
                             style="background:#FFFFFF;color:#000000;border:2px solid #e1e5e9;padding:11px 13px;border-radius:10px;cursor:pointer;">
                             <i class="fas fa-search"></i>
                         </button>
                     </form>
                 </div>

                 <button id="limparFiltros" class="clear-filters-btn">
                     <i class="fas fa-times"></i> Limpar Filtros
                 </button>
             </div>

             <div class="vitrine-content">

                 <!-- Conteúdo da vitrine será adicionado aqui -->
                 <div class="sales-table-container">

                     <table class="sales-table">
                         <thead>
                             <tr>
                                 <th>Nome</th>
                                 <th>Telefone</th>
                                 <th>Celular</th>
                                 <th>Email</th>
                                 <th>Ações</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php if ($result && $result->num_rows > 0): ?>
                             <?php while($row = $result->fetch_assoc()): ?>
                             <tr class="sale-row" data-sale-id="<?php echo $row['CliId']; ?>">
                                 <td><?php echo htmlspecialchars($row['CliNome']); ?></td>
                                 <td><?php echo htmlspecialchars($row['CliTelefone']); ?></td>
                                 <td><?php echo htmlspecialchars($row['CliCelular']); ?></td>
                                 <td><?php echo htmlspecialchars($row['CliEmail']); ?></td>                                 
                                 <td>
                                     <!-- Formulário para apagar o item -->                                     
                                     <form method="post"
                                         onsubmit="return confirm('Tem certeza que deseja deletar este orçamento?');"
                                         style="display:inline;">
                                         <input type="hidden" name="delete_item" value="<?php echo $row['CliId']; ?>">
                                         <button type="submit" class="delete-btn"
                                             style="background:#dc2626;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;">
                                             <i class="fas fa-trash"></i>
                                         </button>
                                     </form>
                                     <button type="button" class="expan-btn"
                                         style="background:#6b7280;color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;"
                                         onclick="toggleDetalhes(this)">
                                         <i class="fas fa-eye"></i>
                                     </button>
                                 </td>
                             </tr>
                             <tr class="detalhes" style="display:none;">
                                 <td colspan="5">
                                     <div class="menu">
                                         <h4>Dados do Produto</h4>
                                         <br>
                                         <div class="info form-row">
                                             <div class="form-group"><label for="OrcDescricao">Descrição:</label><input
                                                     type="text"
                                                     value="<?php echo htmlspecialchars($row['OrcDescricao'] ?? 'Não informado'); ?>"
                                                     disabled></div>
                                             <div class="form-group"><label for="OrcDescricao">Tipo:</label><input
                                                     type="text"
                                                     value="<?php echo htmlspecialchars($row['OrcTipo'] ?? 'Não informado'); ?>"
                                                     disabled></div>
                                         </div>

                                         <h4>Dados do Cliente</h4>
                                         <br>
                                         <div class="info form-row">
                                             <div class="form-group"><label for="OrcDescricao">Email:</label><input
                                                     type="text"
                                                     value="<?php echo htmlspecialchars($row['CliEmail'] ?? 'Não informado'); ?>"
                                                     disabled></div>
                                         </div>
                                         <div class="info form-row">
                                             <div class="form-group"><label for="OrcDescricao">Telefone:</label><input
                                                     type="text"
                                                     value="<?php echo htmlspecialchars($row['CliEndereco'] ?? 'Não informado'); ?>"
                                                     disabled></div>
                                             <div class="form-group"><label for="OrcDescricao">Celular:</label><input
                                                     type="text"
                                                     value="<?php echo htmlspecialchars($row['CliCidade'] ?? 'Não informado'); ?>"
                                                     disabled></div>
                                         </div>
                                         <div class="info form-row">
                                             <div class="form-group"><label for="OrcDescricao">Telefone:</label><input
                                                     type="text"
                                                     value="<?php echo htmlspecialchars($row['CliTelefone'] ?? 'Não informado'); ?>"
                                                     disabled></div>
                                             <div class="form-group"><label for="OrcDescricao">Celular:</label><input
                                                     type="text"
                                                     value="<?php echo htmlspecialchars($row['CliCelular'] ?? 'Não informado'); ?>"
                                                     disabled></div>
                                         </div>
                                         <button type="submit" class="check-btn"
                                             style="background:#16a34a;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;float:right;margin-left:10px;"
                                             disabled>
                                             <i class="fas fa-check"></i>
                                         </button>
                                         <button type="button" class="edite-btn" onclick="toggleEdit(this)"
                                             style="background:#7f7f7f;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;float:right;">
                                             <i class="fas fa-pencil"></i>
                                         </button>
                                     </div>
                                 </td>
                             </tr>
                             <?php endwhile; ?>
                             <?php else: ?>
                             <tr>
                                 <td colspan="6">Nenhum item cadastrado.</td>
                             </tr>
                             <?php endif; ?>
                         </tbody>
                     </table>
                 </div>


             </div>
     </div>
     </main>



     <script src="../Js/script.js"></script>

 </body>

 </html>