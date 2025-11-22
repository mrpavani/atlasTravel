<?php
require 'db.php';
$titulo_pagina = "Home - AtlasTravel";
include 'includes/header.php';

// Busca atrações aleatórias para a seção "Inesquecíveis"
// Faz JOIN para pegar o nome do país também
$stmt = $pdo->query("SELECT g.*, p.nome as pais_nome FROM galeria_turismo g JOIN paises p ON g.pais_id = p.id ORDER BY RAND() LIMIT 4");
$destaques_home = $stmt->fetchAll();
?>
<?php include 'menu.php'; ?>

<div class="relative h-[600px] flex items-center justify-center bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1920&q=80" class="absolute inset-0 w-full h-full object-cover opacity-40 animate-pulse-slow">
    <div class="relative z-10 text-center px-4 animate-fade-in-up max-w-3xl">
        <span class="text-emerald-400 font-bold tracking-[0.3em] text-sm uppercase mb-4 block">Explore o Inexplorado</span>
        <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 tracking-tight leading-tight">Descubra Culturas Fascinantes</h1>
        <p class="text-xl text-gray-200 mb-10 font-light">Do charme histórico da Europa às savanas da África.</p>
        <a href="destinos.php" class="bg-emerald-500 hover:bg-emerald-400 text-white px-10 py-4 rounded-full font-bold transition shadow-xl hover:shadow-emerald-500/40 text-lg transform hover:-translate-y-1 inline-block">
            Começar Jornada
        </a>
    </div>
</div>

<section class="py-20 bg-slate-50">
    <div class="container mx-auto px-6 text-center mb-12">
        <h2 class="text-3xl font-bold text-slate-800 font-serif">Navegue por Região</h2>
    </div>
    <div class="container mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 max-w-6xl">
        <a href="destinos.php?cat=Europa" class="map-card bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl border border-slate-100 cursor-pointer group transition text-center">
            <div class="text-6xl mb-4 transform group-hover:scale-110 transition">🏰</div>
            <h3 class="text-xl font-bold text-slate-800">Europa</h3>
        </a>
        <a href="destinos.php?cat=Ásia" class="map-card bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl border border-slate-100 cursor-pointer group transition text-center">
            <div class="text-6xl mb-4 transform group-hover:scale-110 transition">⛩️</div>
            <h3 class="text-xl font-bold text-slate-800">Ásia</h3>
        </a>
        <a href="destinos.php?cat=África" class="map-card bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl border border-slate-100 cursor-pointer group transition text-center">
            <div class="text-6xl mb-4 transform group-hover:scale-110 transition">🦁</div>
            <h3 class="text-xl font-bold text-slate-800">África</h3>
        </a>
        <a href="destinos.php?cat=América do Sul" class="map-card bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl border border-slate-100 cursor-pointer group transition text-center">
            <div class="text-6xl mb-4 transform group-hover:scale-110 transition">💃</div>
            <h3 class="text-xl font-bold text-slate-800">Américas</h3>
        </a>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12">
            <div>
                <h2 class="text-4xl font-bold text-slate-800 mb-2 font-serif">Atrações Inesquecíveis</h2>
                <p class="text-gray-500">Pontos turísticos marcantes selecionados para você.</p>
            </div>
            <a href="destinos.php" class="text-emerald-600 font-bold hover:text-emerald-700 mt-4 md:mt-0">Ver todos os destinos →</a>
        </div>

        <?php if (count($destaques_home) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($destaques_home as $spot): ?>
                    <a href="detalhes_pais.php?id=<?= $spot['pais_id'] ?>" class="group relative h-80 rounded-2xl overflow-hidden shadow-lg cursor-pointer">
                        <img src="uploads/turismo/<?= htmlspecialchars($spot['nome_arquivo']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-6">
                            <span class="text-emerald-400 text-xs font-bold uppercase tracking-wider mb-1"><?= htmlspecialchars($spot['pais_nome']) ?></span>
                            <h3 class="text-white font-bold text-xl group-hover:text-emerald-200 transition"><?= htmlspecialchars($spot['nome_ponto']) ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-400 py-10">Cadastre pontos turísticos para vê-los aqui.</p>
        <?php endif; ?>
    </div>
</section>


<section id="parceiros" class="bg-slate-900 py-16 overflow-hidden border-t border-slate-800">
    <div class="container mx-auto px-6 mb-8 text-center">
        <h2 class="text-2xl font-bold text-white font-serif">Nossos Parceiros Oficiais</h2>
        <p class="text-slate-400 text-sm mt-2">As melhores agências e hotéis confiam na AtlasTravel.</p>
    </div>

    <div class="relative w-full overflow-hidden">
        <div class="absolute inset-y-0 left-0 w-20 bg-gradient-to-r from-slate-900 to-transparent z-10"></div>
        <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-slate-900 to-transparent z-10"></div>

        <div class="flex w-[200%] animate-scroll gap-16 items-center opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition duration-500">
            <div class="flex gap-20 items-center justify-around w-1/2 px-4">
                <div class="flex items-center gap-3 text-white"><span class="text-4xl">✈️</span> <span class="font-bold text-2xl tracking-tighter">SkyWings</span></div>
                <div class="flex items-center gap-3 text-white"><span class="text-4xl">🏨</span> <span class="font-bold text-2xl tracking-tighter">GrandHotel</span></div>
                <div class="flex items-center gap-3 text-white"><span class="text-4xl">🌍</span> <span class="font-bold text-2xl tracking-tighter">EcoTour</span></div>
                <div class="flex items-center gap-3 text-white"><span class="text-4xl">🛡️</span> <span class="font-bold text-2xl tracking-tighter">SafeGuard</span></div>
                <div class="flex items-center gap-3 text-white"><span class="text-4xl">🗺️</span> <span class="font-bold text-2xl tracking-tighter">LocalGuide</span></div>
            </div>
            <div class="flex gap-20 items-center justify-around w-1/2 px-4">
                <div class="flex items-center gap-3 text-white"><span class="text-4xl">✈️</span> <span class="font-bold text-2xl tracking-tighter">SkyWings</span></div>
                <div class="flex items-center gap-3 text-white"><span class="text-4xl">🏨</span> <span class="font-bold text-2xl tracking-tighter">GrandHotel</span></div>
                <div class="flex items-center gap-3 text-white"><span class="text-4xl">🌍</span> <span class="font-bold text-2xl tracking-tighter">EcoTour</span></div>
                <div class="flex items-center gap-3 text-white"><span class="text-4xl">🛡️</span> <span class="font-bold text-2xl tracking-tighter">SafeGuard</span></div>
                <div class="flex items-center gap-3 text-white"><span class="text-4xl">🗺️</span> <span class="font-bold text-2xl tracking-tighter">LocalGuide</span></div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
</body>

</html>