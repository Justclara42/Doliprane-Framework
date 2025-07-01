<?php

namespace App\Core;

class AssetManager
{
    private array $css = [];
    private array $js = [];
    private array $images = [];
    private array $fonts = [];

    private string $cssPath = '/assets/css/';
    private string $jsPath = '/assets/js/';
    private string $imgPath = '/assets/img/';
    private string $fontsPath = '/assets/fonts/';
    private string $publicRoot;

    public function __construct()
    {
        $this->publicRoot = $_SERVER['DOCUMENT_ROOT'];
    }

    // CSS
    public function addCss(string $file): void
    {
        $this->css[] = $this->cssPath . $file;
    }

    public function getCssTags(): string
    {
        $tags = '';
        foreach ($this->css as $file) {
            $tags .= "<link rel='stylesheet' href='" . $this->versionedUrl($file) . "'>\n";
        }
        return $tags;
    }

    // JS
    public function addJs(string $file): void
    {
        $this->js[] = $this->jsPath . $file;
    }

    public function getJsTags(): string
    {
        $tags = '';
        foreach ($this->js as $file) {
            $tags .= "<script src='" . $this->versionedUrl($file) . "'></script>\n";
        }
        return $tags;
    }

    // Images
    public function getImageUrl(string $file): string
    {
        return $this->versionedUrl($this->imgPath . $file);
