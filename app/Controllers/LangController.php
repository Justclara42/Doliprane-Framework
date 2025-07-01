<?php
namespace App\Controllers;

use App\Core\Lang;

class LangController
{

    private array $supportedLocales = ['fr_FR', 'en_US', 'de_DE', 'es_ES', 'it_IT'];

    public function switch()
    {
        if (isset($_POST['lang']) && in_array($_POST['lang'], $this->supportedLocales)) {
            $_SESSION['lang'] = $_POST['lang'];
        }

        // Redirection vers la page précédente
        $ref = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $ref");
        exit;
    }
//    public function switch()
//    {
//        file_put_contents('lang_debug.log', "Langue POST : {$_POST['lang']}\n", FILE_APPEND);
//        if (isset($_POST['lang']) && in_array($_POST['lang'], ['fr_FR', 'en_US'])) {
//            $_SESSION['lang'] = $_POST['lang'];
//            Lang::setLocale($_POST['lang']); // <== AJOUTÉ
//        }
//
//        // Redirection vers la page précédente
//        $ref = $_SERVER['HTTP_REFERER'] ?? '/';
//        header("Location: $ref");
//        exit;
//    }
}
