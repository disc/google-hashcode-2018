<?php

function toString(array $result) {
  $strings = [];
  foreach ($result as $carId => $rideIds) {
    // if ($rideIds) {
      $strings[] = count($rideIds) . ' ' . implode(' ', $rideIds);
    // }
  }
  return implode(PHP_EOL, $strings);
}

function save($filename, array $result) {
  $data = toString($result);
  if ($data == "") {
    $data = "\n";
  }
  if (!file_put_contents($filename, $data)) {
    throw new Exception('Can\'t save');
  }
}
