 <?php
    require_once '../Configs/Conexao.php';
    // Lógica para deletar um móvel

    if (isset($_POST['delete_item'])) {
        $id = intval($_POST['delete_item']);
        $conexao->query("UPDATE OrcProdutos SET OrcStatus = 'Desacordo' WHERE OrcId = $id");
    }

    if (isset($_POST['confirm_item'])) {
        $id = intval($_POST['confirm_item']);
        $conexao->query("UPDATE OrcProdutos SET OrcStatus = 'Entregue', OrcDataEntrega = CURRENT_DATE WHERE OrcId = $id");
    }
                  
    $sql = "SELECT OrcId, OrcNome, OrcDataVenda, OrcPreco, CliNome FROM OrcProdutos JOIN CliClientes ON OrcCliente = CliId WHERE OrcStatus IN ('Venda', 'Entregue')";
    $result = $conexao->query($sql);
    ?>

 <!DOCTYPE html>
 <html lang="pt-BR">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Dashboard - Venda</title>
     <link rel="stylesheet" href="../Css/styles.css">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
 </head>

 <body>
     <div id="dashboard" class="dashboard">
         <header class="dashboard-header">
             <h1><i class="fas fa-shopping-cart"></i>Vendas</h1>
             <button id="logoutBtn" class="logout-btn">
                 <i class="fas fa-sign-out-alt"></i>
                 Sair
             </button>
         </header>

         <main class="dashboard-content">
             <div class="section-header">
                 <h2>Gerenciamento de Vendas</h2>
                 <div class="header-buttons">
                     <button id="backToDashboard" class="back-btn">
                         <i class="fas fa-arrow-left"></i> Voltar
                     </button>
                 </div>
             </div>

             <!-- Filtros da Tabela de Vendas -->
             <div class="filters-container">
                 <div class="filter-group">
                     <label for="canalFilter">Canal:</label>
                     <select id="canalFilter" class="filter-select">
                         <option value="todos">Todos</option>
                         <option value="online">Online</option>
                         <option value="presencial">Presencial</option>
                     </select>
                 </div>

                 <div class="filter-group">
                     <label for="ordenarPor">Ordenar por:</label>
                     <select id="ordenarPor" class="filter-select">
                         <option value="padrao">Padrão</option>
                         <option value="nome-asc">Nome (A-Z)</option>
                         <option value="nome-desc">Nome (Z-A)</option>
                         <option value="preco-asc">Preço (Menor-Maior)</option>
                         <option value="preco-desc">Preço (Maior-Menor)</option>
                     </select>
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
                                 <th>Produto</th>
                                 <th>Preço</th>
                                 <th>Data de Venda</th>
                                 <th>Cliente</th>
                                 <th>Ações</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php if ($result && $result->num_rows > 0): ?>
                             <?php while($row = $result->fetch_assoc()): ?>
                             <tr class="sale-row" data-sale-id="<?php echo $row['OrcId']; ?>">
                                 <td><?php echo htmlspecialchars($row['OrcNome']); ?></td>
                                 <td>R$ <?php echo number_format($row['OrcPreco'], 2, ',', '.'); ?></td>
                                 <td><?php echo date('d/m/Y', strtotime($row['OrcDataVenda'])); ?></td>
                                 <td><?php echo htmlspecialchars($row['CliNome']); ?></td>
                                 <td>
                                     <!-- Formulário para apagar o item -->
                                     <form method="post"
                                         onsubmit="return confirm('Tem certeza que deseja confirmar este orçamento?');"
                                         style="display:inline;">
                                         <input type="hidden" name="confirm_item" value="<?php echo $row['OrcId']; ?>">
                                         <button type="submit" class="confirm-btn"
                                             style="background:#16a34a;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;">
                                             <i class="fas fa-check"></i>
                                         </button>
                                     </form>
                                     <form method="post"
                                         onsubmit="return confirm('Tem certeza que deseja deletar este orçamento?');"
                                         style="display:inline;">
                                         <input type="hidden" name="delete_item" value="<?php echo $row['OrcId']; ?>">
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