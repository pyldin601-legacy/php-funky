<?php

namespace Funky;


class OptionTest extends \PHPUnit_Framework_TestCase
{
    private $initialValue = 'foo';
    private $alternativeValue = 'bar';

    public function testSomeOption()
    {
        $option = new Option\Some($this->initialValue);
        $alter = new Option\Some($this->alternativeValue);

        $mapping = function () { return $this->alternativeValue; };
        $filterWont = function ($value) { return strlen($value) < 2; };
        $filterWill = function ($value) { return strlen($value) > 2; };

        $this->assertInstanceOf(Option\Option::class, $option);
        $this->assertEquals($this->initialValue, $option->get());
        $this->assertFalse($option->isEmpty());
        $this->assertTrue($option->nonEmpty());
        $this->assertEquals($option->getOrElse($this->alternativeValue), $this->initialValue);
        $this->assertEquals($option->getOrThrow(\Exception::class), $this->initialValue);
        $this->assertEquals($option->orElse($alter), $option);

        $this->assertInstanceOf(Option\Option::class, $option->map($mapping));
        $this->assertEquals($option->map($mapping)->get(), $this->alternativeValue);

        $this->assertInstanceOf(Option\None::class, $option->filter($filterWont));
        $this->assertInstanceOf(Option\Some::class, $option->filter($filterWill));
    }

    public function testNoneOption()
    {
        $option = Option\None::instance();
        $alter = new Option\Some($this->alternativeValue);

        $mapping = function () { return $this->alternativeValue; };
        $filterReject = function () { return false; };
        $filterPass = function () { return true; };

        $this->assertInstanceOf(Option\Option::class, $option);
        $this->assertException(function () use ($option) { $option->get(); }, Option\OptionException::class);
        $this->assertTrue($option->isEmpty());
        $this->assertFalse($option->nonEmpty());
        $this->assertEquals($option->getOrElse($this->alternativeValue), $this->alternativeValue);
        $this->assertException(function () use ($option) { $option->getOrThrow(\Exception::class); }, \Exception::class);
        $this->assertEquals($option->orElse($alter), $alter);

        $this->assertInstanceOf(Option\None::class, $option->map($mapping));

        $this->assertInstanceOf(Option\None::class, $option->filter($filterPass));
        $this->assertInstanceOf(Option\None::class, $option->filter($filterReject));
    }

    public function testWrapping()
    {
        $this->assertInstanceOf(Option\None::class, Option\Option::wrap(false, false));
        $this->assertInstanceOf(Option\Some::class, Option\Option::wrap($this->initialValue, false));
        $this->assertInstanceOf(Option\Some::class, Option\Option::wrap($this->initialValue));
    }


    private function assertException($callable, $expectedException = \Exception::class)
    {
        try {
            $callable();
        } catch (\Exception $exception) {
            $this->assertInstanceOf($expectedException, $exception);
        }
    }
}