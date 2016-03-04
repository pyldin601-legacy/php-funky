<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 13.02.16
 * Time: 13:06
 */

namespace Funky\Lambda;


class Compiler {

    const VARIABLE_PATTERN = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';
    const ARGUMENT_TEMPLATE = '~(\$)(\d+)~';

    private static $compiledCache = [];

    /**
     * @param string $pattern Pattern to be converted into Closure
     * @param boolean $returns Is Closure must return result
     * @return \Closure
     */
    public static function compile($pattern, $returns)
    {
        $hash = self::getPatternHash([$pattern, $returns]);
        if (empty(self::$compiledCache[$hash])) {
            $arguments = self::detectNumberOfArguments($pattern);
            $build = create_function(
                implode(',', array_map(function ($i) { return '$arg' . $i; }, range(1, $arguments, 1))),
                ($returns ? 'return ' : '') . preg_replace(self::ARGUMENT_TEMPLATE, '$1arg$2', $pattern) . ";"
            );

            self::$compiledCache[$hash] = $build;
        }
        return self::$compiledCache[$hash];
    }

    /**
     * @param $pattern
     * @param bool|true $returns
     * @return callable
     * @throws CompilerException
     */
    public static function parsePattern($pattern, $returns = true)
    {
        if (is_callable($pattern)) {
            return $pattern;
        }
        if (is_string($pattern)) {
            return function_exists($pattern) ? $pattern : self::compile($pattern, $returns);
        }
        if (is_array($pattern) && count($pattern) == 2) {
            if (class_exists($pattern[0]) || is_object($pattern[0])) {
                return $pattern;
            }
        }
        throw new CompilerException('Unsupported pattern type');
    }

    /**
     * @param string $pattern
     * @return string
     */
    private static function getPatternHash($pattern)
    {
        return md5(serialize($pattern));
    }

    /**
     * @param $pattern
     * @return int|mixed
     */
    static function detectNumberOfArguments($pattern)
    {
        preg_match_all('~\$(\d+)~', $pattern, $result, PREG_PATTERN_ORDER);
        $result = $result[1];
        rsort($result, SORT_NUMERIC);
        if (count($result) == 0) {
            return 0;
        }
        return (int) array_shift($result);
    }

}