<?php
namespace App\Manager;

use Doliprane\Manager\AbstractManager;
use App\Entity\Article;

class ArticleManager extends AbstractManager {

    public function find(int $id) {
        return $this->readOne(Article::class, [ 'id' => $id ]);
    }

    public function findOneBy(array $filters) {
        return $this->readOne(Article::class, $filters);
    }

    public function findAll() {
        return $this->readMany(Article::class);
    }

    public function findBy(array $filters, array $order = [], int $limit = null, int $offset = null) {
        return $this->readMany(Article::class, $filters, $order, $limit, $offset);
    }

    public function add(Article $article) {
        return $this->create(Article::class, [
                'title' => $article->getTitle(),
                'description' => $article->getDescription(),
                'content' => $article->getContent()
            ]
        );
    }

    public function edit(Article $article) {
        return $this->update(Article::class, [
            'title' => $article->getTitle(),
            'description' => $article->getDescription(),
            'content' => $article->getContent()
        ],
            $article->getId()
        );
    }

    public function delete(Article $article) {
        return $this->remove(Article::class, $article->getId());
    }


}
