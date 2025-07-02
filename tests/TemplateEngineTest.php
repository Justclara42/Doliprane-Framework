<?php

use App\Core\View\TemplateEngine;
use Tests\TestCase;

beforeEach(function () {
    $this->engine = new TemplateEngine();

    // Répertoire temporaire pour tests
    $this->templatePath = ROOT . '/templates/tests/';
    $this->cachePath = ROOT . '/storage/cache/tests/';
    if (!is_dir($this->templatePath)) mkdir($this->templatePath, 0775, true);
    if (!is_dir($this->templatePath . 'tests')) mkdir($this->templatePath . 'tests', 0775, true);
    if (!is_dir($this->cachePath)) mkdir($this->cachePath, 0775, true);

    $reflection = new ReflectionClass($this->engine);
    $reflection->getProperty('templatePath')->setValue($this->engine, $this->templatePath);
    $reflection->getProperty('cachePath')->setValue($this->engine, $this->cachePath);
});

afterEach(function () {
    collect(glob($this->templatePath . 'tests/*'))->each(fn ($f) => unlink($f));
    collect(glob($this->cachePath . '*'))->each(fn ($f) => unlink($f));
});

test('renders simple variable with {{ $var }}', function () {
    file_put_contents($this->templatePath . 'tests/simple.dtf', 'Bonjour {{ $name }}');
    $output = $this->engine->render('tests.simple', ['name' => 'Clara']);
    expect($output)->toBe('Bonjour Clara');
});

test('escapes HTML in variables with {{ $var }}', function () {
    file_put_contents($this->templatePath . 'tests/escape.dtf', 'Safe: {{ $content }}');
    $output = $this->engine->render('tests.escape', ['content' => '<b>danger</b>']);
    expect($output)->toBe('Safe: &lt;b&gt;danger&lt;/b&gt;');
});

test('applies filters with {{ var|upper }}', function () {
    file_put_contents($this->templatePath . 'tests/filters.dtf', 'Name: {{ name|upper }}');
    $output = $this->engine->render('tests.filters', ['name' => 'clara']);
    expect($output)->toBe('Name: CLARA');
});

test('renders foreach loop', function () {
    file_put_contents($this->templatePath . 'tests/loop.dtf', '{% foreach $list as $item %}{{ $item }},{% endforeach %}');
    $output = $this->engine->render('tests.loop', ['list' => ['A', 'B', 'C']]);
    expect($output)->toBe('A,B,C,');
});

test('renders if condition', function () {
    file_put_contents($this->templatePath . 'tests/if.dtf', '{% if $val %}Yes{% else %}No{% endif %}');
    $output = $this->engine->render('tests.if', ['val' => true]);
    expect($output)->toBe('Yes');
});

test('includes another template', function () {
    file_put_contents($this->templatePath . 'tests/main.dtf', 'Hello {% include "tests.included" %}');
    file_put_contents($this->templatePath . 'tests/included.dtf', '{{ $msg }}');
    $output = $this->engine->render('tests.main', ['msg' => 'World']);
    expect($output)->toBe('Hello World');
});

test('renders layout with @extends and @yield', function () {
    file_put_contents($this->templatePath . 'tests/layout.dtf', 'Layout Start @yield("body") Layout End');
    file_put_contents($this->templatePath . 'tests/child.dtf', '@extends("tests.layout") @section("body")Content@endsection');
    $output = $this->engine->render('tests.child');
    expect($output)->toBe('Layout Start Content Layout End');
});

test('removes comments {# #}', function () {
    file_put_contents($this->templatePath . 'tests/comment.dtf', 'Hello {# this is a comment #} World');
    $output = $this->engine->render('tests.comment');
    expect($output)->toBe('Hello  World');
});

test('ignores unknown filters gracefully', function () {
    file_put_contents($this->templatePath . 'tests/unknown.dtf', 'Test: {{ name|nonexistent }}');
    $output = $this->engine->render('tests.unknown', ['name' => 'ok']);
    expect($output)->toContain('Filtre inconnu');
});

test('renders push and stack blocks', function () {
    file_put_contents($this->templatePath . 'tests/layout.dtf', 'Header @stack("scripts") Footer');
    file_put_contents($this->templatePath . 'tests/child.dtf', '@extends("tests.layout") @push("scripts")<script>test</script>@endpush');

    $output = $this->engine->render('tests.child');
    expect($output)->toContain('<script>test</script>');
});

test('renders translated text with {{ lang("key") }}', function () {
    // Simule un fichier de langue pour le test
    $langPath = ROOT . '/resources/lang/fr_FR.json';
    file_put_contents($langPath, json_encode(['hello' => 'Bonjour']));
    $_SESSION['lang'] = 'fr_FR'; // important pour Lang::setLocale

    file_put_contents($this->templatePath . 'tests/lang.dtf', '{{ lang("hello") }}');
    $output = $this->engine->render('tests.lang');
    expect($output)->toBe('Bonjour');

    unlink($langPath);
});

test('applies custom filter registered with registerFilter()', function () {
    $this->engine->registerFilter('reverse', fn($val) => strrev($val));

    file_put_contents($this->templatePath . 'tests/custom_filter.dtf', 'Welcome: {{ name|reverse }}');
    $output = $this->engine->render('tests.custom_filter', ['name' => 'Clara']);
    expect($output)->toBe('Welcome: aralC');
});



test('throws error on missing template file', function () {
    expect(fn () => $this->engine->render('tests.missing'))->toThrow(Exception::class);
});

test('handles unclosed section gracefully', function () {
    file_put_contents($this->templatePath . 'tests/layout.dtf', 'Start @yield("body") End');
    file_put_contents($this->templatePath . 'tests/unclosed_section.dtf', '@extends("tests.layout") @section("body")Never closed');
    $output = $this->engine->render('tests.unclosed_section');
    expect($output)->toBe('Start  End');
});
