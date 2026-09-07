<?php

$vars = [
    'DB_NAME',
    'DB_USER',
    'DB_PASS',
    'INSTANCE_CONNECTION_NAME'
];

foreach ($vars as $var) {
    $value = getenv($var);

    echo $var . ': ' . ($value === false || $value === '' ? 'MISSING' : 'SET') . '<br>';
}

 
