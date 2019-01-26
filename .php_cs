<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
    ->in(__DIR__.'/test_server')
;



return PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules(
            [
                '@PSR1' => true,
                '@PSR2' => true,
                '@PhpCsFixer' => true,
                '@PHP71Migration' => true,
                '@PHP71Migration:risky' => true,
                'native_function_invocation' => array(
                    'scope' => 'namespaced',
                    'strict' => true,
                ),
            ]
        )
;