<?php

if (!function_exists('mb_strcasecmp')) {
    function mb_strcasecmp($str1, $str2, $encoding = null)
    {
        if (null === $encoding) {
            $encoding = mb_internal_encoding();
        }
        return strcmp(mb_strtoupper($str1, $encoding), mb_strtoupper($str2, $encoding));
    }
}

if (!function_exists('mb_ucfirst')) {
    function mb_ucfirst($string, $encoding = 'UTF-8')
    {
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }
}

if (!function_exists('mb_ucwords')) {
    function mb_ucwords($string)
    {
        return preg_replace_callback(
            '~(\w+)~u',
            function($m) {
                return mb_ucfirst($m[0]);
            },
            $string
        );
    }
}

if (!function_exists('snake_to_camel')) {
    function snake_to_camel($word)
    {
        return lcfirst(str_replace('_', '', ucwords($word, '_')));
    }
}
