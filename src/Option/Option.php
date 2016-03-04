<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.02.2016
 * Time: 14:02
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

        return ! $this->isEmpty();

    }

    /**
     * @param $else
     * @return mixed
     */
    abstract function getOrElse($else);

    /**
     * @param callable $callable
     * @param $arguments
     * @return mixed
     */
    abstract function getOrThrow($callable, ...$arguments);

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
    public static function pack($value, $reject = null) {
        if ($value === $reject) {
            return None::instance();
        }
        return new Some($value);
    }

}