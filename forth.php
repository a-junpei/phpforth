<?php

// $str = "10 20 + .";
// eval_str($str);

test_eval_str("10 20 + .", "30");
test_eval_str("30 20 - .", "10");
test_eval_str("10 20 * .", "200");
test_eval_str("200 20 / .", "10");
test_eval_str("10 30 + 30 20 - * 40 / .", "10");
test_eval_str("10 20 30 + 5 + * .", "550");


function eval_str($str) {
    $tokens = explode(' ', $str);

    $stack = array();
    foreach ($tokens as $key => $token) {
        // echo "[debug]({$key}) : token='{$token}' stack=" . print_r($stack, true) . PHP_EOL;
    
        if ($token == '.') {
            $x = array_pop($stack);
            echo $x;
        } else if ($token == '+') {
            $y = array_pop($stack);
            $x = array_pop($stack);
    
            $result = $x + $y;
            array_push($stack, $result);
        } else if ($token == '-') {
            $y = array_pop($stack);
            $x = array_pop($stack);
    
            $result = $x - $y;
            array_push($stack, $result);
        } else if ($token == '*') {
            $y = array_pop($stack);
            $x = array_pop($stack);
    
            $result = $x * $y;
            array_push($stack, $result);
        } else if ($token == '/') {
            $y = array_pop($stack);
            $x = array_pop($stack);
    
            $result = $x / $y;
            array_push($stack, $result);
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