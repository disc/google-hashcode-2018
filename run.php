<?php

ini_set('memory_limit','6000M');

require_once __DIR__ . '/parser.php';
require_once __DIR__ . '/result.php';
require_once __DIR__ . '/calc.php';
require_once __DIR__ . '/Balancer.php';

$algo = $argv[2];
$filename = __DIR__ . '/' . $argv[1];

list($rows, $columns, $cars, $ridesCount, $bonus, $steps, $rides) = getData($filename);

$balancer = new Balancer($rows, $columns, $cars, $ridesCount, $bonus, $steps, $rides);
$balancer->$algo();
$result = $balancer->getResult();

$calc = new Calculator($endpoints, $result);
$score = $calc->getScore();
var_dump($score);

save($filename . '.out', $result);
