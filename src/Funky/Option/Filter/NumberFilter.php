<?php

namespace Funky\Option\Filter;


class NumberFilter
{
    /**
     * "Greater than $that" filter
     * @param $that
     * @return \Closure
     */
    public static function gt($that)
    {
        return function ($value) use ($that) { return $value > $that; };
    }

    /**
     * "Less than $that" filter
     * @param $that
     * @return \Closure
     */
    public static function lt($that)
    {
        return function ($value) use ($that) { return $value < $that; };
    }

    /**
     * "Greater or equal $that" filter
     * @param $that
     * @return \Closure
     */
    public static function ge($that) {
        return function ($value) use ($that) { return $value >= $that; };
    }

    /**
     * "Less or equal $that" filter
     * @param $that
     * @return \Closure
     */
    public static function le($that) {
        return function ($value) use ($that) { return $value <= $that; };
    }

    /**
     * "Is in range" filter
     * @param $from
     * @param $to
     * @return \Closure
     */
    public static function range($from, $to) {
        return function ($value) use ($from, $to) { return $value >= $from && $value <= $to; };
    }
}