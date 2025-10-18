<?php 

function dd($values) {
    echo '<pre>';
    var_dump($values);
    echo '</pre>';

    die();
}

function urls ($values) {
    return $_SERVER['REQUEST_URI'] === $values;
}