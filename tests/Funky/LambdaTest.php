<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 05.03.16
 * Time: 22:39
 */

namespace Funky;


class LambdaTest extends Misc\TestCase
{
    public $attr = 'foo';

    public function testMath()
    {
        $closure = func("$1 + $2");
        $this->assertEquals(10, $closure(8, 2));

        $closure = func("sin($1) + cos($1)");
        $this->assertEquals(1, $closure(0));
    }

    public function testMethodCall()
    {
        $closure = func("$1->someMethod()");
        $this->assertEquals('CALLED', $closure($this));
    }

    public function classPropertyGet()
    {
        $closure = func("$1->attr");
        $this->assertEquals('foo', $closure($this));
    }

    public function testFunctionsCache()
    {
        $closure1 = func("$1 / $2");
        $closure2 = func("$1 / $2");
        $closure3 = func("$1 - $2");

        $this->assertEquals($closure1, $closure2);
        $this->assertNotEquals($closure1, $closure3);
    }

    public function testArrayAttributesGet()
    {
        $array = [
            'one' => 'foo',
            'bar'
        ];

        $closure1 = func("$1['one']");
        $closure2 = func("$1[0]");

        $this->assertEquals('foo', $closure1($array));
        $this->assertEquals('bar', $closure2($array));
    }

    public function testUnindexedVariables()
    {
        $closure = func("$ + $");

        $this->assertEquals(5, $closure(2, 3));
    }

    public function testWrongTypeOfData()
    {
        $this->assertException(function () { func(null); }, Lambda\LambdaException::class);
        $this->assertException(function () { func(array()); }, Lambda\LambdaException::class);
        $this->assertException(function () { func(false); }, Lambda\LambdaException::class);
    }

    public function someMethod()
    {
        return 'CALLED';
    }
}