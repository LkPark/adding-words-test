<?php

namespace App\Services;

class ArithService
{
    const SINGLE_DIGISTS = 'single_digits';
    const TWO_DIGISTS = 'twp_digits';
    const TENS_MULTIPLE = 'tens_multiple';
    const TENS_POWER = 'tens_power';
    const SKIP_WORDS = 'skip_words';

    const SUM = 'sum';
    const MULTIPLY = 'multiply';
    const SKIP_OPERATION = 'skip_operation';

    private $dictionaryList = [
        self::SINGLE_DIGISTS => [
            'zero' => 0,
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6,
            'seven' => 7,
            'eight' => 8,
            'nine' => 9
        ],
        self::TWO_DIGISTS => [
            'ten' => 10,
            'eleven' => 11,
            'twelve' => 12,
            'thirteen' => 13,
            'fourteen' => 14,
            'fifteen' => 15,
            'sixteen' => 16,
            'seventeen' => 17,
            'eighteen' => 18,
            'nineteen' => 19
        ],
        self::TENS_MULTIPLE => [
            'twenty' => 20,
            'thirty' => 30,
            'forty' => 40,
            'fifty' => 50,
            'sixty' => 60,
            'seventy' => 70,
            'eighty' => 80,
            'ninety' => 90
        ],
        self::TENS_POWER => [
            'hundred' => 100
        ],
        self::SKIP_WORDS => [
            'and' => -1
        ]
    ];

    private $operationMap = [
        self::SUM => [
            self::SINGLE_DIGISTS,
            self::TWO_DIGISTS,
            self::TENS_MULTIPLE
        ],
        self::MULTIPLY => [
            self::TENS_POWER
        ],
        self::SKIP_OPERATION => [
            self::SKIP_WORDS
        ]
    ];

    private $sum = 0;

    public function __construct($firstStrNum = false)
    {
        $this->validateInputNumber($firstStrNum);
        $this->sum = $this->stringToNumber($this->cleanInput($firstStrNum));
    }

    public function add($secondStrNum = false)
    {
        $this->validateInputNumber($secondStrNum);
        $this->sum += $this->stringToNumber($this->cleanInput($secondStrNum));
        return $this->numberToString($this->sum);
    }

    private function validateInputNumber($number)
    {
        if ($number === false) {
            throw new \Exception('Input must not be empty');
        }
        if (!is_string($number)) {
            throw new \Exception('Input type must be string');
        }
    }

    private function stringToNumber($strNumb)
    {
        if ($strNumb === '') {
            return 0;
        }
        $values = explode(' ', $strNumb);
        $this->validateInDictionaryList($values);
        $grammar = $this->stringToGrammar($values);
        return $this->grammarToNumber($grammar);
    }

    private function numberToString($number)
    {
        $numberList = [];

        $digitsLen = $this->getNumberDigitsLength($number);
        while ($digitsLen > 0) {
            $addAnd = false;

            if ($number < 20) {
                $numberList[] = $number;
                $digitsLen -= 2;
                continue;
            }

            $power = pow(10, $digitsLen - 1);
            $last = $number % $power;
            $toPush = $number - $last;

            if ($last === 0) {
                $toPush = $number;
                $digitsLen = 0;
            }

            if ($toPush > 99) {
                $tmp = ceil($toPush / $power);
                if ($last > 0) {
                    $addAnd = true;
                }
                $numberList[] = $tmp;
                $toPush = $power;
            }

            $numberList[] = $toPush;
            if ($addAnd) {
                $numberList[] = -1;
            }
            $number = $last;
            $digitsLen--;
        }
        $strResult = [];
        foreach ($numberList as $number) {
            $strResult[] =  $this->getStrNumber($number);
        }
        return implode(' ', $strResult);
    }

    private function validateInDictionaryList($values = [])
    {
        $foundNr = 0;
        foreach ($this->dictionaryList as $dictionary) {
            $foundNr += count(array_intersect(array_keys($dictionary), $values));
        }
        if ($foundNr !== count($values)) {
            throw new \Exception('Input is not a number or not found in dictionary');
        }
    }

    private function stringToGrammar($values)
    {
        $tmp = [];
        foreach ($values as $index => $strNumb) {
            $dictionary = $this->getDictionary($strNumb);
            $number = $this->getStrNumberValue($dictionary, $strNumb);
            if ($index === 0) {
                $tmp[] = $number;
                continue;
            }
            $tmp[] = $this->getOperation($dictionary);
            $tmp[] = $number;
        }
        return $tmp;
    }

    private function grammarToNumber($grammar)
    {
        $number = $prevAction = false;
        foreach ($grammar as $index => $item) {
            if ($index === 0) {
                $number = $item;
                continue;
            }
            if ($item === self::SUM || $item === self::MULTIPLY || $item === self::SKIP_OPERATION) {
                $prevAction = $item;
                continue;
            }
            if ($prevAction === self::SUM) {
                $number += $item;
            }
            if ($prevAction === self::MULTIPLY) {
                $number *= $item;
            }
            $prevAction = false;
        }
        return $number;
    }

    private function getOperation($dictionary)
    {
        foreach ($this->operationMap as $operation => $maps) {
            if (in_array($dictionary, $maps)) {
                return $operation;
            }
        }
    }

    private function getDictionary($strNumb)
    {
        foreach ($this->dictionaryList as $dictionary => $dictionaryValues) {
            if (array_key_exists($strNumb, $dictionaryValues)) {
                return $dictionary;
            }
        }
    }

    private function getStrNumber($number)
    {
        foreach ($this->dictionaryList as $dictionary) {
            $strNumber = array_search($number, $dictionary);
            if ($strNumber !== false) {
                return $strNumber;
            }
        }
        return false;
    }

    private function getStrNumberValue($dictionary, $strNumb)
    {
        return $this->dictionaryList[$dictionary][$strNumb];
    }

    private function getNumberDigitsLength($number)
    {
        return strlen((string)$number);
    }

    private function cleanInput($input)
    {
        return trim($input);
    }
}
