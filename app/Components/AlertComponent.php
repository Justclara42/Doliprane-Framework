<?php

namespace App\Components;

use App\Core\Component;

class AlertComponent extends Component
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->view = 'components.alert';
    }
}