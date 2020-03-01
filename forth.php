<?php

test_eval_str("10 20 + .", "30");
test_eval_str("30 20 - .", "10");
test_eval_str("10 20 * .", "200");
test_eval_str("200 20 / .", "10");
test_eval_str("10 30 + 30 20 - * 40 / .", "10");
test_eval_str("10 20 30 + 5 + * .", "550");
test_eval_str("65 66 emit", "B");
test_eval_str("2 dup * .", "4");
test_eval_str(": cube dup dup * * ; 4 cube .", "64");

function eval_str($str) {
    $tokens = explode(' ', $str);

    $stack = [];
    $words = [
        '.' => function() use (&$stack) {
            $x = array_pop($stack);
            echo $x;
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

function test_eval_str($input, $expected) {
    ob_start();
    eval_str($input);
    $actual = ob_get_clean();
    if ($actual == $expected) {
        echo "OK(eval_str({$input}) => {$actual} == {$expected})" . PHP_EOL;
    } else {
        echo "NG(eval_str({$input}) => {$actual} != {$expected})" . PHP_EOL;
    }
}