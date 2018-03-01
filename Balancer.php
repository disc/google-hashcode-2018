<?php

class Balancer
{
    protected $result = [];

    /**
     * @var int
     */
    protected $rowsCount;

    /**
     * @var int
     */
    protected $columnsCount;

    /**
     * @var int
     */
    protected $carsCount;

    /**
     * @var int
     */
    protected $ridesCount;

    /**
     * @var int
     */
    protected $bonus;

    /**
     * @var int
     */
    protected $steps;

    /**
     * @var array
     */
    protected $rides;

    public function __construct($rowsCount, $columnsCount, $carsCount, $ridesCount, $bonus, $steps, $rides)
    {
        $this->rowsCount = $rowsCount;
        $this->columnsCount = $columnsCount;
        $this->carsCount = $carsCount;
        $this->ridesCount = $ridesCount;
        $this->bonus = $bonus;
        $this->steps = $steps;
        $this->rides = $rides;
    }

    // stub
    public function none()
    {}

    public function sample()
    {
      $this->result = [
        2 => [3,4,5],
        4 => [5858],
      ];
    }

    public function example()
    {
        $this->result = [
            1 => [0],
            2 => [2, 1],
        ];
    }


    public function dumbfuck()
    {
      $len = min($this->carsCount, $this->ridesCount);
      for ($i = 0; $i < $len; $i++) {
        $this->result[] = [$i];
      }
    }

    public function coeffOfRideDeep()
    {
        $cars = [];

        for ($i = 0; $i < $this->carsCount; ++$i) {
            $currentLocation = [0, 0];

            for ($step = 0; $step < $this->steps; ++$step) {
                $availableRides = $this->getAvailableRides(
                    $this->rides,
                    $step,
                    $currentLocation
                );

                foreach ($availableRides as $ride) {

                }

//                $carFreeSteps = $this->steps;
            }
        }
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    protected function getAvailableRides($freeRides, $currentStep, $carLocation)
    {
        $availableRides = [];
        foreach ($freeRides as $ride) {
            $distToGuy = $this->getDistance($carLocation, $ride['from']);
            $distToFinish = $this->getDistance($ride['from'], $ride['to']);

            if (
                $ride['start'] >= $currentStep + $distToGuy
                && $ride['finish'] <= $currentStep + $distToGuy + $distToFinish
            ) {
                $ride['coeff'] = $this->getCoeffs($carLocation, $ride['start'], $ride['finish']);
                $availableRides[] = $ride;
            }
        }

        usort(
            $availableRides,
            function ($a, $b) {
                return $a['coeff'] >= $b['coeff']
                    ? 1
                    : 0;
            }
        );

        return $availableRides;
    }

    protected function getCoeffs($carCoord, $startCoord, $endCoord)
    {
        $distToStart = $this->getDistance($carCoord, $startCoord);
        $distStartFinish = $this->getDistance($startCoord, $endCoord);

        return $distToStart + $distStartFinish;
    }

    /**
     * @param $from [x, y]
     * @param $to [x, y]
     * @return int
     */
    protected function getDistance($from, $to)
    {
        return abs($from[0] - $to[0]) + abs($from[1] - $to[1]);
    }

}
