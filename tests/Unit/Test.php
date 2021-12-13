<?php

define('TEST_MODE', 1);
require 'app/forth.php';

function test_eval_str($_this, $input, $expected) {
    global $env;
    $_env = $env; // 環境引き継がないようにする

    ob_start();
    eval_str($_env, $input);
    $actual = ob_get_clean();
    $_this->assertEquals($expected, $actual);
}

test('+', function() {
    test_eval_str($this, "10 20 + .", "30");
});

test('-', function() {
    test_eval_str($this, "30 20 - .", "10");
});

test('*', function() {
    test_eval_str($this, "10 20 * .", "200");
});

test('/', function() {
    test_eval_str($this, "200 20 / .", "10");
});

test('Multiple', function() {
    test_eval_str($this, "10 30 + 30 20 - * 40 / .", "10");
    test_eval_str($this, "10 20 30 + 5 + * .", "550");
});

test('emit', function() {
    test_eval_str($this, "65 66 emit", "B");
});

test('dup', function() {
    test_eval_str($this, "2 dup * .", "4");
});

test(':', function() {
    test_eval_str($this, ": cube dup dup * * ; 4 cube .", "64");
});

test('swap', function() {
    test_eval_str($this, "3 4 swap .s", "<2> 4 3");
});

test('over', function() {
    test_eval_str($this, "3 4 over .s", "<3> 3 4 3");
});

test('fib', function() {
    // https://qiita.com/iigura/items/2a34303d73dd4e7b0184
    test_eval_str($this, "1 1 swap over + dup . swap over + dup . swap over + dup . swap over + dup .", "2358");
});
