<?php

$env = [
    'stack' => [],
    'words' => [
        '.' => function(&$stack) {
            $x = array_pop($stack);
            echo $x;
        },
        '.s' => function(&$stack) {
            if (is_array($stack)) {
                echo '<' . count($stack) . '>';
                foreach ($stack as $value) {
                    echo ' ' . $value;
                }
            }
        },
        '+' => function(&$stack) {
            $y = array_pop($stack);
            $x = array_pop($stack);
    
            $result = $x + $y;
            array_push($stack, $result);
        },
        '-' => function(&$stack) {
            $y = array_pop($stack);
            $x = array_pop($stack);
    
            $result = $x - $y;
            array_push($stack, $result);
        }, 
        '*' => function(&$stack) {
            $y = array_pop($stack);
            $x = array_pop($stack);
    
            $result = $x * $y;
            array_push($stack, $result);
        }, 
        '/' => function(&$stack) {
            $y = array_pop($stack);
            $x = array_pop($stack);
    
            $result = $x / $y;
            array_push($stack, $result);
        }, 
        'dup' => function(&$stack) {
            $x = array_pop($stack);
            array_push($stack, $x);
            array_push($stack, $x);
        }, 
        'emit' => function(&$stack) {
            $x = array_pop($stack);
            echo chr($x);
        }, 
        'swap' => function(&$stack) {
            $y = array_pop($stack);
            $x = array_pop($stack);
            array_push($stack, $y);
            array_push($stack, $x);
        }, 
        'over' => function(&$stack) {
            $y = array_pop($stack);
            $x = array_pop($stack);
            array_push($stack, $x);
            array_push($stack, $y);
            array_push($stack, $x);
        }, 
    ],
    'definition_mode' => false,
    'definition_stack' => [],    
];

function repl(&$env) {
    while(1) {
        echo 'phpforth> ';
        eval_str($env, str_replace("\r\n", '', fgets(STDIN)));
        echo PHP_EOL;
    }
}

function eval_str(&$env, $str) {
    $tokens = explode(' ', $str);

    foreach ($tokens as $key => $token) {
        // echo "[debug]({$key}) : token='{$token}' stack=" . print_r($env['stack'], true) . PHP_EOL;

        if ($env['definition_mode']) {
            if ($token === ';') {
                $name = array_shift($env['definition_stack']);
                $definitions = $env['definition_stack'];
                $env['words'][$name] = function() use (&$env, $definitions) {
                    foreach($definitions as $definition) {
                        $env['words'][$definition]($env['stack']);
                    }
                };

                $env['definition_mode'] = false;
                $env['definition_stack'] = [];
            } else {
                array_push($env['definition_stack'], $token);
            }
        } else {
            if ($token === ':') {
                $env['definition_mode'] = true;
            } else if (isset($env['words'][$token])) {
                $env['words'][$token]($env['stack']);
            } else if (is_numeric($token)) {
                array_push($env['stack'], $token);
            } else {
                ;
            }
        }
    }
}

if (!defined('TEST_MODE')) {
    repl($env);
}