<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.02.2016
 * Time: 14:05
 */

namespace Funky\Option;


/**
 * Class None
 * @package Funky\Option
 */
class None extends Option {

    private static $instance;

    private function __construct() { }

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function get()
    {
        throw new OptionException("Object is empty.");
    }

    function isEmpty()
    {
        return true;
    }

    function getOrElse($else)
    {
        return $else;
    }

    function getOrThrow($exceptionClass, ...$arguments)
    {
        throw new $exceptionClass(...$arguments);
    }

    function getOrCall($callable)
    {
        return $callable();
    }

    function orElse(Option $other)
    {
        return $other;
    }

    function map($callable)
    {
        return $this;
    }

    function flatMap($callable)
    {
        return $this;
    }

    function filter($callable)
    {
        return $this;
    }

    function __toString()
    {
        return "None";
    }
}