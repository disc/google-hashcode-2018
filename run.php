<?php

ini_set('memory_limit','6000M');

require_once __DIR__ . '/parser.php';
require_once __DIR__ . '/result.php';
require_once __DIR__ . '/calc.php';
require_once __DIR__ . '/Balancer.php';

$files = [
  'input/a_example.in',
  'input/b_should_be_easy.in',
  'input/c_no_hurry.in',
  'input/d_metropolis.in',
  'input/e_high_bonus.in',
];

foreach ($files as $f) {
  $algo = $argv[1];
  $filename = __DIR__ . '/' . $f;

  list($rows, $columns, $cars, $ridesCount, $bonus, $steps, $rides) = getData($filename);

  $balancer = new Balancer($rows, $columns, $cars, $ridesCount, $bonus, $steps, $rides);
  $balancer->$algo();
  $result = $balancer->getResult();

  // $calc = new Calculator($endpoints, $result);
  // $score = $calc->getScore();
  // var_dump($score);

  save($filename . '.out', $result);
}
