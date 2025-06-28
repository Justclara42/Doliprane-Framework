<?php
namespace App\Controllers\Api;

use App\Repositories\PostRepository;

class PostController
{
    public function show($id)
    {
        $repo = new PostRepository();
        $post = $repo->find($id);
        header('Content-Type: application/json');
        echo json_encode($post);
    }
}
