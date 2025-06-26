<?php
namespace App\Controller;

use Doliprane\Controller\AbstractController;

class MainController extends AbstractController {

    public function home() {
        return $this->renderView('main/home.php', ['title' => 'Accueil']);
    }

    public function contact() {
        return $this->redirectToRoute('home', ['state' => 'success']);
    }

}
