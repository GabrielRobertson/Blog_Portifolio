<?php
session_start();
require_once '../config/db.php';

if (isset($_POST['login'])) {
    $user = trim($_POST['user']);
    $pass = trim($_POST['pass']);

    // Buscar credenciais configuradas no banco de dados
    $stmt_u = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'admin_user'");
    $stmt_u->execute();
    $saved_user = $stmt_u->fetchColumn() ?: 'admin';

    $stmt_p = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'admin_password'");
    $stmt_p->execute();
    $saved_pass = $stmt_p->fetchColumn() ?: 'admin';

    // Verificar credenciais usando hash armazenado no banco (com fallback para senha legada)
    $pass_match = password_verify($pass, $saved_pass) || ($pass === $saved_pass);

    if ($user === $saved_user && $pass_match) {
        $_SESSION['logged_in'] = true;
        // Regenerar ID da sessão para evitar fixação de sessão
        session_regenerate_id(true);
        header("Location: index.php");
        exit;
    } else {
        $error = "Usuário ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-indigo-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Acesso Administrativo</h2>
        <?php if (isset($error)): ?>
            <p class="text-red-500 text-sm mb-4 text-center"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Usuário</label>
                <input type="text" name="user" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Senha</label>
                <input type="password" name="pass" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>
            <button type="submit" name="login" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition">Entrar</button>
        </form>
    </div>
</body>
</html>
