<?php

// $str = "10 20 + .";
// eval_str($str);

test_eval_str("10 20 + .", "30");
test_eval_str("30 20 - .", "10");
test_eval_str("10 20 * .", "200");
test_eval_str("200 20 / .", "10");
test_eval_str("10 30 + 30 20 - * 40 / .", "10");
test_eval_str("10 20 30 + 5 + * .", "550");
test_eval_str("65 66 emit", "B");

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
        'emit' => function() use (&$stack) {
            $x = array_pop($stack);
            echo chr($x);
        }, 
    ];
    foreach ($tokens as $key => $token) {
        // echo "[debug]({$key}) : token='{$token}' stack=" . print_r($stack, true) . PHP_EOL;
    
        if (isset($words[$token])) {
            $words[$token]();
        } else if (is_numeric($token)) {
            array_push($stack, $token);
        } else {
            ;
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