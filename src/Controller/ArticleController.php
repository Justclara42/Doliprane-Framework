<?php
namespace App\Controller;

use App\Manager\ArticleManager;
use Doliprane\Controller\AbstractController;

class ArticleController extends AbstractController {

    public function index() {
        $articleManager = new ArticleManager();
        return $this->renderView('article/index.php', [
            'articles' => $articleManager->findAll()
        ]);
    }

    public function add() {
        if (!empty($_POST)) {
            $article = new Article();
            $articleManager = new ArticleManager();
            $article->setTitle($_POST['title']);
            $article->setDescription($_POST['description']);
            $article->setContent($_POST['content']);
            $articleManager->add($article);
            return $this->redirectToRoute('/blog');
        }
        return $this->renderView('article/add.php');
    }

}

