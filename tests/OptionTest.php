<?php

/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 05.03.16
 * Time: 11:41
 */
class OptionTest extends PHPUnit_Framework_TestCase
{
    private $initialValue = 'foo';
    private $alternativeValue = 'bar';

    public function testSomeOption()
    {
        $option = new \Funky\Option\Some($this->initialValue);
        $alter = new \Funky\Option\Some($this->alternativeValue);

        $mapping = function () { return $this->alternativeValue; };
        $filterWont = function ($value) { return strlen($value) < 2; };
        $filterWill = function ($value) { return strlen($value) > 2; };

        $this->assertInstanceOf(\Funky\Option\Option::class, $option);
        $this->assertEquals($this->initialValue, $option->get());
        $this->assertFalse($option->isEmpty());
        $this->assertTrue($option->nonEmpty());
        $this->assertEquals($option->getOrElse($this->alternativeValue), $this->initialValue);
        $this->assertEquals($option->getOrThrow(\Exception::class), $this->initialValue);
        $this->assertEquals($option->orElse($alter), $option);

        $this->assertInstanceOf(\Funky\Option\Option::class, $option->map($mapping));
        $this->assertEquals($option->map($mapping)->get(), $this->alternativeValue);

        $this->assertInstanceOf(\Funky\Option\None::class, $option->filter($filterWont));
        $this->assertInstanceOf(\Funky\Option\Some::class, $option->filter($filterWill));
    }

    public function testNoneOption()
    {
        $option = \Funky\Option\None::instance();
        $alter = new \Funky\Option\Some($this->alternativeValue);

        $mapping = function () { return $this->alternativeValue; };
        $filterReject = function () { return false; };
        $filterPass = function () { return true; };

        $this->assertInstanceOf(\Funky\Option\Option::class, $option);
        $this->assertException(function () use ($option) { $option->get(); }, \Funky\Option\OptionException::class);
        $this->assertTrue($option->isEmpty());
        $this->assertFalse($option->nonEmpty());
        $this->assertEquals($option->getOrElse($this->alternativeValue), $this->alternativeValue);
        $this->assertException(function () use ($option) { $option->getOrThrow(\Exception::class); }, \Exception::class);
        $this->assertEquals($option->orElse($alter), $alter);

        $this->assertInstanceOf(\Funky\Option\None::class, $option->map($mapping));

        $this->assertInstanceOf(\Funky\Option\None::class, $option->filter($filterPass));
        $this->assertInstanceOf(\Funky\Option\None::class, $option->filter($filterReject));
    }

    public function testWrapping()
    {
        $this->assertInstanceOf(\Funky\Option\None::class, \Funky\Option\Option::wrap(false, false));
        $this->assertInstanceOf(\Funky\Option\Some::class, \Funky\Option\Option::wrap($this->initialValue, false));
        $this->assertInstanceOf(\Funky\Option\Some::class, \Funky\Option\Option::wrap($this->initialValue));
    }

    public function testHelpers()
    {
        $this->assertInstanceOf(\Funky\Option\Some::class, some($this->initialValue));
        $this->assertInstanceOf(\Funky\Option\None::class, none());
        $this->assertInstanceOf(\Funky\Option\Some::class, wrap($this->initialValue));
        $this->assertInstanceOf(\Funky\Option\None::class, wrap($this->initialValue, $this->initialValue));
    }

    private function assertException($callable, $expectedException = Exception::class)
    {
        try {
            $callable();
        } catch (\Exception $exception) {
            $this->assertInstanceOf($expectedException, $exception);
        }
    }
}