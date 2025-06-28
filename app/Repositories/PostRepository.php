<?php
namespace App\Repositories;

class PostRepository
{
    public function find($id): array
    {
        // Ici on peut simuler une base de données
        return ['id' => $id, 'title' => 'Post ' . $id];
    }
}
