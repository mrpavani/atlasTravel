<?php
require 'auth.php';
require '../db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo->prepare("UPDATE configuracoes SET valor=? WHERE chave='instagram'")->execute([$_POST['instagram']]);
    $pdo->prepare("UPDATE configuracoes SET valor=? WHERE chave='facebook'")->execute([$_POST['facebook']]);
    $msg = "Salvo!";
}
$configs = $pdo->query("SELECT * FROM configuracoes")->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Config</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">
    <div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Redes Sociais</h2>
        <?php if (isset($msg)) echo "<p class='text-green-600 mb-4'>$msg</p>"; ?>
        <form method="POST" class="space-y-4">
            <input type="text" name="instagram" value="<?= $configs['instagram'] ?? '' ?>" placeholder="URL Instagram" class="w-full border p-2 rounded">
            <input type="text" name="facebook" value="<?= $configs['facebook'] ?? '' ?>" placeholder="URL Facebook" class="w-full border p-2 rounded">
            <button class="w-full bg-blue-600 text-white py-2 rounded font-bold">Salvar</button>
        </form>
        <a href="dashboard.php" class="block text-center mt-4 text-gray-500">Voltar</a>
    </div>
</body>

</html>