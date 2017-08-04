<?php

namespace Unit\Services;

use App\Services\ArithService;
use PHPUnit_Framework_TestCase;

class ArithServiceTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Empty constructor param
     */
    public function testConstructorEmptyParam()
    {
        new ArithService();
    }

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Constructor param must be string
     */
    public function testConstructorIsNotStringTypeParam()
    {
        new ArithService(1234);
    }

    /**
     * @test
     */
    public function testConstructorIsStringTypeParam()
    {
        new ArithService('test string');
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
