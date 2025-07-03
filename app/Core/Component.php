<?php

namespace App\Core;

use App\Core\View\TemplateEngine;

abstract class Component
{
    protected string $view;
    protected array $data = [];
    protected ?string $slot = null;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function with(array $data): static
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function slot(string $content): static
    {
        $this->slot = $content;
        return $this;
    }

    public function render(): string
    {
        $engine = new TemplateEngine();
        $data = $this->data;
        if ($this->slot !== null) {
            $data['slot'] = $this->slot;
        }
        return $engine->render($this->view, $data);
    }

    public function view(): string
    {
        return $this->view;
    }
}
