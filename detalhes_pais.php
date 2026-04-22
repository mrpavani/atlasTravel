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

<?php
$residenciaBg = $bandeira ? "uploads/bandeiras/" . htmlspecialchars($bandeira['nome_arquivo']) : "";
?>
<div class="relative bg-slate-900 text-white py-24 shadow-xl bg-cover bg-center" style="<?= $residenciaBg ? "background-image: url('$residenciaBg');" : "" ?>">
    <?php if($residenciaBg): ?><div class="absolute inset-0 bg-black/60 z-0"></div><?php endif; ?>
    <div class="container mx-auto px-6 flex flex-col items-center gap-6 relative z-10">
        <div class="text-center">
            <span class="bg-emerald-500 text-sm md:text-base font-bold px-5 py-2 mt-4 rounded-full uppercase mb-6 inline-block"><?= htmlspecialchars($pais['continente'] ?? '') ?></span>
            <h1 class="text-6xl md:text-8xl font-black mb-4 drop-shadow-lg"><?= htmlspecialchars($pais['nome'] ?? '') ?></h1>
            <p class="text-2xl text-blue-100 drop-shadow">Capital: <span class="font-bold text-white"><?= htmlspecialchars($pais['capital'] ?? '') ?></span></p>
        </div>
    </div>
</div>

<main class="container mx-auto p-6 space-y-10 pb-20 -mt-10 relative z-20">
    <section class="bg-white p-8 md:p-12 rounded-3xl shadow-2xl border border-gray-100">
        
        <!-- Cards de Info Rápidas -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
            <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition text-center md:text-left">
                <div class="text-4xl mb-3">👤</div>
                <p class="text-sm font-bold uppercase text-gray-500 tracking-wider">Presidente</p>
                <p class="text-xl font-bold text-slate-800 mt-1"><?= htmlspecialchars($pais['presidente'] ?? '') ?></p>
            </div>
            <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition text-center md:text-left">
                <div class="text-4xl mb-3">🏛️</div>
                <p class="text-sm font-bold uppercase text-gray-500 tracking-wider">Regime</p>
                <p class="text-xl font-bold text-slate-800 mt-1"><?= htmlspecialchars($pais['regime_politico'] ?? '') ?></p>
            </div>
            <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition text-center md:text-left">
                <div class="text-4xl mb-3">🗣️</div>
                <p class="text-sm font-bold uppercase text-gray-500 tracking-wider">Idioma</p>
                <p class="text-xl font-bold text-slate-800 mt-1"><?= htmlspecialchars($pais['idioma'] ?? '') ?></p>
            </div>
            <div class="p-6 bg-emerald-50 border border-emerald-100 rounded-2xl shadow-sm hover:shadow-md transition text-center md:text-left">
                <div class="text-4xl mb-3">💰</div>
                <p class="text-sm font-bold uppercase text-emerald-600 tracking-wider">Moeda</p>
                <p class="text-xl font-bold text-emerald-800 mt-1"><?= htmlspecialchars($pais['moeda'] ?? '') ?></p>
            </div>
        </div>

        <!-- TABS NAV -->
        <div class="flex flex-wrap gap-4 border-b-2 border-gray-100 pb-4 mb-10 overflow-x-auto whitespace-nowrap">
            <button onclick="switchTab('visao-geral')" id="btn-visao-geral" class="tab-btn outline-none font-bold text-xl text-emerald-600 border-b-4 border-emerald-600 pb-4 px-2 -mb-[18px] transition">Visão Geral</button>
            <button onclick="switchTab('cultura')" id="btn-cultura" class="tab-btn outline-none font-bold text-xl text-gray-400 hover:text-emerald-500 pb-4 px-2 -mb-[18px] transition">Cultura & Clima</button>
            <button onclick="switchTab('gastronomia')" id="btn-gastronomia" class="tab-btn outline-none font-bold text-xl text-gray-400 hover:text-emerald-500 pb-4 px-2 -mb-[18px] transition">Gastronomia</button>
            <?php if (count($galeria) > 0): ?>
                <button onclick="switchTab('turismo')" id="btn-turismo" class="tab-btn outline-none font-bold text-xl text-gray-400 hover:text-emerald-500 pb-4 px-2 -mb-[18px] transition">Turismo (Galeria)</button>
            <?php endif; ?>
        </div>

        <!-- TAB CONTENT: Visão Geral -->
        <div id="tab-visao-geral" class="tab-content block animate-fade-in">
            <h3 class="font-bold text-3xl mb-6 text-slate-800">📜 História</h3>
            <p class="text-slate-700 text-lg md:text-xl leading-relaxed max-w-none whitespace-pre-line"><?= htmlspecialchars($pais['historia'] ?? '') ?></p>
        </div>

        <!-- TAB CONTENT: Cultura & Clima -->
        <div id="tab-cultura" class="tab-content hidden animate-fade-in grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-blue-50 p-8 md:p-10 rounded-3xl shadow drop-shadow-sm border border-blue-100">
                <div class="text-5xl mb-6">🌤️</div>
                <h3 class="font-bold text-3xl text-blue-900 mb-4">Clima</h3>
                <p class="text-blue-800 text-lg md:text-xl leading-relaxed whitespace-pre-line"><?= htmlspecialchars($pais['clima'] ?? '') ?></p>
            </div>
            <div class="bg-purple-50 p-8 md:p-10 rounded-3xl shadow drop-shadow-sm border border-purple-100">
                <div class="text-5xl mb-6">🎭</div>
                <h3 class="font-bold text-3xl text-purple-900 mb-4">Dicas Culturais</h3>
                <p class="text-purple-800 text-lg md:text-xl leading-relaxed whitespace-pre-line"><?= htmlspecialchars($pais['dicas_culturais'] ?? '') ?></p>
            </div>
        </div>

        <!-- TAB CONTENT: Gastronomia -->
        <div id="tab-gastronomia" class="tab-content hidden animate-fade-in">
            <div class="bg-orange-50 p-8 md:p-12 rounded-3xl shadow-md border-l-[12px] border-orange-400">
                <div class="text-5xl mb-6">🍽️</div>
                <h2 class="text-4xl font-bold text-orange-700 mb-6">Gastronomia Típica</h2>
                <p class="text-orange-900 leading-relaxed text-lg md:text-xl whitespace-pre-line max-w-4xl"><?= htmlspecialchars($pais['nome_pratos'] ?? '') ?></p>
            </div>
        </div>

        <!-- TAB CONTENT: Turismo -->
        <?php if (count($galeria) > 0): ?>
        <div id="tab-turismo" class="tab-content hidden animate-fade-in">
            <h2 class="text-3xl font-bold mb-8 text-slate-800">✈️ Pontos Turísticos</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($galeria as $ponto): ?>
                    <div onclick="abrirModal('modal_<?= $ponto['id'] ?>')" class="cursor-pointer bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden group relative h-72">
                        <img src="uploads/turismo/<?= htmlspecialchars($ponto['nome_arquivo'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/90 to-transparent p-6 pt-12">
                            <p class="text-white text-xl font-bold drop-shadow-md"><?= htmlspecialchars($ponto['nome_ponto'] ?? '') ?></p>
                        </div>
                    </div>
                    <div id="modal_<?= $ponto['id'] ?>" class="hidden fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm">
                        <div class="bg-white rounded-3xl w-full max-w-5xl max-h-[90vh] flex flex-col md:flex-row relative overflow-hidden shadow-2xl border border-gray-700">
                            <button onclick="fecharModal('modal_<?= $ponto['id'] ?>')" class="absolute top-4 right-4 bg-white/90 hover:bg-gray-200 text-gray-800 rounded-full w-10 h-10 flex items-center justify-center font-bold text-xl z-20 shadow">✕</button>
                            <img src="uploads/turismo/<?= htmlspecialchars($ponto['nome_arquivo'] ?? '') ?>" class="w-full md:w-1/2 h-64 md:h-auto object-cover">
                            <div class="p-8 md:p-12 overflow-y-auto w-full no-scrollbar">
                                <h3 class="text-3xl font-black mb-6 text-slate-800"><?= htmlspecialchars($ponto['nome_ponto'] ?? '') ?></h3>
                                <p class="whitespace-pre-line text-slate-600 text-lg leading-relaxed mb-8"><?= htmlspecialchars($ponto['historia_ponto'] ?? 'Sem descrição') ?></p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                        <strong class="text-sm uppercase text-gray-500 tracking-widest block mb-2">📍 Como Chegar</strong>
                                        <span class="text-lg font-medium text-slate-700 leading-snug"><?= htmlspecialchars($ponto['como_chegar'] ?? '') ?></span>
                                    </div>
                                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                        <strong class="text-sm uppercase text-gray-500 tracking-widest block mb-2">🕒 Horários</strong>
                                        <span class="text-lg font-medium text-slate-700 leading-snug"><?= htmlspecialchars($ponto['horario'] ?? '') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </section>
</main>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('block'));
    
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('text-emerald-600', 'border-b-4', 'border-emerald-600');
        el.classList.add('text-gray-400');
    });

    document.getElementById('tab-' + tabId).classList.remove('hidden');
    document.getElementById('tab-' + tabId).classList.add('block');

    const btn = document.getElementById('btn-' + tabId);
    btn.classList.remove('text-gray-400');
    btn.classList.add('text-emerald-600', 'border-b-4', 'border-emerald-600');
}
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fadeIn 0.4s ease-out forwards;
}
</style>
<?php include 'includes/footer.php'; ?>