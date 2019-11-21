<?php

namespace OnlyTracker\Infrastructure\Util\Parser\Title;

class StudioParser
{
    /**
     * @param string $content
     *
     * @return string[]
     */
    public function parse(string $content)
    {
        preg_match('~^\[([a-zA-Z0-9\/\\\.\s_\(\)\-]+)\]~', $content, $matches);

        if (!isset($matches[1])) {
            return [];
        }

        $splitted = preg_split('~[\\\/]~', $matches[1], null, PREG_SPLIT_NO_EMPTY);
        $withAliases = array_values(array_filter(array_map(function ($value) {
            return trim($value);
        }, $splitted), function ($value) {
            return !empty($value);
        }));

        $notFlatten = array_map(function ($value) {
            preg_match('~([a-zA-Z0-9_\-\.]*)\s*(?:\(([a-zA-Z0-9_\-\.]*)\))*~', $value, $res);
            array_shift($res);
            return $res;
        }, $withAliases);

        $result = [];
        array_walk_recursive($notFlatten, function ($value) use (&$result) {
            $result[] = $value;
        });

        return $result;
    }
}
