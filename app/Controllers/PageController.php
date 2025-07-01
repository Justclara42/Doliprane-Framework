<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;

class PageController extends Controller {
    public function about() {
        View::render('about', ['title' => 'À propos']);
    }

    public function docs() {
        View::render('docs', ['title' => 'Documentation']);
    }
}
