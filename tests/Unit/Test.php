<?php

require 'app/forth.php';

function test_eval_str($input, $expected) {
    ob_start();
    eval_str($input);
    $actual = ob_get_clean();
    assertEquals($expected, $actual);
}

test('+', function() {
    test_eval_str("10 20 + .", "30");
});

test('-', function() {
    test_eval_str("30 20 - .", "10");
});

test('*', function() {
    test_eval_str("10 20 * .", "200");
});

test('/', function() {
    test_eval_str("200 20 / .", "10");
});

test('Multiple', function() {
    test_eval_str("10 30 + 30 20 - * 40 / .", "10");
    test_eval_str("10 20 30 + 5 + * .", "550");
});

test('emit', function() {
    test_eval_str("65 66 emit", "B");
});

test('dup', function() {
    test_eval_str("2 dup * .", "4");
});

test(':', function() {
    test_eval_str(": cube dup dup * * ; 4 cube .", "64");
});