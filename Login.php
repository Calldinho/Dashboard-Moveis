<?php                    

session_start(); 

require_once './Configs/Conexao.php';
$mensagem_erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $usuario = $conexao->real_escape_string($_POST['username']);
    $senha_digitada = $_POST['password'];

    //Consulta
    $sql = "SELECT AdmId, AdmSenha FROM Admusuario WHERE AdmNome = '$usuario'";
    
    $resultado = $conexao->query($sql);

    // Autenticação
    if ($resultado->num_rows > 0) {
        $linha = $resultado->fetch_assoc();
        $senha_hash_banco = $linha['AdmSenha'];
        
        
        if (password_verify($senha_digitada, $senha_hash_banco)) {           
                     
            $_SESSION['usuario_logado'] = $usuario;
            $_SESSION['usuario_id'] = $linha['AdmId'];
		    header("Location: ./Paginas/Dashboard.php");
		    exit();           
            
        } else {
           
            $mensagem_erro = "<p style='color: red; font-weight: bold;'> Senha incorreta. Tente novamente.</p>";
        }
        
    } else {
    
        $mensagem_erro = "<p style='color: red; font-weight: bold;'> Usuário não encontrado.</p>";
    }
}

$conexao->close();

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Autenticação</title>
    <link rel="stylesheet" href="./Css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <i class="fas fa-lock"></i>
                <h1>Login</h1>
                <p>Entre com suas credenciais</p>
            </div>

            <form id="loginForm" class="login-form" method="POST">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i>
                        Usuário
                    </label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-key"></i>
                        Senha
                    </label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-options">
                    <a href="#" class="forgot-password">Esqueceu a senha?</a>
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Entrar
                </button>

                <div id="errorMessage" class="error-message"></div>
                <?php if (!empty($mensagem_erro)): ?>
                <div class="error-message show"><?php echo $mensagem_erro; ?></div>
                <?php endif; ?>
            </form>

        </div>
    </div>

    <script src="./Js/script.js"></script>

</body>

</html>