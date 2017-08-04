<?php

namespace App\Services;

class ArithService
{
    private $firstStrNum;

    public function __construct($firstStrNum = false)
    {
        if ($firstStrNum === false) {
            throw new \Exception('Empty constructor param');
        }

        if (!is_string($firstStrNum)) {
            throw new \Exception('Constructor param must be string');
        }

        $this->firstStrNum = $firstStrNum;
    }

    public function add()
    {

    }
}
