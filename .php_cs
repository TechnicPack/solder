<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/app');

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'general_phpdoc_annotation_remove' => ['internal'],
        'not_operator_with_successor_space' => true,
        'no_empty_comment' => false,
        'phpdoc_align' => false,
        'phpdoc_order' => true,
    ])
    ->setFinder($finder);
