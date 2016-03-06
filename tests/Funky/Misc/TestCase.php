<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 06.03.16
 * Time: 11:31
 */

namespace Funky\Misc;


class TestCase extends \PHPUnit_Framework_TestCase {
    protected function assertException($callable, $expectedException = \Exception::class)
    {
        try {
            $callable();
        } catch (\Exception $exception) {
            $this->assertInstanceOf($expectedException, $exception);
        }
    }
}