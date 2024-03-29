<?php

/*
 * This file is part of the php-defer/php-defer package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$finder = PhpCsFixer\Finder::create()
    ->in(['src', 'tests'])
    ->name(['*.php', '*.phpt'])
    ->notName(['change_returned_value.phpt'])
;

$header = <<<'HEADER'
This file is part of the php-defer/php-defer package.

(c) Bartłomiej Krukowski <bartlomiej@krukowski.me>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
HEADER;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        'header_comment' => ['header' => $header],
        'native_function_invocation' => ['exclude' => [], 'include' => ['@internal'], 'scope' => 'all', 'strict' => true],
        'native_constant_invocation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],
        'static_lambda' => false,
    ])
    ->setFinder($finder)
;
