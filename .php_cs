<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('bin')
    ->exclude('vendor')
    ->exclude('tests/fixtures')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'visibility_required' => false,
        'array_syntax' => [
            'syntax' => 'short',
        ],
    ])
    ->setUsingCache(true)
    ->setFinder($finder)
;
