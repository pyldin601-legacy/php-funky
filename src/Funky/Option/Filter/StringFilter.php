<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 09.03.2016
 * Time: 9:32
 */

namespace Funky\Option\Filter;


class StringFilter
{
    /**
     * "Length of the string is equal to $that"
     *
     * @param $that
     * @return \Closure
     */
    public static function lengthEq($that)
    {
        return function ($value) use ($that) { return strlen($value) == $that; };
    }

    /**
     * "Length of the string is greater than $that"
     *
     * @param $that
     * @return \Closure
     */
    public static function lengthGt($that)
    {
        return function ($value) use ($that) { return strlen($value) > $that; };
    }

    /**
     * "Length of the string is less than $that"
     *
     * @param $length
     * @return \Closure
     */
    public static function lengthLt($length)
    {
        return function ($value) use ($length) { return strlen($value) < $length; };
    }

    /**
     * "Length of the string is equal or greater than $that"
     *
     * @param $that
     * @return \Closure
     */
    public static function lengthGe($that)
    {
        return function ($value) use ($that) { return strlen($value) >= $that; };
    }

    /**
     * "Length of the string is less or equal to $that"
     *
     * @param $length
     * @return \Closure
     */
    public static function lengthLe($length)
    {
        return function ($value) use ($length) { return strlen($value) <= $length; };
    }

    /**
     * "Length of the string is in range"
     *
     * @param $min
     * @param $max
     * @return \Closure
     */
    public static function range($min, $max)
    {
        return function ($value) use ($min, $max) {
            return strlen($value) >= $min && strlen($value) <= $max;
        };
    }

    /**
     * "String matches regexp $pattern"
     *
     * @param $pattern
     * @return \Closure
     */
    public static function match($pattern)
    {
        return function ($value) use ($pattern) {
            return boolval(preg_match("~$pattern~", $value));
        };
    }

    /**
     * "String exists in $array"
     *
     * @param $array
     * @return \Closure
     */
    public static function foundIn($array)
    {
        return function ($value) use ($array) {
            return in_array($value, $array);
        };
    }
}