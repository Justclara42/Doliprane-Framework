<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;

class HomeController extends Controller {
    public function index() {
        View::render('home', ['title' => 'Accueil']);
    }
}
