<?php

$finder = (new PhpCsFixer\Finder())->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'array_indentation' => true,
        'binary_operator_spaces' => [
            'default' => 'align',
            'operators' => [
                '+' => 'align_single_space_minimal',
            ],
        ],
        'indentation_type' => true,
        'no_useless_return' => true,
        'magic_constant_casing' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'curly_brace_block',
                'return',
            ],
        ],
        'trailing_comma_in_multiline' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
