<?php
namespace App\Controllers;

use App\Models\Post;
class PostController
{
    public function view($id, $slug)
    {
        $post = Post::findOrFail($id);
        View::layout('base', 'post', ['post' => $post]);
    }
}
