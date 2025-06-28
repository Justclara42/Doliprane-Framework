<?php
namespace App\Controllers;

class LangController
{
    public function set() {
        $_SESSION['lang'] = $_POST['lang'] ?? 'fr_FR';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
