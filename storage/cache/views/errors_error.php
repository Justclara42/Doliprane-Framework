<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title> Erreur <?= $code ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/assets/favicon.ico" type="image/x-icon">

    <?= $assetManager->getCssTags() ?>
    <script defer src="/assets/lucide/lucide.min.js"></script>
    <?= $assetManager->getJsTags() ?>
    
</head>
<body class="bg-[#FFE600] text-gray-900 min-h-screen flex flex-col font-sans">
    <header class="bg-[#0074D9] text-white px-4 py-4 shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <h1 class="text-xl font-bold"><a href="/">💊 Doliprane Framework</a></h1>

        <!-- Menu desktop -->
        <nav class="flex md:flex gap-6">
            <a href="/" class="hover:underline">Accueil</a>
            <a href="/about" class="hover:underline">À propos</a>
            <a href="/docs" class="hover:underline">Docs</a>
            <a href="https://github.com/Justclara42/Doliprane-Framework" class="hover:underline" target="_blank">GitHub</a>

            <form method="POST" action="/set-lang" class="inline-block">
                <label for="lang_sel" class="sr-only"><?= lang("lang_select") ?></label>
                <select id="lang_sel" name="lang" onchange="this.form.submit()" class="bg-white text-black rounded px-2 py-1 border">
                    <option value="fr_FR" <?= ($_SESSION['lang'] ?? '') === 'fr_FR' ? 'selected' : '' ?>>🇫🇷 FR</option>
                    <option value="en_US" <?= ($_SESSION['lang'] ?? '') === 'en_US' ? 'selected' : '' ?>>🇬🇧 EN</option>
                    <option value="de_DE" <?= ($_SESSION['lang'] ?? '') === 'de_DE' ? 'selected' : '' ?>>🇩🇪 DE</option>
                    <option value="es_ES" <?= ($_SESSION['lang'] ?? '') === 'es_ES' ? 'selected' : '' ?>>🇪🇸 ES</option>
                    <option value="it_IT" <?= ($_SESSION['lang'] ?? '') === 'it_IT' ? 'selected' : '' ?>>🇮🇹 IT</option>
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
        <a href="/" class="block hover:underline">Accueil</a>
        <a href="/about" class="block hover:underline">À propos</a>
        <a href="/docs" class="block hover:underline">Docs</a>
        <a href="https://github.com/Justclara42/Doliprane-Framework" target="_blank" class="block hover:underline">GitHub</a>
        <form method="POST" action="/set-lang" class="inline-block">
            <label for="lang_sel" class="sr-only"><?= lang("lang_select") ?></label>
            <select id="lang_sel" name="lang" onchange="this.form.submit()" class="bg-white text-black rounded px-2 py-1 border">
                <option value="fr_FR" <?= ($_SESSION['lang'] ?? '') === 'fr_FR' ? 'selected' : '' ?>>🇫🇷 FR</option>
                <option value="en_US" <?= ($_SESSION['lang'] ?? '') === 'en_US' ? 'selected' : '' ?>>🇬🇧 EN</option>
                <option value="de_DE" <?= ($_SESSION['lang'] ?? '') === 'de_DE' ? 'selected' : '' ?>>🇩🇪 DE</option>
                <option value="es_ES" <?= ($_SESSION['lang'] ?? '') === 'es_ES' ? 'selected' : '' ?>>🇪🇸 ES</option>
                <option value="it_IT" <?= ($_SESSION['lang'] ?? '') === 'it_IT' ? 'selected' : '' ?>>🇮🇹 IT</option>
            </select>
        </form>
    </nav>
</header>


    <main class="flex-grow w-full max-w-7xl mx-auto px-4 py-10 bg-[#FFE600]/30">
        
    <div class="text-center mt-16">
        <h1 class="text-6xl font-bold text-red-600">Erreur <?= $code ?></h1>
        <p class="mt-4 text-xl text-gray-700"><?= $message ?></p>

        <?php if ($trace): ?>
            <pre class="mt-6 p-4 bg-gray-100 text-left text-sm rounded text-gray-800 overflow-x-auto"><?= $trace ?></pre>
        <?php endif; ?>

        <a href="/" class="mt-6 inline-block text-blue-500 underline">Retour à l'accueil</a>
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


    
</body>
</html>
