<?php

function getData($filename = "a_example.in")
{
    $rows = file($filename);

    $rawFileRows = [];
    foreach ($rows as $rawRow) {
        $rawFileRows[] = explode(' ', $rawRow);
    }

    list($rows, $columns, $cars, $ridesCount, $bonus, $steps) = $rawFileRows[0];

    unset($rawFileRows[0]);

    $rides = [];

    foreach ($rawFileRows as $index => $row) {
        $rides[$index - 1] = [
            'index' => $index - 1,
            'from' => [$row[0], $row[1]],
            'to' =>  [$row[2], $row[3]],
            'start' => $row[4],
            'finish' => $row[5],
        ];
    }

    return [$rows, $columns, $cars, $ridesCount, $bonus, $steps, $rides];
}
