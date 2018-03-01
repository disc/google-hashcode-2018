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


    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }
}
