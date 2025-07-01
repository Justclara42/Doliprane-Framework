<?php
// Doliprane Framework - Vue générée automatiquement
?>

<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">users/index</h1>
    <p>Bienvenue sur la vue <code>users/index</code> pour le modèle <code>User</code>.</p>
    <?php foreach ($items as $item): ?>
        <div class="mb-4 p-4 border rounded">
            <h2 class="text-xl font-semibold"><?= htmlspecialchars($item->username) ?></h2>
            <p>Email: <?= htmlspecialchars($item->email) ?></p>
            <a href="/users/<?= $item->id ?>" class="text-blue-500 hover:underline">Voir les détails</a>
        </div>
    <?php endforeach; ?>
</div>
