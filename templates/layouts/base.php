<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Doliprane Framework' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/tailwind.css">
</head>
<body class="bg-[#FFE600] text-gray-900 min-h-screen flex flex-col font-sans">

<!-- HEADER -->
<header class="bg-[#0074D9] text-white px-6 py-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">💊 Doliprane Framework</h1>
        <nav class="space-x-4">
            <a href="/" class="hover:underline">Accueil</a>
            <a href="https://github.com/Justclara42/Doliprane-Framework" target="_blank" class="hover:underline">GitHub</a>
        </nav>
    </div>
</header>

<!-- MAIN -->
<main class="flex-grow container mx-auto px-6 py-10">
    <?php include $templateFile; ?>
</main>

<!-- FOOTER -->
<footer class="bg-[#0074D9] text-white text-center py-4 mt-8">
    &copy; <?= date('Y') ?> Doliprane Framework — Tous droits réservés
</footer>

</body>
</html>
