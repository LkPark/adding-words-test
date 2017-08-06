<?php

namespace Unit\Services;

use App\Services\ArithService;
use PHPUnit_Framework_TestCase;

class ArithServiceTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Input must not be empty
     */
    public function testConstructorEmptyInput()
    {
        new ArithService();
    }

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Input type must be string
     */
    public function testConstructorInputTypeIsNotString()
    {
        new ArithService(1234);
    }

    /**
     * @test
     */
    public function testConstructorInputTypeIsString()
    {
        new ArithService('zero');
    }

    /**
     * @test
     */
    public function testConstructorInputUsingEmptyString()
    {
        new ArithService('');
    }

    /**
     * @test
     */
    public function testMethodAddExist()
    {
        $this->assertTrue(method_exists(new ArithService(''), 'add'), 'Class does not have method add');
    }

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Input must not be empty
     */
    public function testAddMethodEmptyInput()
    {
        $arithInst = new ArithService('');
        $arithInst->add();
    }

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Input type must be string
     */
    public function testAddMethodInputTypeIsNotString()
    {
        $arithInst = new ArithService('');
        $arithInst->add(1234);
    }

    /**
     * @test
     */
    public function testAddMethodInputTypeIsString()
    {
        $arithInst = new ArithService('');
        $arithInst->add('zero');
    }

    /**
     * @test
     */
    public function testAddMethodInputUsingEmptyString()
    {
        $arithInst = new ArithService('');
        $arithInst->add('');
    }

    /**
     * @test
     */
    public function testAddResultUsingEmptyStringInputs()
    {
        $arithInst = new ArithService('');
        $this->assertEquals('zero', $arithInst->add(''));
    }

    /**
     * @test
     */
    public function testAddResultUsingEmptyStringInputsWithSpaces()
    {
        $arithInst = new ArithService('   ');
        $this->assertEquals('zero', $arithInst->add('   '));
    }

    /**
     * @test
     */
    public function testOneOfInputEmptyStringWillReturnInitialValue()
    {
        $arithInstFirst = new ArithService('zero');
        $this->assertEquals('zero', $arithInstFirst->add(''));

        $arithInstSecond = new ArithService('');
        $this->assertEquals('zero', $arithInstSecond->add('zero'));
    }

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Input is not a number or not found in dictionary
     */
    public function testIfConstructorInputIsNotNumberOrNotFoundInDictionary()
    {
        new ArithService('lkpark');
    }

    /**
     * @test
     */
    public function testIfConstructorInputIsNumber()
    {
        new ArithService('zero');
    }

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Input is not a number or not found in dictionary
     */
    public function testIfAddMethodInputIsNotNumberOrNotFoundInDictionary()
    {
        $arithInstFirst = new ArithService('zero');
        $arithInstFirst->add('lkpark');
    }

    /**
     * @test
     */
    public function testIfAddMethodInputIsNumber()
    {
        $arithInstFirst = new ArithService('zero');
        $arithInstFirst->add('zero');
    }

    /**
     * @test
     */
    public function testAddSingleStringDigitsNumberWithSingleDigitNumberResponse()
    {
        $arithInstFirst = new ArithService('one');
        $this->assertEquals('three', $arithInstFirst->add('two'));

        $arithInstFirst = new ArithService('ten');
        $this->assertEquals('thirty', $arithInstFirst->add('twenty'));

        $arithInstFirst = new ArithService('twenty');
        $this->assertEquals('sixty', $arithInstFirst->add('forty'));
    }

    /**
     * @test
     */
    public function testAddTwoDigitsNumberWithSeparatedTwoDigitResponse()
    {
        $arithInstFirst = new ArithService('twenty one');
        $this->assertEquals('forty three', $arithInstFirst->add('twenty two'));

        $arithInstFirst = new ArithService('fifty five');
        $this->assertEquals('eighty seven', $arithInstFirst->add('thirty two'));

        $arithInstFirst = new ArithService('seventy two');
        $this->assertEquals('ninety four', $arithInstFirst->add('twenty two'));
    }

    /**
     * @test
     */
    public function testAddThreeDigitsNumberWithSeparatedThreeDigitResponse()
    {
        $arithInstFirst = new ArithService('one hundred and five');
        $this->assertEquals('three hundred and six', $arithInstFirst->add('two hundred and one'));

        $arithInstFirst = new ArithService('three hundred and two');
        $this->assertEquals('six hundred and eight', $arithInstFirst->add('three hundred and six'));

        $arithInstFirst = new ArithService('two hundred and twenty one');
        $this->assertEquals('eight hundred and twelve', $arithInstFirst->add('five hundred and ninety one'));
    }

    /**
     * @test
     */
    public function testWithEnvironmentVariables()
    {
        $arithConstructValue = getenv('ARITH_CONSTRUCT_VALUE');
        $arithAddValue = getenv('ARITH_ADD_VALUE');
        $arithResult = getenv('ARITH_RESULT');

        if (!$arithConstructValue || !$arithAddValue || !$arithResult) {
            echo PHP_EOL;
            echo 'To check your personal inputs please follow README.md instructions';
            echo PHP_EOL;
            return;
        }

        $arithInst = new ArithService($arithConstructValue);
        $this->assertEquals($arithResult, $arithInst->add($arithAddValue));
    }

}
