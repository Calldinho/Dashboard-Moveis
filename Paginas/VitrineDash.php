 <?php

    session_start();
    if (!isset($_SESSION['usuario_logado'])) {
        header("Location: ../Login.php");
        exit();
    }

    require_once '../Configs/Conexao.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['vitrineNome'])) {

    $VitNome = trim($_POST['vitrineNome'] ?? '');
    $Vitdescricao = trim($_POST['vitrineDesc'] ?? '');
    $VitPreco = trim($_POST['vitrinePreco'] ?? '');
    $VitImagem = trim($_POST['vitrineImagem'] ?? '');

    // Verifica se o nome e preço foram preenchidos
    if (empty($VitNome) || empty($VitPreco)) {
        echo "<p style='color:red;'>⚠️ Nome e preço são obrigatórios.</p>";
        exit;
    }

    // Valida preço
    if (!is_numeric($VitPreco) || $VitPreco < 0) {
        echo "<p style='color:red;'>⚠️ Preço inválido.</p>";
        exit;
    }

    // Prepara e insere
    $sql = "INSERT INTO VitItens (VitNome, VitDescricao, VitPreco, VitImagem) VALUES (?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        die("Erro no prepare: " . $conexao->error);
    }

    $stmt->bind_param("ssds", $VitNome, $VitDescricao, $VitPreco, $VitImagem);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>✅ Item inserido com sucesso!</p>";
    } else {
        echo "<p style='color:red;'>❌ Erro ao inserir: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conexao->close();
    }
}

   
    ?>

 <!DOCTYPE html>
 <html lang="pt-BR">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Dashboard - Vitrine</title>
     <link rel="stylesheet" href="../Css/styles.css">
     <link rel="stylesheet" href="../Css/Vitrine.css">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
 </head>

 <body>
     <div id="dashboard" class="dashboard">
         <header class="dashboard-header">
             <h1><i class="fas fa-file-invoice-dollar"></i>Vitrine</h1>
             <button id="logoutBtn" class="logout-btn">
                 <i class="fas fa-sign-out-alt"></i>
                 Sair
             </button>
         </header>

         <main class="dashboard-content">
             <div class="section-header">
                 <h2>Controle de Vitrine</h2>
                 <div class="header-buttons">
                     <button id="novoItemBtnVitrine" class="novo-item-btn">
                         <i class="fas fa-plus"></i> Novo Item
                     </button>
                     <button id="backToDashboard" class="back-btn">
                         <i class="fas fa-arrow-left"></i> Voltar
                     </button>
                 </div>
             </div>

             <div class="vitrine-content">
                 <div class="sales-table-container" style="padding-top: 20px;">
                     <div class="vitrine-bg"></div>
                     <div class="vitrine-container">
                         <div class="vitrine-grid">
                            
                         </div>
                     </div>
                 </div>
             </div>


         </main>

         <div id="novoVitrineModal" class="modal hidden">
             <div class="modal-content">
                 <div class="modal-header">
                     <h3>Cadastro de Item na Vitrine</h3>
                 </div>
                 <!-- Formulário de Cadastro da Vitrine -->
                 <form id="formVitrine" class="novo-item-form" method="POST" action="">
                     <h4>Dados do Item</h4>
                     <div class="form-group">
                         <label for="vitrineNome">Nome do Item</label>
                         <input type="text" id="vitrineNome" name="vitrineNome" required>
                     </div>
                     <div class="form-group">
                         <label for="vitrineDesc">Descrição</label>
                         <textarea id="vitrineDesc" name="vitrineDesc" rows="3" required></textarea>
                     </div>
                     <div class="form-row">
                         <div class="form-group">
                             <label for="vitrinePreco">Preço (R$)</label>
                             <input type="number" id="vitrinePreco" name="vitrinePreco" step="0.01" min="0" required>
                         </div>
                         <div class="form-group">
                             <label for="vitrineImagem">URL da Imagem</label>
                             <input type="text" id="vitrineImagem" name="vitrineImagem"
                                 placeholder="https://exemplo.com/imagem.jpg">
                         </div>
                     </div>
                     <div class="form-buttons">
                         <button type="button" id="cancelarVitrineBtn" class="cancel-btn">Cancelar</button>
                         <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Salvar Item</button>
                     </div>
                 </form>
             </div>
         </div>

         <script src="../Js/script.js"></script>

 </body>

 </html>