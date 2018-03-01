<?php

ini_set('memory_limit','6000M');

require_once __DIR__ . '/parser.php';
require_once __DIR__ . '/result.php';
require_once __DIR__ . '/calc.php';
require_once __DIR__ . '/Balancer.php';

$files = [
  'a_example.in',
  'b_should_be_easy.in',
  'c_no_hurry.in',
  'd_metropolis.in',
  'e_high_bonus.in',
];

$algo = $argv[1];
@mkdir(__DIR__ . '/output/');
$dir = __DIR__ . '/output/' . $algo . '/';
@mkdir($dir);

foreach ($files as $f) {
  $input = __DIR__ . '/input/' . $f;
  list($rows, $columns, $cars, $ridesCount, $bonus, $steps, $rides) = getData($input);

  $balancer = new Balancer($rows, $columns, $cars, $ridesCount, $bonus, $steps, $rides);
  $balancer->$algo();
  $result = $balancer->getResult();

  // $calc = new Calculator($endpoints, $result);
  // $score = $calc->getScore();
  // var_dump($score);

  $filename = $dir . $f;
  save($filename . '.out', $result);
}
