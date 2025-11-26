<?php
    session_start();
    if (!isset($_SESSION['usuario_logado'])) {
        header("Location: ../Login.php");
        exit();
    }
    
    require_once '../Configs/Conexao.php';

    $id = intval($_SESSION['usuario_id']);
    $admNomeAtual = 'Não Identificado';
    $ultimaModificacao = 'Não Identificado';

    $sqlNome = "SELECT AdmNome, UltimaModificacao FROM AdmUsuario WHERE AdmId = ?";
    $stmtNome = $conexao->prepare($sqlNome);

    if ($stmtNome) {
    $stmtNome->bind_param("i", $id);
    $stmtNome->execute();
    $resultNome = $stmtNome->get_result();
    
    if ($resultNome->num_rows > 0) {
        $linhaNome = $resultNome->fetch_assoc();
        $admNomeAtual = htmlspecialchars($linhaNome['AdmNome']); // Armazena o nome
        if ($linhaNome['UltimaModificacao']) {
                // Formata a data para um formato amigável (ex: 10/11/2025 às 15:30)
                $data = new DateTime($linhaNome['UltimaModificacao']);
                $ultimaModificacao = $data->format('d/m/Y');
            }
    }
    $stmtNome->close();
    }

    if (isset($_POST['savebtn'])) {        
    $AdmEmail = trim($_POST['Nemail'] ?? '');
    $AdmNovoUser = trim($_POST['Nuser'] ?? '');
    $AdmNovaSenha = trim($_POST['Nsenha'] ?? '');
    $AdmNovaSenhaC = trim($_POST['NsenhaC'] ?? '');
    $AdmAntigaSenha = trim($_POST['Asenha'] ?? '');

    $sqlCheck = "SELECT AdmSenha FROM AdmUsuario WHERE AdmId = ?";
    $stmtCheck = $conexao->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $usuario = $result->fetch_assoc();

    if (!$usuario) {
        echo "Usuário não encontrado!";
        exit;
    }

    if (!password_verify($AdmAntigaSenha, $usuario['AdmSenha'])) {
        echo "Senha atual incorreta!";
        exit;
    }

    if (!empty($AdmNovaSenha) && $AdmNovaSenha !== $AdmNovaSenhaC) {
        echo "As novas senhas não coincidem!";
        exit;
    }

    if (!empty($AdmNovaSenha)) {
        $novaSenhaHash = password_hash($AdmNovaSenha, PASSWORD_DEFAULT);
    } else {
        $novaSenhaHash = $usuario['AdmSenha'];
    }

    $sqlUpdate = "UPDATE AdmUsuario 
                  SET AdmEmail = ?, AdmNome = ?, AdmSenha = ?, UltimaModificacao = NOW() 
                  WHERE AdmId = ?";
    $stmtUpdate = $conexao->prepare($sqlUpdate);
    $stmtUpdate->bind_param("sssi", $AdmEmail, $AdmNovoUser, $novaSenhaHash, $id);

    if ($stmtUpdate->execute()) {
        echo "Configurações atualizadas com sucesso!";
        session_destroy();        
        header("Location: ../Login.php");
        exit();
    } else {
        echo "Erro ao atualizar: " . $conexao->error;
    }

    $stmtCheck->close();
    $stmtUpdate->close();
}


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Configurações </title>
    <link rel="stylesheet" href="../Css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div id="dashboard" class="dashboard">
        <header class="dashboard-header">
            <h1><i class="fas fa-cog"></i>Configurações</h1>
            <button id="logoutBtn" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Sair
            </button>
        </header>

        <!-- Conteúdo Principal do Dashboard -->
        <main class="dashboard-content">

            <div id="configuracoesSection" class="configuracoes-section">
                <div class="section-header">
                    <h2><i class="fas fa-cog"></i> Configurações do Sistema</h2>
                    <button id="backToDashboard" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </button>
                </div>

                <div class="config-container">
                    <!-- Formulário de Configurações -->
                    <div class="config-card">
                        <div class="config-header">
                            <h3><i class="fas fa-user-shield"></i> Configurações do Administrador</h3>
                            <p>Altere suas credenciais de acesso ao sistema</p>
                        </div>

                        <form id="configForm" class="config-form" method="POST">
                            <div class="form-section">
                                <h4>Dados Atuais</h4>
                                <div class="current-info">
                                    <div class="info-item">
                                        <span class="label">Usuário Atual:</span>
                                        <span class="value"
                                            id="currentUsername"><?php echo $admNomeAtual; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Última Modificação:</span>
                                        <span class="value"><?php echo $ultimaModificacao; ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h4>Alterar Credenciais</h4>
                                <div class="form-group">
                                    <label for="newEmail">Novo Email</label>
                                    <input type="email" id="newEmail" name="Nemail" required>
                                    <small>Digite o Novo Email </small>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="newUsername">Novo Nome de Usuário</label>
                                        <input type="text" id="newUsername" name="Nuser" required minlength="3">
                                        <small>Mínimo 3 caracteres</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="currentPassword">Senha Atual</label>
                                        <input type="password" id="currentPassword" name="Asenha" required>
                                        <small>Digite sua senha atual para confirmar</small>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="newPassword">Nova Senha</label>
                                        <input type="password" id="newPassword" name="Nsenha" minlength="6">
                                        <small>Mínimo 6 caracteres</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword">Confirmar Nova Senha</label>
                                        <input type="password" id="confirmPassword" name="NsenhaC">
                                        <small>Digite a nova senha novamente</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-buttons">
                                <button type="button" id="resetConfigBtn" class="reset-btn"><i class="fas fa-undo"></i>
                                    Resetar</button>
                                <button type="submit" class="save-config-btn" name="savebtn"><i class="fas fa-save"></i> Salvar
                                    Alterações</button>
                            </div>
                        </form>
                    </div>

                    <!-- Dicas de Segurança e Histórico -->
                    <div class="security-info">
                        <div class="info-card">
                            <h4><i class="fas fa-shield-alt"></i> Dicas de Segurança</h4>
                            <ul>
                                <li>Use senhas com pelo menos 8 caracteres</li>
                                <li>Combine letras, números e símbolos</li>
                                <li>Não compartilhe suas credenciais</li>
                                <li>Altere sua senha regularmente</li>
                            </ul>
                        </div>

                        <div class="info-card">
                            <h4><i class="fas fa-history"></i> Histórico de Alterações</h4>
                            <div class="history-item"><span class="date">13/09/2025 - 14:30</span><span
                                    class="action">Senha alterada</span></div>
                            <div class="history-item"><span class="date">10/09/2025 - 09:15</span><span
                                    class="action">Login alterado</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>



    <script src="../Js/script.js"></script>

</body>