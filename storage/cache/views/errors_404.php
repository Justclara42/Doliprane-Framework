<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>
    Erreur 404 - Page non trouvée
</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/assets/favicon.ico" type="image/x-icon">

    <?= $assetManager->getCssTags() ?>
    <script defer src="/assets/lucide/lucide.min.js"></script>
    <?= $assetManager->getJsTags() ?>
    
    <style>
        .error-500 {
            animation: shake 0.5s ease-in-out infinite alternate;
        }

        @keyframes shake {
            from { transform: translateX(-2px); }
            to { transform: translateX(2px); }
        }
    </style>


    <style>
        .error-404 {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%   { transform: translateY(0); }
            50%  { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }
    </style>

</head>
<body class="bg-[#FFE600] text-gray-900 min-h-screen flex flex-col font-sans">

<?php

$controller = new \App\Controllers\LangController();
$supportedLocales = $controller->getSupportedLocales();



?>
<header class="bg-[#0074D9] text-white px-4 py-4 shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <h1 class="text-xl font-bold"><a href="/">💊 Doliprane Framework</a></h1>

        <!-- Menu desktop -->
        <nav class="flex md:flex gap-6">
            <a href="/" class="hover:underline"><?= lang("home") ?></a>
            <a href="/about" class="hover:underline"><?= lang("about") ?></a>
            <a href="/docs" class="hover:underline"><?= lang("docs") ?></a>
            <a href="https://github.com/Justclara42/Doliprane-Framework" class="hover:underline" target="_blank">GitHub</a>

            <form method="POST" action="/set-lang" class="inline-block">
                <label for="lang_sel" class="sr-only"><?= lang("lang_select") ?></label>
                <select id="lang_sel" name="lang" onchange="this.form.submit()" class="bg-white text-black rounded px-2 py-1 border">
                    <?php foreach ($supportedLocales as $locale): ?>
                        <option value="<?= htmlspecialchars($locale) ?>"
                            <?= (isset($_SESSION['lang']) && $_SESSION['lang'] === $locale) ? 'selected' : '' ?>>
                            <?= htmlspecialchars(substr($locale, -2)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
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
        <a href="/" class="block hover:underline"><?= lang("home") ?></a>
        <a href="/about" class="block hover:underline"><?= lang("about") ?></a>
        <a href="/docs" class="block hover:underline"><?= lang("docs") ?></a>
        <a href="https://github.com/Justclara42/Doliprane-Framework" target="_blank" class="block hover:underline">GitHub</a>
        <form method="POST" action="/set-lang" class="inline-block">
            <label for="lang_sel" class="sr-only"><?= lang("lang_select") ?></label>
            <select id="lang_sel" name="lang" onchange="this.form.submit()" class="bg-white text-black rounded px-2 py-1 border">
                <?php foreach ($supportedLocales as $locale): ?>
                    <option value="<?= htmlspecialchars($locale) ?>"
                        <?= (isset($_SESSION['lang']) && $_SESSION['lang'] === $locale) ? 'selected' : '' ?>>
                        <?= htmlspecialchars(substr($locale, -2)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </nav>
</header>


<main class="flex-grow w-full max-w-7xl mx-auto px-4 py-10 bg-[#FFE600]/30">
    
    <div class="flex flex-col items-center justify-center text-center text-yellow-600 py-20">
        <h1 class="text-6xl font-extrabold error-404">404</h1>
        <p class="text-2xl mt-4 font-semibold">La page demandée est introuvable.</p>
        <p class="mt-2 text-gray-700">Il semble que cette page n'existe pas ou a été déplacée.</p>
        <a href="/" class="mt-6 inline-block bg-yellow-600 text-white px-6 py-2 rounded hover:bg-yellow-700 transition">
            Retour à l'accueil
        </a>
    </div>

</main>

<footer class="bg-[#0074D9] text-white text-center py-4 mt-8">
    &copy; <?= date("Y") ?> Doliprane Framework — <?= lang("all_rights_reserved") ?>
</footer>
<script>
    // Réouverture automatique après erreur (si erreurs présentes)
    document.addEventListener('DOMContentLoaded', () => {
        const hasErrors = <?= htmlspecialchars(count($debug["errors"] ?? []) > 0 ? 'true' : 'false') ?>;
        if (hasErrors) {
            document.getElementById('debug-details').classList.remove('hidden');
        }
    });
</script>



<?php if (isset($debugbar)) echo $debugbar; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>

</body>
</html>
