<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo_pagina) ? $titulo_pagina : 'AtlasTravel' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= strpos($_SERVER['REQUEST_URI'], 'admin') ? '../' : '' ?>assets/css/style.css">
    <script src="<?= strpos($_SERVER['REQUEST_URI'], 'admin') ? '../' : '' ?>assets/js/script.js" defer></script>
</head>

<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">