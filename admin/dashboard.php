<?php
require 'auth.php';
require '../db.php';
$stmt = $pdo->query("SELECT id, nome FROM paises ORDER BY nome ASC");
$paises = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-slate-800 text-white p-4 mb-8">
        <div class="container mx-auto flex justify-between">
            <span class="font-bold">🛠️ Painel Admin</span>
            <div class="space-x-4"><a href="config.php">Configurar Redes</a> <a href="../index.php" target="_blank">Ver Site</a> <a href="logout.php" class="text-red-300">Sair</a></div>
        </div>
    </nav>
    <main class="container mx-auto p-6 bg-white rounded shadow max-w-4xl">
        <div class="flex justify-between mb-6">
            <h2 class="text-2xl font-bold">Países</h2><a href="gerenciar.php" class="bg-green-600 text-white px-4 py-2 rounded font-bold">+ Novo</a>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="p-2">Nome</th>
                    <th class="p-2 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paises as $p): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2"><?= htmlspecialchars($p['nome']) ?></td>
                        <td class="p-2 text-right"><a href="gerenciar.php?id=<?= $p['id'] ?>" class="text-blue-600 mr-4">Editar</a><a href="gerenciar.php?acao=excluir&id_pais=<?= $p['id'] ?>" onclick="return confirm('Excluir?')" class="text-red-600">Excluir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>

</html>