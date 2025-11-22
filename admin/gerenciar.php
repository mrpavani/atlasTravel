<?php
require 'auth.php';
require '../db.php';
function checkDir($p)
{
    if (!is_dir($p)) mkdir($p, 0777, true);
}
checkDir('../uploads/bandeiras/');
checkDir('../uploads/turismo/');
$id = $_GET['id'] ?? null;
$dados = null;
$galeria_atual = [];

// Exclusão de Foto
if (isset($_GET['acao']) && $_GET['acao'] == 'deletar_foto') {
    $pdo->prepare("DELETE FROM galeria_turismo WHERE id=?")->execute([$_GET['id_foto']]);
    header("Location: gerenciar.php?id=" . $_GET['retorno']);
    exit;
}

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM paises WHERE id=?");
    $stmt->execute([$id]);
    $dados = $stmt->fetch();
    $stmt = $pdo->prepare("SELECT * FROM galeria_turismo WHERE pais_id=?");
    $stmt->execute([$id]);
    $galeria_atual = $stmt->fetchAll();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (Captura de variaveis igual ao anterior) ...
    $campos = [$_POST['nome'], $_POST['capital'], $_POST['continente'], $_POST['regime'], $_POST['presidente'], $_POST['moeda'], $_POST['codigo_moeda'], $_POST['idioma'], $_POST['historia'], $_POST['nome_pratos'], $_POST['clima'], $_POST['dicas_culturais']];
    if ($_POST['id']) {
        $sql = "UPDATE paises SET nome=?, capital=?, continente=?, regime_politico=?, presidente=?, moeda=?, codigo_moeda=?, idioma=?, historia=?, nome_pratos=?, clima=?, dicas_culturais=? WHERE id=?";
        $pdo->prepare($sql)->execute(array_merge($campos, [$_POST['id']]));
        $pais_id = $_POST['id'];
    } else {
        $sql = "INSERT INTO paises (nome, capital, continente, regime_politico, presidente, moeda, codigo_moeda, idioma, historia, nome_pratos, clima, dicas_culturais) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $pdo->prepare($sql)->execute($campos);
        $pais_id = $pdo->lastInsertId();
    }

    // Uploads
    if (!empty($_FILES['bandeira']['name'])) {
        $nm = uniqid() . "." . pathinfo($_FILES['bandeira']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['bandeira']['tmp_name'], "../uploads/bandeiras/$nm")) {
            $pdo->prepare("DELETE FROM bandeiras WHERE pais_id=?")->execute([$pais_id]);
            $pdo->prepare("INSERT INTO bandeiras (pais_id, nome_arquivo) VALUES (?,?)")->execute([$pais_id, $nm]);
        }
    }
    if (!empty($_FILES['fotos_turismo']['name'][0])) {
        foreach ($_FILES['fotos_turismo']['name'] as $k => $n) {
            if ($_FILES['fotos_turismo']['error'][$k] == 0) {
                $nm = uniqid() . "_pt." . pathinfo($n, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES['fotos_turismo']['tmp_name'][$k], "../uploads/turismo/$nm");
                $pdo->prepare("INSERT INTO galeria_turismo (pais_id, nome_arquivo) VALUES (?,?)")->execute([$pais_id, $nm]);
            }
        }
    }
    // Update Detalhes Fotos
    if (isset($_POST['detalhes_fotos'])) {
        foreach ($_POST['detalhes_fotos'] as $fid => $inf) {
            $pdo->prepare("UPDATE galeria_turismo SET nome_ponto=?, cidade_ponto=?, historia_ponto=?, como_chegar=?, horario=? WHERE id=?")
                ->execute([$inf['nome'], $inf['cidade'], $inf['historia'], $inf['chegar'], $inf['hora'], $fid]);
        }
    }
    header("Location: gerenciar.php?id=$pais_id&msg=ok");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Gerenciar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 pb-20">
    <nav class="bg-slate-800 text-white p-4 mb-6">
        <div class="container mx-auto"><a href="dashboard.php">↩ Voltar ao Painel</a></div>
    </nav>
    <main class="container mx-auto p-6 bg-white rounded shadow max-w-5xl">
        <h2 class="text-2xl font-bold mb-6"><?= $id ? 'Editar' : 'Novo' ?> País</h2>
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id" value="<?= $dados['id'] ?? '' ?>">

            <div class="grid grid-cols-3 gap-4">
                <input type="text" name="nome" value="<?= $dados['nome'] ?? '' ?>" placeholder="Nome" class="border p-2 rounded" required>
                <input type="text" name="capital" value="<?= $dados['capital'] ?? '' ?>" placeholder="Capital" class="border p-2 rounded">
                <select name="continente" class="border p-2 rounded">
                    <option value="Leste Europeu">Leste Europeu</option>
                    <option value="Europa Ocidental">Europa Ocidental</option>
                    <option value="Ásia">Ásia</option>
                    <option value="África">África</option>
                    <option value="América do Sul">América do Sul</option>
                </select>
            </div>
            <div class="grid grid-cols-4 gap-4">
                <input type="text" name="regime" value="<?= $dados['regime_politico'] ?? '' ?>" placeholder="Regime" class="border p-2 rounded">
                <input type="text" name="presidente" value="<?= $dados['presidente'] ?? '' ?>" placeholder="Presidente" class="border p-2 rounded">
                <input type="text" name="idioma" value="<?= $dados['idioma'] ?? '' ?>" placeholder="Idioma" class="border p-2 rounded">
                <input type="text" name="codigo_moeda" value="<?= $dados['codigo_moeda'] ?? '' ?>" placeholder="ISO Moeda" class="border p-2 rounded uppercase">
                <input type="hidden" name="moeda" value="<?= $dados['moeda'] ?? 'Moeda' ?>">
            </div>

            <textarea name="historia" rows="3" placeholder="História" class="w-full border p-2 rounded"><?= $dados['historia'] ?? '' ?></textarea>
            <div class="grid grid-cols-2 gap-4">
                <textarea name="clima" rows="3" placeholder="Clima" class="border p-2 rounded"><?= $dados['clima'] ?? '' ?></textarea>
                <textarea name="dicas_culturais" rows="3" placeholder="Dicas Culturais" class="border p-2 rounded"><?= $dados['dicas_culturais'] ?? '' ?></textarea>
            </div>

            <textarea name="nome_pratos" rows="2" placeholder="Gastronomia (Texto)" class="w-full border p-2 rounded"><?= $dados['nome_pratos'] ?? '' ?></textarea>

            <div class="border p-4 bg-gray-50"><label class="font-bold">Bandeira</label> <input type="file" name="bandeira"></div>

            <div class="border p-4 bg-gray-50">
                <h3 class="font-bold mb-2">Pontos Turísticos</h3>
                <input type="file" name="fotos_turismo[]" multiple class="mb-4">
                <?php if (count($galeria_atual) > 0): ?>
                    <?php foreach ($galeria_atual as $f): ?>
                        <div class="flex gap-4 border p-2 bg-white rounded items-start mb-2">
                            <div class="w-32 text-center">
                                <img src="../uploads/turismo/<?= $f['nome_arquivo'] ?>" class="h-20 w-full object-cover rounded">
                                <a href="?acao=deletar_foto&id_foto=<?= $f['id'] ?>&retorno=<?= $id ?>" onclick="return confirm('Apagar?')" class="text-red-600 text-xs font-bold block mt-1">Excluir</a>
                            </div>
                            <div class="grid grid-cols-2 gap-2 w-full">
                                <input type="text" name="detalhes_fotos[<?= $f['id'] ?>][nome]" value="<?= $f['nome_ponto'] ?>" placeholder="Nome" class="border p-1 rounded text-sm">
                                <input type="text" name="detalhes_fotos[<?= $f['id'] ?>][cidade]" value="<?= $f['cidade_ponto'] ?>" placeholder="Cidade" class="border p-1 rounded text-sm">
                                <textarea name="detalhes_fotos[<?= $f['id'] ?>][historia]" placeholder="Sobre" class="col-span-2 border p-1 rounded text-sm"><?= $f['historia_ponto'] ?></textarea>
                                <input type="text" name="detalhes_fotos[<?= $f['id'] ?>][chegar]" value="<?= $f['como_chegar'] ?>" placeholder="Chegar" class="border p-1 rounded text-sm">
                                <input type="text" name="detalhes_fotos[<?= $f['id'] ?>][hora]" value="<?= $f['horario'] ?>" placeholder="Horário" class="border p-1 rounded text-sm">
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button class="bg-blue-600 text-white px-6 py-3 rounded font-bold w-full">Salvar Tudo</button>
        </form>
    </main>
</body>

</html>