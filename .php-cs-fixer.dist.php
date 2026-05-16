<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer:risky' => true,
        '@Symfony' => true,
        'native_function_invocation' => ['include' => ['@all']],
        'declare_strict_types' => true,
    ])
    ->setFinder($finder)
;
