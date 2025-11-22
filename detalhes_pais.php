<?php
require 'db.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: destinos.php");
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM paises WHERE id = ?");
$stmt->execute([$id]);
$pais = $stmt->fetch();
if (!$pais) {
    header("Location: destinos.php");
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM bandeiras WHERE pais_id = ?");
$stmt->execute([$id]);
$bandeira = $stmt->fetch();
$stmt = $pdo->prepare("SELECT * FROM galeria_turismo WHERE pais_id = ?");
$stmt->execute([$id]);
$galeria = $stmt->fetchAll();
$titulo_pagina = htmlspecialchars($pais['nome'] ?? '') . " - Detalhes";
include 'includes/header.php';
?>
<?php include 'menu.php'; ?>

<div class="relative bg-slate-900 text-white py-16 shadow-xl">
    <div class="container mx-auto px-6 flex flex-col md:flex-row items-center gap-10 relative z-10">
        <div class="w-full md:w-1/3 flex justify-center md:justify-end">
            <?php if ($bandeira): ?>
                <img src="uploads/bandeiras/<?= htmlspecialchars($bandeira['nome_arquivo'] ?? '') ?>" class="max-w-sm rounded-xl shadow-2xl border-4 border-white/10">
            <?php endif; ?>
        </div>
        <div class="w-full md:w-2/3 text-center md:text-left">
            <span class="bg-emerald-500 text-xs font-bold px-3 py-1 rounded-full uppercase mb-4 inline-block"><?= htmlspecialchars($pais['continente'] ?? '') ?></span>
            <h1 class="text-6xl font-bold mb-4"><?= htmlspecialchars($pais['nome'] ?? '') ?></h1>
            <p class="text-xl text-blue-200">Capital: <span class="font-bold text-white"><?= htmlspecialchars($pais['capital'] ?? '') ?></span></p>
        </div>
    </div>
</div>

<main class="container mx-auto p-6 space-y-10 pb-20 -mt-8 relative z-20">
    <section class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div class="p-4 bg-slate-50 rounded">
                <p class="text-xs font-bold uppercase text-gray-400">Presidente</p>
                <p class="font-bold"><?= htmlspecialchars($pais['presidente'] ?? '') ?></p>
            </div>
            <div class="p-4 bg-slate-50 rounded">
                <p class="text-xs font-bold uppercase text-gray-400">Regime</p>
                <p class="font-bold"><?= htmlspecialchars($pais['regime_politico'] ?? '') ?></p>
            </div>
            <div class="p-4 bg-slate-50 rounded">
                <p class="text-xs font-bold uppercase text-gray-400">Idioma</p>
                <p class="font-bold"><?= htmlspecialchars($pais['idioma'] ?? '') ?></p>
            </div>
            <div class="p-4 bg-slate-50 rounded">
                <p class="text-xs font-bold uppercase text-gray-400">Moeda</p>
                <p class="font-bold text-emerald-600"><?= htmlspecialchars($pais['moeda'] ?? '') ?></p>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <h3 class="font-bold text-lg mb-2">📜 História</h3>
                <p class="text-gray-600 whitespace-pre-line text-justify"><?= htmlspecialchars($pais['historia'] ?? '') ?></p>
            </div>
            <div class="space-y-6">
                <div class="bg-blue-50 p-6 rounded-2xl">
                    <h3 class="font-bold text-blue-900">🌤️ Clima</h3>
                    <p class="text-blue-800 text-sm mt-2"><?= htmlspecialchars($pais['clima'] ?? '') ?></p>
                </div>
                <div class="bg-purple-50 p-6 rounded-2xl">
                    <h3 class="font-bold text-purple-900">🎭 Dicas Culturais</h3>
                    <p class="text-purple-800 text-sm mt-2"><?= htmlspecialchars($pais['dicas_culturais'] ?? '') ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white p-8 rounded-3xl shadow-lg border-l-8 border-orange-400">
        <h2 class="text-2xl font-bold text-orange-600 mb-4">🍽️ Gastronomia Típica</h2>
        <p class="text-gray-700 whitespace-pre-line text-lg"><?= htmlspecialchars($pais['nome_pratos'] ?? '') ?></p>
    </section>

    <?php if (count($galeria) > 0): ?>
        <section>
            <h2 class="text-2xl font-bold mb-6">✈️ Pontos Turísticos</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($galeria as $ponto): ?>
                    <div onclick="abrirModal('modal_<?= $ponto['id'] ?>')" class="cursor-pointer bg-white rounded-xl shadow overflow-hidden group relative h-64">
                        <img src="uploads/turismo/<?= htmlspecialchars($ponto['nome_arquivo'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute bottom-0 inset-x-0 bg-black/80 p-4">
                            <p class="text-white font-bold"><?= htmlspecialchars($ponto['nome_ponto'] ?? '') ?></p>
                        </div>
                    </div>
                    <div id="modal_<?= $ponto['id'] ?>" class="hidden fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm">
                        <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] flex flex-col md:flex-row relative overflow-hidden">
                            <button onclick="fecharModal('modal_<?= $ponto['id'] ?>')" class="absolute top-4 right-4 bg-gray-100 rounded-full w-8 h-8 font-bold z-10">✕</button>
                            <img src="uploads/turismo/<?= htmlspecialchars($ponto['nome_arquivo'] ?? '') ?>" class="w-full md:w-1/2 h-64 md:h-auto object-cover">
                            <div class="p-8 overflow-y-auto w-full no-scrollbar">
                                <h3 class="text-2xl font-bold mb-4"><?= htmlspecialchars($ponto['nome_ponto'] ?? '') ?></h3>
                                <p class="whitespace-pre-line text-gray-600 mb-6"><?= htmlspecialchars($ponto['historia_ponto'] ?? 'Sem descrição') ?></p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-100 p-3 rounded"><strong class="text-xs uppercase">Como Chegar</strong><br><span class="text-sm"><?= htmlspecialchars($ponto['como_chegar'] ?? '') ?></span></div>
                                    <div class="bg-gray-100 p-3 rounded"><strong class="text-xs uppercase">Horários</strong><br><span class="text-sm"><?= htmlspecialchars($ponto['horario'] ?? '') ?></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php include 'includes/footer.php'; ?>