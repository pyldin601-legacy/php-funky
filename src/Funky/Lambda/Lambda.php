<?php


namespace Funky\Lambda;


class Lambda {

    const ARGUMENT_PATTERN = '~(\$)(\d*?)(?![a-zA-Z0-9_\x7f-\xff])~';

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

            $totalArguments = 0;

            $pattern = preg_replace_callback(self::ARGUMENT_PATTERN, function ($match) use (&$totalArguments)
            {
                if (!empty($match[2])) {
                    $id = $match[2];
                    $totalArguments = max($totalArguments, $id);
                } else {
                    $id = ++ $totalArguments;
                }

                return sprintf('$arg%d', $id);

            }, $pattern);

            $function = create_function(
                $this->generateArguments($totalArguments),
                "return $pattern;"
            );

            $this->compiledCache[$hash] = $function;

        }

        return $this->compiledCache[$hash];
    }

    /**
     * @param $count
     * @return string
     */
    private function generateArguments($count)
    {
        $arguments = [];
        for ($i = 0; $i < $count; $i ++) {
            $arguments[] = sprintf('$arg%d', ($i + 1));
        }
        return implode(',', $arguments);
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