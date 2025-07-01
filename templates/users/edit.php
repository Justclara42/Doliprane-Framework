<?php
// Doliprane Framework — Formulaire d'édition
?>

<h1 class="text-2xl font-bold mb-6">Modifier le/la User</h1>

<form method="POST" action="/users/update/<?= \$item->id ?>" class="space-y-4 bg-white p-6 rounded shadow">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
        <input type="text" id="name" name="name" value="<?= \$item->name ?>" class="mt-1 block w-full border border-gray-300 px-3 py-2 rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300" required>
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" value="<?= \$item->email ?>" class="mt-1 block w-full border border-gray-300 px-3 py-2 rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <div class="flex justify-end">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Mettre à jour
        </button>
    </div>
</form>
