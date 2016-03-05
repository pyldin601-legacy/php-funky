<?php


namespace Funky\Lambda;


class Lambda {

    const VARIABLE_PATTERN = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';
    const ARGUMENT_TEMPLATE = '~(\$)(\d+)~';

    private $compiledCache = [];

    /**
     * @param string $pattern Pattern to be converted into Closure
     * @return \Closure
     */
    public function make($pattern)
    {
        $hash = $this->getPatternHash($pattern);
        if (empty($this->compiledCache[$hash])) {
            $arguments = $this->detectNumberOfArguments($pattern);
            $build = create_function(
                implode(',', array_map(function ($i) { return '$arg' . $i; }, range(1, $arguments, 1))),
                'return ' . preg_replace(self::ARGUMENT_TEMPLATE, '$1arg$2', $pattern) . ";"
            );

            $this->compiledCache[$hash] = $build;
        }
        return $this->compiledCache[$hash];
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