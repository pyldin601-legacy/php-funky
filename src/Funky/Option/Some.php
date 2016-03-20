<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.02.2016
 * Time: 14:08
 */

namespace Funky\Option;


/**
 * Class Some
 * @package Funky\Option
 */
class Some extends Option {

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    function get()
    {
        return $this->value;
    }

    function isEmpty()
    {
        return false;
    }

    function getOrElse($else)
    {
        return $this->get();
    }

    function getOrThrow($exceptionClass, ...$arguments)
    {
        return $this->get();
    }

    function getOrCall($callable)
    {
        return $this->get();
    }

    function orElse(Option $other)
    {
        return $this;
    }

    function map($callable)
    {
        if (is_callable($callable)) {
            return new self($callable($this->get()));
        }
        elseif (is_array($callable)) {
            return isset($callable[$this->get()]) ? new self($callable[$this->get()]) : None::instance();
        }
        throw new \InvalidArgumentException("Expected only callable or array type of argument.");
    }

    function flatMap($callable)
    {
        $result = $callable($this->get());
        if (! $result instanceof Option) {
            throw new OptionException("Callable used in flatMap must return Option.");
        }
        return $result;
    }

    function filter($callable)
    {
        if (is_callable($callable)) {
            return ($callable($this->get())) ? $this : None::instance();
        }
        elseif (is_array($callable)) {
            return in_array($this->get(), $callable) ? $this : None::instance();
        }
        throw new \InvalidArgumentException("Expected only callable or array type of argument.");
    }

    function __toString()
    {
        return "Some(".$this->get().")";
    }

}