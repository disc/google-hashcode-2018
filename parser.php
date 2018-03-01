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
        $rides[$index] = [
            'from' => [$row[0], $row[1]],
            'to' =>  [$row[2], $row[3]],
            'start' => $row[4],
            'finish' => $row[5],
        ];
    }

    return [
        'rows' => $rows,
        'columns' => $columns,
        'cars' => $cars,
        'ridesCount' => $ridesCount,
        'bonus' => $bonus,
        'steps' => $steps,
        'rides' => $rides,
    ];
}
