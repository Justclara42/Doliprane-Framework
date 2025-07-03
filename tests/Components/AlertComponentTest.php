<?php

use App\Components\AlertComponent;

test('Alert component renders correctly', function () {
    $component = new AlertComponent([
        'type' => 'warning',
        'message' => 'Attention !',
        'slot' => 'Contenu additionnel'
    ]);

    $html = $component->render();

    expect($html)->toContain('alert alert-warning');
    expect($html)->toContain('Attention !');
    expect($html)->toContain('Contenu additionnel');
});
