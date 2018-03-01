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
            'from' => [(int) $row[0], (int) $row[1]],
            'to' =>  [(int) $row[2], (int) $row[3]],
            'start' => (int) $row[4],
            'finish' => (int) $row[5],
        ];
    }

    return [$rows, $columns, $cars, $ridesCount, $bonus, $steps, $rides];
}
