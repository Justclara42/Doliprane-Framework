<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>
    <?= lang("about") ?>
</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/tailwind.css">
    <script defer src="/assets/lucide/lucide.min.js"></script>
    <script defer src="/assets/js/app.js"></script>
    
</head>
<body class="bg-[#FFE600] text-gray-900 min-h-screen flex flex-col font-sans">
    <header class="bg-[#0074D9] text-white px-4 py-4 shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <h1 class="text-xl font-bold"><a href="/">💊 Doliprane Framework</a></h1>

        <!-- Menu desktop -->
        <nav class="hidden md:flex gap-6">
            <a href="/" class="hover:underline">Accueil</a>
            <a href="/about" class="hover:underline">À propos</a>
            <a href="/docs" class="hover:underline">Docs</a>
            <a href="https://github.com/Justclara42/Doliprane-Framework" class="hover:underline" target="_blank">GitHub</a>

            <form method="POST" action="/set-lang" class="inline-block">
                <label for="lang_sel" class="sr-only"><?= lang("lang_select") ?></label>
                <select id="lang_sel" name="lang" onchange="this.form.submit()" class="bg-white text-black rounded px-2 py-1 border">
                    <option value="fr_FR" <?= htmlspecialchars(($_SESSION['lang'] ?? '') === 'fr_FR' ? 'selected' : '') ?>>🇫🇷 FR</option>
                    <option value="en_US" <?= htmlspecialchars(($_SESSION['lang'] ?? '') === 'en_US' ? 'selected' : '') ?>>🇬🇧 EN</option>
                    <option value="de_DE" <?= htmlspecialchars(($_SESSION['lang'] ?? '') === 'de_DE' ? 'selected' : '') ?>>🇩🇪 DE</option>
                    <option value="es_ES" <?= htmlspecialchars(($_SESSION['lang'] ?? '') === 'es_ES' ? 'selected' : '') ?>>🇪🇸 ES</option>
                    <option value="it_IT" <?= htmlspecialchars(($_SESSION['lang'] ?? '') === 'it_IT' ? 'selected' : '') ?>>🇮🇹 IT</option>
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
                <option value="fr_FR" <?= htmlspecialchars(($_SESSION['lang'] ?? '') === 'fr_FR' ? 'selected' : '') ?>>🇫🇷 FR</option>
                <option value="en_US" <?= htmlspecialchars(($_SESSION['lang'] ?? '') === 'en_US' ? 'selected' : '') ?>>🇬🇧 EN</option>
                <option value="de_DE" <?= htmlspecialchars(($_SESSION['lang'] ?? '') === 'de_DE' ? 'selected' : '') ?>>🇩🇪 DE</option>
                <option value="es_ES" <?= htmlspecialchars(($_SESSION['lang'] ?? '') === 'es_ES' ? 'selected' : '') ?>>🇪🇸 ES</option>
                <option value="it_IT" <?= htmlspecialchars(($_SESSION['lang'] ?? '') === 'it_IT' ? 'selected' : '') ?>>🇮🇹 IT</option>
            </select>
        </form>
    </nav>
</header>


    <main class="flex-grow w-full max-w-7xl mx-auto px-4 py-10 bg-[#FFE600]/30">
        
<section class="bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-bold text-[#0074D9] mb-4"><?= lang("about") ?></h2>

    <p>
        Le framework <strong>Doliprane</strong> a été conçu pour être léger, rapide et évolutif.
        Il est idéal pour apprendre à construire une architecture MVC, gérer les vues, les routes et les composants front.
    </p>
</section>

    </main>

    <footer class="bg-[#0074D9] text-white text-center py-4 mt-8">
    &copy; <?= htmlspecialchars(date("Y")) ?> Doliprane Framework — <?= lang("all_rights_reserved") ?>
</footer>


    
</body>
</html>
