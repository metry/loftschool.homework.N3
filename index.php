<?php

require('src/functions.php');

//Task 1
task1('error.xml');
task1('data/data.xml');
echo '<hr>';

//Task2
//формируем output.json
$path = 'data/output.json';
$array = [
    'name' => 'Dmitry',
    'age' => '27',
    'city' => 'Moscow',
    'phone' => ['89161111111', '89162222222', '89163333333'],
    'email'=>['main' => 'main@google.com', 'second' => 'second@google.com', 'third' => 'third@google.com']
];
file_put_contents($path, json_encode($array));
task2($path);
echo '<hr>';


//Task3
task3();
echo '<hr>';

//Task4
task4('https://en.wikipedia.org/w/api.php?action=query&titles=Main%20Page&prop=revisions&rvprop=content&format=json');
echo '<hr>';
