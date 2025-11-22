<?php
require 'db.php';
$cat = $_GET['cat'] ?? 'todos';

$sql = "SELECT p.*, b.nome_arquivo as bandeira FROM paises p LEFT JOIN bandeiras b ON p.id = b.pais_id";

// Lógica de Filtro Aprimorada
if ($cat !== 'todos') {
    if ($cat === 'Europa') {
        $sql .= " WHERE p.continente IN ('Leste Europeu', 'Europa Ocidental')";
    } elseif ($cat === 'Américas') {
        $sql .= " WHERE p.continente IN ('América do Sul', 'América do Norte', 'América Central')";
    } else {
        $sql .= " WHERE p.continente = :cat";
    }
}
$sql .= " ORDER BY p.nome ASC";

try {
    $stmt = $pdo->prepare($sql);
    if ($cat !== 'todos' && $cat !== 'Europa' && $cat !== 'Américas') {
        $stmt->execute(['cat' => $cat]);
    } else {
        $stmt->execute();
    }
    $paises = $stmt->fetchAll();
} catch (PDOException $e) {
    die($e->getMessage());
}

$titulo_pagina = "Destinos - AtlasTravel";
include 'includes/header.php';
?>
<?php include 'menu.php'; ?>

<div class="bg-slate-900 text-white py-12 shadow-md">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl font-bold font-serif mb-2">Explorar Destinos</h1>
        <p class="text-slate-400">Filtro Atual: <span class="text-emerald-400 font-bold"><?= ucfirst($cat) ?></span></p>
    </div>
</div>

<div class="border-b bg-white sticky top-0 z-40 shadow-sm overflow-x-auto">
    <div class="container mx-auto px-6 py-4 flex gap-3 whitespace-nowrap justify-start md:justify-center">
        <a href="destinos.php?cat=todos" class="px-4 py-2 rounded-full text-sm font-bold transition <?= $cat == 'todos' ? 'bg-slate-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">Todos</a>
        <a href="destinos.php?cat=Europa" class="px-4 py-2 rounded-full text-sm font-bold transition <?= $cat == 'Europa' ? 'bg-slate-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">Europa</a>
        <a href="destinos.php?cat=Américas" class="px-4 py-2 rounded-full text-sm font-bold transition <?= $cat == 'Américas' ? 'bg-slate-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">Américas (Geral)</a>
        <a href="destinos.php?cat=América do Norte" class="px-4 py-2 rounded-full text-sm font-bold transition <?= $cat == 'América do Norte' ? 'bg-slate-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">Am. Norte</a>
        <a href="destinos.php?cat=América do Sul" class="px-4 py-2 rounded-full text-sm font-bold transition <?= $cat == 'América do Sul' ? 'bg-slate-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">Am. Sul</a>
        <a href="destinos.php?cat=Ásia" class="px-4 py-2 rounded-full text-sm font-bold transition <?= $cat == 'Ásia' ? 'bg-slate-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">Ásia</a>
        <a href="destinos.php?cat=África" class="px-4 py-2 rounded-full text-sm font-bold transition <?= $cat == 'África' ? 'bg-slate-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">África</a>
    </div>
</div>

<main class="container mx-auto p-6 py-12 flex-grow">
    <div class="flex justify-end mb-8">
        <a href="admin/gerenciar.php" class="inline-flex items-center gap-2 bg-emerald-500 text-white px-5 py-2 rounded-lg font-bold hover:bg-emerald-400 transition shadow-lg hover:-translate-y-1">
            <span>➕</span> Cadastrar Novo Destino
        </a>
    </div>

    <?php if (count($paises) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php foreach ($paises as $pais): ?>
                <a href="detalhes_pais.php?id=<?= $pais['id'] ?>" class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col border border-gray-100 relative block h-full">
                    <span class="absolute top-3 left-3 bg-black/60 backdrop-blur text-white text-[10px] font-bold px-2 py-1 rounded uppercase z-10 border border-white/20">
                        <?= htmlspecialchars($pais['continente'] ?? '') ?>
                    </span>
                    <div class="h-56 w-full bg-slate-200 overflow-hidden relative">
                        <?php if (!empty($pais['bandeira'])): ?>
                            <img src="uploads/bandeiras/<?= htmlspecialchars($pais['bandeira']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <?php else: ?>
                            <div class="flex items-center justify-center h-full text-gray-400">Sem Foto</div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition"></div>
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-emerald-600 transition mb-1"><?= htmlspecialchars($pais['nome'] ?? '') ?></h3>
                        <p class="text-sm text-gray-500 flex items-center gap-1 mb-4">🏛️ <?= htmlspecialchars($pais['capital'] ?? '') ?></p>

                        <div class="mt-auto pt-3 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ver Guia</span>
                            <span class="text-emerald-500 font-bold text-lg">→</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-24 bg-white rounded-3xl border-2 border-dashed border-gray-200">
            <p class="text-gray-400 text-xl">Nenhum destino encontrado nesta região.</p>
            <a href="admin/gerenciar.php" class="text-emerald-600 font-bold mt-4 inline-block hover:underline">Adicionar o primeiro?</a>
        </div>
    <?php endif; ?>
</main>
<?php include 'includes/footer.php'; ?>