<?php
session_start();
require '../db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && $user['senha'] == $senha) { // Em produção use password_verify
        $_SESSION['admin_logado'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = "Dados inválidos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-900 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded w-96">
        <h1 class="text-2xl font-bold mb-4 text-center">🔒 Admin</h1>
        <?php if (isset($erro)) echo "<p class='text-red-500 text-sm mb-4'>$erro</p>"; ?>
        <form method="POST" class="space-y-4">
            <input type="email" name="email" placeholder="Email" class="w-full border p-2 rounded">
            <input type="password" name="senha" placeholder="Senha" class="w-full border p-2 rounded">
            <button class="w-full bg-blue-600 text-white py-2 rounded font-bold">Entrar</button>
        </form>
        <a href="../index.php" class="block text-center mt-4 text-sm text-gray-500">Voltar ao site</a>
    </div>
</body>

</html>