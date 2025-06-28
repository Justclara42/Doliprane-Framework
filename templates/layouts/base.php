<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Doliprane Framework' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/tailwind.css">
    <script defer src="/assets/lucide/lucide.min.js"></script>
    <script defer src="/assets/js/app.js"></script>
</head>
<body class="bg-[#FFE600] text-gray-900 min-h-screen flex flex-col font-sans">

<!-- HEADER -->
<header class="bg-[#0074D9] text-white px-4 py-4 shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <h1 class="text-xl font-bold"><a href="/">💊 Doliprane Framework</a></h1>

        <!-- Menu desktop -->
        <nav class="hidden md:flex gap-6">
            <a href="/" class="hover:underline">Accueil</a>
            <a href="/about" class="hover:underline">À propos</a>
            <a href="/docs" class="hover:underline">Docs</a>
            <a href="https://github.com/Justclara42/Doliprane-Framework" class="hover:underline" target="_blank">GitHub</a>
        </nav>

        <!-- Menu burger -->
        <button id="burger-btn" class="md:hidden text-white focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Menu mobile -->
    <nav id="mobile-nav" class="hidden flex-col gap-2 px-4 pt-2 md:hidden">
        <a href="/" class="block hover:underline">Accueil</a>
        <a href="/about" class="block hover:underline">À propos</a>
        <a href="/docs" class="block hover:underline">Docs</a>
        <a href="https://github.com/Justclara42/Doliprane-Framework" target="_blank" class="block hover:underline">GitHub</a>
    </nav>
</header>

<!-- MAIN -->
<main class="flex-grow w-full max-w-7xl mx-auto px-4 py-10 bg-[#FFE600]/30">
    <?php include $templateFile; ?>
</main>

<!-- FOOTER -->
<footer class="bg-[#0074D9] text-white text-center py-4 mt-8">
    &copy; <?= date('Y') ?> Doliprane Framework — Tous droits réservés
</footer>

</body>
</html>