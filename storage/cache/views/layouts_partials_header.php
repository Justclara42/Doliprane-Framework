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
