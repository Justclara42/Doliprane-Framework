<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>
    Documentation
</title>
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
        
<div id="docs" class="flex min-h-[80vh]">

    <!-- Sidebar -->
    <aside id="sidebar"
           class="transition-all duration-300 ease-in-out bg-white shadow p-4 space-y-4 w-64 overflow-hidden"
           data-expanded="true">
        <button id="toggleSidebar" class="flex items-center gap-2 text-sm font-semibold mb-4 hover:underline">
            <i data-lucide="chevron-left"></i>
            <span class="text"><?= lang("collapse_sidebar") ?></span>
        </button>

        <nav class="flex flex-col space-y-3">
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="intro">
                <i data-lucide="book" class="pointer-events-none"></i>
                <span class="text"><?= lang("doc_intro") ?></span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow tooltip"><?= lang("doc_intro") ?></span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="routes">
                <i data-lucide="git-branch" class="pointer-events-none"></i>
                <span class="text"><?= lang("doc_routes") ?></span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow tooltip"><?= lang("doc_routes") ?></span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="controllers">
                <i data-lucide="layers" class="pointer-events-none"></i>
                <span class="text"><?= lang("doc_controllers") ?></span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow tooltip"><?= lang("doc_controllers") ?></span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="models">
                <i data-lucide="layers" class="pointer-events-none"></i>
                <span class="text"><?= lang("doc_models") ?></span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow tooltip"><?= lang("doc_models") ?></span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="views">
                <i data-lucide="eye" class="pointer-events-none"></i>
                <span class="text"><?= lang("doc_views") ?></span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow tooltip"><?= lang("doc_views") ?></span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="eloquent">
                <i data-lucide="database" class="pointer-events-none"></i>
                <span class="text"><?= lang("doc_eloquent") ?></span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow tooltip"><?= lang("doc_eloquent") ?></span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="relations">
                <i data-lucide="link" class="pointer-events-none"></i>
                <span class="text"><?= lang("doc_relations") ?></span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow tooltip"><?= lang("doc_relations") ?></span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="api">
                <i data-lucide="cloud" class="pointer-events-none"></i>
                <span class="text"><?= lang("doc_api") ?></span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow tooltip"><?= lang("doc_api") ?></span>
            </button>
            <button class="flex items-center gap-2 text-left hover:text-blue-600 relative group" data-doc="env">
                <i data-lucide="settings" class="pointer-events-none"></i>
                <span class="text"><?= lang("doc_env") ?></span>
                <span class="absolute left-full ml-2 hidden group-hover:block bg-gray-700 text-white text-xs px-2 py-1 rounded shadow tooltip"><?= lang("doc_env") ?></span>
            </button>
        </nav>
    </aside>

    <!-- Content -->
    <section id="doc-content" class="flex-1 p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4"><?= lang("documentation") ?></h2>
        <p><?= lang("choose_topic") ?></p>
    </section>
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
