<?php

function eval_str($str) {
    $tokens = explode(' ', $str);

    $stack = [];
    $words = [
        '.' => function() use (&$stack) {
            $x = array_pop($stack);
            echo $x;
        },
        '.s' => function() use (&$stack) {
            if (is_array($stack)) {
                echo '<' . count($stack) . '>';
                foreach ($stack as $value) {
                    echo ' ' . $value;
                }
            }
        },
        '+' => function() use (&$stack) {
            $y = array_pop($stack);
            $x = array_pop($stack);
    
            $result = $x + $y;
            array_push($stack, $result);
        },
        '-' => function() use (&$stack) {
            $y = array_pop($stack);
            $x = array_pop($stack);
    
            $result = $x - $y;
            array_push($stack, $result);
        }, 
        '*' => function() use (&$stack) {
            $y = array_pop($stack);
            $x = array_pop($stack);
    
            $result = $x * $y;
            array_push($stack, $result);
        }, 
        '/' => function() use (&$stack) {
            $y = array_pop($stack);
            $x = array_pop($stack);
    
            $result = $x / $y;
            array_push($stack, $result);
        }, 
        'dup' => function() use (&$stack) {
            $x = array_pop($stack);
            array_push($stack, $x);
            array_push($stack, $x);
        }, 
        'emit' => function() use (&$stack) {
            $x = array_pop($stack);
            echo chr($x);
        }, 
        'swap' => function() use (&$stack) {
            $y = array_pop($stack);
            $x = array_pop($stack);
            array_push($stack, $y);
            array_push($stack, $x);
        }, 
        'over' => function() use (&$stack) {
            $y = array_pop($stack);
            $x = array_pop($stack);
            array_push($stack, $x);
            array_push($stack, $y);
            array_push($stack, $x);
        }, 
    ];

    $definition_mode = false;
    $definition_stack = [];
    
    foreach ($tokens as $key => $token) {
        // echo "[debug]({$key}) : token='{$token}' stack=" . print_r($stack, true) . PHP_EOL;

        if ($definition_mode) {
            if ($token === ';') {
                $name = array_shift($definition_stack);
                $definitions = $definition_stack;
                $words[$name] = function() use (&$stack, $words, $definitions) {
                    foreach($definitions as $definition) {
                        $words[$definition]();
                    }
                };

                $definition_mode = false;
                $definition_stack = [];
            } else {
                array_push($definition_stack, $token);
            }
        } else {
            if ($token === ':') {
                $definition_mode = true;
            } else if (isset($words[$token])) {
                $words[$token]();
            } else if (is_numeric($token)) {
                array_push($stack, $token);
            } else {
                ;
            }
        }
    }
}
