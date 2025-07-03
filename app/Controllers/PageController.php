<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\Page;

class PageController extends Controller {
    public function about() {
        View::render('about', ['title' => 'À propos']);
    }

    public function docs() {
        View::render('docs', ['title' => 'Documentation']);
    }
}
