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

                $bestRide = $availableRides[0];

                if (!$bestRide) {
                    break;
                }

                unset($this->rides[$bestRide['index']]);

                if (!array_key_exists($i, $cars)) {
                    $cars[$i] = [];
                }

                $cars[$i][] = $bestRide['index'];
                $step += $availableRides[0]['spendSteps'];
            }
        }

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
      foreach ($this->rides as &$rr) {
        $rr['actual'] = $rr['finish'];
      }
      usort($this->rides, function($a, $b) {
        if ($a['start'] == $b['start']) return 0;
        return $a['start'] > $b['start'] ? 1 : -1;
      });
      $map = [];
      foreach ($this->rides as $key => $value) {
        $map[$value['index']] = $key;
      }
      foreach ($this->rides as $num => &$r) {
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
            $hasFin = $this->hasTimeToFinish($r, $this->steps - 1);
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
      $dist = $this->nextDist($next);
      return max($minStart, $next['start']) + $dist;
    }

    public function hasTimeToPickUp($prev, $next)
    {
      // var_dump($prev);
      $dist = abs($prev['to'][0] - $next['from'][0]) + abs($prev['to'][1] - $next['from'][1]);
      $nextDist = $this->nextDist($next);
      $superTime = $next['start'] - min($prev['actual'], $prev['finish']);
      $regTime = $next['finish'] - $nextDist - min($prev['actual'], $prev['finish']);
      return [$superTime >= $dist, $regTime >= $dist];
    }

    public function nextDist($next)
    {
      return abs($next['from'][0] - $next['to'][0]) + abs($next['from'][1] - $next['to'][1]);
    }

    public function hasTimeToFinish($next, $total)
    {
      $dist = $this->nextDist($next);
      $time = $total - $next['start'];
      return $time >= $dist;
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
                $ride['start'] <= $currentStep + $distToGuy
                && $ride['finish'] >= $currentStep + $distToGuy + $distToFinish
            ) {
                $ride['coeff'] = $this->getCoeffs($carLocation, $ride['from'], $ride['to']);
                $ride['spendSteps'] = $distToGuy + $distToFinish;
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
