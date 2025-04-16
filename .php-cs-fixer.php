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
        // 'array_indentation' => true,
        // 'array_syntax' => ['syntax' => 'short'],
        // 'combine_consecutive_unsets' => true,
        // 'multiline_whitespace_before_semicolons' => false,
        // 'single_quote' => true,
        // 'binary_operator_spaces' => [
        //     'operators' => [
        //         '=>' => 'single_space',
        //         '=' => 'single_space',
        //     ],
        // ],
        // 'blank_line_before_statement' => [
        //     'statements' => ['return'], // Replaces 'blank_line_before_return' which was removed in newer versions.
        // ],
        // 'braces' => [
        //     'allow_single_line_closure' => true,
        // ],
        // 'concat_space' => ['spacing' => 'none'],
        // 'declare_equal_normalize' => ['space' => 'none'],
        // 'function_typehint_space' => true,
        // 'single_line_comment_style' => [
        //     'comment_types' => ['hash'],
        // ],
        // 'include' => true,
        // 'lowercase_cast' => true,
        // 'no_blank_lines_after_phpdoc' => true,
        // 'no_empty_comment' => true,
        // 'no_empty_phpdoc' => true,
        // 'no_extra_blank_lines' => [
        //     'tokens' => [
        //         'curly_brace_block',
        //         'extra',
        //         'parenthesis_brace_block',
        //         'square_brace_block',
        //         'throw',
        //         'use',
        //     ],
        // ],
        // 'no_multiline_whitespace_around_double_arrow' => true,
        // 'no_spaces_around_offset' => true,
        // 'no_unused_imports' => true,
        // 'no_whitespace_before_comma_in_array' => true,
        // 'no_whitespace_in_blank_line' => true,
        // 'object_operator_without_whitespace' => true,
        // 'single_blank_line_before_namespace' => true,
        // 'not_operator_with_space' => true, // Updated rule name from 'logical_not_operators_with_spaces'.
        // 'ordered_imports' => ['sort_algorithm' => 'alpha'], // Added sorting algorithm option for better control.
        // 'ternary_operator_spaces' => true,
        // 'trailing_comma_in_multiline' => ['elements' => ['arrays']], // Updated rule name from 'trailing_comma_in_multiline_arrays'.
        // 'trim_array_spaces' => true,
        // 'unary_operator_spaces' => true,
        // 'whitespace_after_comma_in_array' => true,
    ])
    ->setIndent('    ')
    ->setLineEnding("\n");
