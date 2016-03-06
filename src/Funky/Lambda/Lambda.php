<?php


namespace Funky\Lambda;


class Lambda {

    const VARIABLE_PATTERN = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';
    const ARGUMENT_TEMPLATE = '~(\$)(\d+)~';
    const ARGUMENT1_TEMPLATE = '~(\$)~';

    private $compiledCache = [];

    /**
     * @param string $pattern Pattern to be converted into Closure
     * @return \Closure
     */
    public function make($pattern)
    {
        $this->checkPattern($pattern);
        $hash = $this->getPatternHash($pattern);

        if (empty($this->compiledCache[$hash])) {

            $pattern = preg_replace(self::ARGUMENT_TEMPLATE, 'func_get_arg($2 - 1)', $pattern);

            $index = 0;

            $pattern = preg_replace_callback(self::ARGUMENT1_TEMPLATE, function () use (&$index) {
                $result = "func_get_arg($index)"; $index ++; return $result;
            }, $pattern);

            $build = create_function("", "return $pattern;");

            $this->compiledCache[$hash] = $build;
        }
        return $this->compiledCache[$hash];
    }

    /**
     * Checks whether $pattern is valid.
     *
     * @param $pattern
     * @throws LambdaException
     */
    private function checkPattern($pattern)
    {
        if (! is_string($pattern)) {
            throw new LambdaException("Pattern expected to be a string");
        }
    }

    /**
     * @param string $pattern
     * @return string
     */
    private function getPatternHash($pattern)
    {
        return md5(serialize($pattern));
    }

    /**
     * @param $pattern
     * @return int|mixed
     */
    private function detectNumberOfArguments($pattern)
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