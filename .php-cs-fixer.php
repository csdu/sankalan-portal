<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/resources',
        __DIR__ . '/tests',
    ])
    ->exclude([
        'storage',
        'bootstrap/cache',
    ])
    ->notName('*.blade.php');

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRules([
        '@PSR12' => true,
    ])
    ->setIndent('    ')
    ->setLineEnding("\n");
