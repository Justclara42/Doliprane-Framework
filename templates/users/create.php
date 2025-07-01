<?php
// Doliprane Framework — Formulaire de création
?>

<h1 class="text-2xl font-bold mb-6">Créer un(e) User</h1>

<form method="POST" action="/users/store" class="space-y-4 bg-white p-6 rounded shadow">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
        <input type="text" id="name" name="name" class="mt-1 block w-full border border-gray-300 px-3 py-2 rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300" required>
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" class="mt-1 block w-full border border-gray-300 px-3 py-2 rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <div class="flex justify-end">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Enregistrer
        </button>
    </div>
</form>
