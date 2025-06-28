<?php
namespace App\Controllers;

class LangController
{
    public function switch()
    {
        if (isset($_POST['lang']) && in_array($_POST['lang'], ['fr_FR', 'en_US'])) {
            $_SESSION['lang'] = $_POST['lang'];
        }

        // Redirection vers la page précédente
        $ref = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $ref");
        exit;
    }
}
