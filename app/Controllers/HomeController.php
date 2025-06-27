<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
class HomeController extends Controller {
    public function index() {
        $visits = $_COOKIE['visits'] ?? 0;
        $visits++;
        setcookie('visits', $visits, time() + 3600, '/');

        View::layout('base', 'home', ['visits' => $visits]);
    }
}
