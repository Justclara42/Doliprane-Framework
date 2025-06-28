<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
class PageController extends Controller {
    public function about() {
        View::layout('base', 'about', [
            'title' => 'À propos'
        ]);
    }

    public function docs() {
        View::layout('base', 'docs', [
            'title' => 'Documentation'
        ]);

    }
}
