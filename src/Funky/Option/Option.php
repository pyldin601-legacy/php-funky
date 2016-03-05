<?php

/**
 * Option is a type that represents encapsulation of an optional value.
 *
 * For more information @see README.md
 *
 * @link https://github.com/pldin601/php-funky
 * @author Roman Gemini (pldin601), roman@homefs.biz
 * @copyright 2016 Roman Gemini
 */

namespace Funky\Option;


/**
 * Class Option
 * @package Funky\Option
 */
abstract class Option {

    /**
     * @return mixed
     */
    abstract function get();

    /**
     * @return boolean
     */
    abstract function isEmpty();

    /**
     * @return bool
     */
    function nonEmpty() {

        return !$this->isEmpty();

    }

    /**
     * @param $else
     * @return mixed
     */
    abstract function getOrElse($else);

    /**
     * @param callable $exceptionClass
     * @param $arguments
     * @return mixed
     */
    abstract function getOrThrow($exceptionClass, ...$arguments);

    /**
     * @param Option $other
     * @return Option
     */
    abstract function orElse(Option $other);

    /**
     * @param callable|array $callable
     * @return Option
     */
    abstract function map($callable);

    /**
     * @param callable|array $callable
     * @return Option
     */
    abstract function filter($callable);

    /**
     * @param $value
     * @param null $reject
     * @return Option
     */
    public static function wrap($value, $reject = null) {
        if ($value === $reject) {
            return None::instance();
        }
        return new Some($value);
    }

}