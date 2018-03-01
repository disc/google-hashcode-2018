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
        $this->rowsCount = (int) $rowsCount;
        $this->columnsCount = (int) $columnsCount;
        $this->carsCount = (int) $carsCount;
        $this->ridesCount = (int) $ridesCount;
        $this->bonus = (int) $bonus;
        $this->steps = (int) $steps;
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

    protected $stepsByCar = [];
    protected $carLocation = [];

    public function coeffOfRideDeep()
    {
        $cars = [];

        // width
        while (count($this->rides)) {
            $startCount = count($this->rides);

            for ($car = 0; $car < $this->carsCount; ++$car) {
                if (!array_key_exists($car, $cars)) {
                    $cars[$car] = [];
                }

                if (!array_key_exists($car, $this->stepsByCar)) {
                    $this->stepsByCar[$car] = 0;
                }

                if (!array_key_exists($car, $this->carLocation)) {
                    $this->carLocation[$car] = [0, 0];
                }

                if ($this->stepsByCar[$car] >= $this->steps) {
                    continue;
                }

                $availableRides = $this->getAvailableRides(
                    $this->rides,
                    $this->stepsByCar[$car],
                    $this->carLocation[$car],
                    $car
                );

                $bestRide = $availableRides[0];

                if (!$bestRide) {
                    continue;
                }

                unset($this->rides[$bestRide['index']]);

                $cars[$car][] = $bestRide['index'];
                $this->carLocation[$car] = $bestRide['to'];
                $this->stepsByCar[$car] += $bestRide['spendSteps'];
            }

            if ($startCount == count($this->rides)) {
                break;
            }
        }


        // Deep
//        for ($i = 0; $i < $this->carsCount; ++$i) {
//            if (!array_key_exists($i, $cars)) {
//                $cars[$i] = [];
//            }
//
//            if (!array_key_exists($i, $this->stepsByCar)) {
//                $this->stepsByCar[$i] = 0;
//            }
//
//            $currentLocation = [0, 0];
//
//            for ($step = 0; $step < $this->steps; ++$step) {
//                $availableRides = $this->getAvailableRides(
//                    $this->rides,
//                    $step,
//                    $currentLocation,
//                    $i
//                );
//
//                $bestRide = $availableRides[0];
//
////                if (!$bestRide) {
////                    break;
////                }
//
//                unset($this->rides[$bestRide['index']]);
//
//                $cars[$i][] = $bestRide['index'];
//                $currentLocation = $bestRide['to'];
//                $step += $bestRide['spendSteps'];
//            }

        $this->result = $cars;
    }

    public function dumbfuck2()
    {
      for ($i = 0; $i < $this->carsCount; $i++) {
        $this->result[] = [];
      }
      for ($j = 0; $j < $this->ridesCount; $j++) {
        $i = $j % $this->carsCount;
        $this->result[$i][] = $j;
      }
    }

    public function dumbfuck3()
    {
      for ($i = 0; $i < $this->carsCount; $i++) {
        $this->result[] = [];
      }
      usort($this->rides, function($a, $b) {
        if ($a['start'] == $b['start']) return 0;
        return $a['start'] > $b['start'] ? 1 : -1;
      });
      foreach ($this->rides as $num => $r) {
        $i = $num % $this->carsCount;
        $this->result[$i][] = $r['index'];
      }
      for ($j = 0; $j < $this->ridesCount; $j++) {
      }
    }

    public function smart()
    {
      for ($i = 0; $i < $this->carsCount; $i++) {
        $this->result[] = [];
      }
      $maxDist = 0;
      foreach ($this->rides as &$rr) {
        $rr['actual'] = $rr['finish'];
        $rr['dist'] = $this->nextDist($rr);
        if ($rr['dist'] > $maxDist) {
          $maxDist = $rr['dist'];
        }
      }
      usort($this->rides, function($a, $b) {
        if ($a['start'] == $b['start']) return 0;
        return $a['start'] > $b['start'] ? 1 : -1;
      });
      // $percent = 50;
      // $limit = 0;
      // usort($dists, function($a, $b) {
      //   if ($a['dist'] == $b['dist']) return 0;
      //   return $a['dist'] > $b['dist'] ? 1 : -1;
      // });
      // $index = round(count($this->rides) * $percent / 100);
      // var_dump(count($this->rides));
      // $this->rides = array_slice($this->rides, 0, $index);
      // var_dump(count($this->rides));
      $map = [];
      foreach ($this->rides as $key => $value) {
        $map[$value['index']] = $key;
      }
      foreach ($this->rides as $num => &$r) {
        if ($r['dist'] > $maxDist * 0.33) {
          // echo "$maxDist, {$r['dist']}\n";
          continue;
        }
        $carIndex = -1;
        for ($lap = 0; $lap < 2; $lap++) {
          $prev = null;
          foreach ($this->result as $i => $rds) {
            if (count($rds) == 0) {
              $prev = [
                'to' => [0, 0],
                'actual' => 0,
                'finish' => 0,
              ];
            } else {
              $ind = $rds[count($rds) - 1];
              $prev = $this->rides[$map[$ind]];
              // var_dump($prev);
            }
            list ($super, $reg) = $this->hasTimeToPickUp($prev, $r);
            $hasTime = $super || $reg && $lap == 1;
            $hasFin = $this->hasTimeToFinish($r, $this->steps);
            // $hasFin = true;
            if ($hasTime && $hasFin) {
              $carIndex = $i;
              break;
            }
          }
          if ($carIndex >= 0) {
            $r['actual'] = $this->calcActual($r, $prev['actual']);
            $this->result[$i][] = $r['index'];
            break;
          }
        }
      }
    }

    public function calcActual($next, $minStart)
    {
      return max($minStart, $next['start']) + $next['dist'];
    }

    public function hasTimeToPickUp($prev, $next)
    {
      // var_dump($prev);
      $dist = abs($prev['to'][0] - $next['from'][0]) + abs($prev['to'][1] - $next['from'][1]);
      $superTime = $next['start'] - $prev['actual'];
      $regTime = $next['finish'] - $next['dist'] - $prev['actual'];
      return [$superTime >= $dist, $regTime >= $dist];
    }

    public function nextDist($next)
    {
      return abs($next['from'][0] - $next['to'][0]) + abs($next['from'][1] - $next['to'][1]);
    }

    public function hasTimeToFinish($next, $total)
    {
      $time = min($total, $next['finish']) - $next['start'];
      return $time >= $next['dist'];
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    protected function getAvailableRides($freeRides, $currentStep, $carLocation, $car)
    {

        $availableRides = [];
        foreach ($freeRides as $ride) {
            $distToGuy = $this->getDistance($carLocation, $ride['from']);
            $distToFinish = $this->getDistance($ride['from'], $ride['to']);

            $stepsToStart = $currentStep + $distToGuy;
            $stepToFinish = $currentStep + $distToGuy + $distToFinish;

            if (
                $ride['start'] <= $stepsToStart
                && $stepToFinish < $ride['finish']
            ) {
                $ride['coeff'] = 0;
                $this->getCoeffs(
                    $carLocation,
                    $ride['from'],
                    $ride['to'],
                    0
                );
                $ride['spendSteps'] = $distToGuy + $distToFinish;
                $availableRides[] = $ride;


            } elseif ($stepToFinish <= $ride['finish']) {
                $waitSteips = $ride['start'] - $distToGuy;
                $ride['coeff'] = $this->getCoeffs($carLocation, $ride['from'], $ride['to'], $waitSteips);
                $ride['spendSteps'] = $waitSteips + $distToGuy + $distToFinish;
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

    protected function getCoeffs($carCoord, $startCoord, $endCoord, $waitSteips)
    {
        $distToStart = $this->getDistance($carCoord, $startCoord);
        $distStartFinish = $this->getDistance($startCoord, $endCoord);

        return $distToStart + $distStartFinish + $waitSteips;
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
