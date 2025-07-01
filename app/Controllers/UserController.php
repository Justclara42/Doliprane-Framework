<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Core\View;

class UserController extends Controller
{
    public function index()
    {
        $items = User::all();
        View::layout('base', 'users/index', ['items' => $items]);
    }

    public function show($id)
    {
        $item = User::findOrFail($id);
        View::layout('base', 'users/show', ['item' => $item]);
    }

    public function create()
    {
        View::layout('base', 'users/create');
    }

    public function edit($id)
    {
        $item = User::findOrFail($id);
        View::layout('base', 'users/edit', ['item' => $item]);
    }
}
