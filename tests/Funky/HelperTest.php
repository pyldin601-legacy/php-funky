<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 05.03.16
 * Time: 22:35
 */

namespace Funky;


class HelperTest extends \PHPUnit_Framework_TestCase
{
    private $initialValue = 'foo';
    private $alternativeValue = 'bar';

    public function testOptionHelpers()
    {
        $this->assertInstanceOf(Option\Some::class, some($this->initialValue));
        $this->assertInstanceOf(Option\None::class, none());
        $this->assertInstanceOf(Option\Some::class, wrap($this->initialValue, $this->alternativeValue));
        $this->assertInstanceOf(Option\None::class, wrap($this->initialValue, $this->initialValue));
    }

    public function testInstFunction()
    {
        $closure = inst();
        $this->assertInstanceOf(Misc\SomeClass::class, $closure(Misc\SomeClass::class));
    }

    public function testInvokeFunction()
    {
        $instance = new Misc\SomeClass();
        $closure = invoke('foo');
        $this->assertEquals('bar', $closure($instance));

        $closure = invoke('staticFoo');
        $this->assertEquals('bar', $closure(Misc\SomeClass::class));
    }
}