<?php

if (!function_exists('func')) {

    /**
     * @param $pattern
     * @return Closure
     */
    function func($pattern)
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new \Funky\Lambda\Lambda();
        }

        return $instance->make($pattern);
    }

}

if (!function_exists('inst')) {

    /**
     * Returns callback that makes new instance from given argument.
     *
     * @return Closure
     */
    function inst()
    {
        return function ($class) {
            return new $class();
        };
    }

}

if (!function_exists('invoke')) {

    /**
     * Returns callback that invokes $method on the $class.
     *
     * @param $method
     * @param ...$arguments
     * @return Closure
     */
    function invoke($method, ...$arguments)
    {
        return function ($class) use ($method, $arguments) {
            return call_user_func_array([$class, $method], $arguments);
        };
    }

}

if (!function_exists('some')) {

    /**
     * @param $value
     * @return \Funky\Option\Some
     */
    function some($value)
    {
        return new \Funky\Option\Some($value);
    }

}

if (!function_exists('none')) {

    /**
     * @return \Funky\Option\None
     */
    function none()
    {
        return \Funky\Option\None::instance();
    }

}

if (!function_exists('wrap')) {

    /**
     * @param $value
     * @param null $reject
     * @return \Funky\Option\Option
     */
    function wrap($value, $reject = null)
    {
        return \Funky\Option\Option::wrap($value, $reject);
    }

}