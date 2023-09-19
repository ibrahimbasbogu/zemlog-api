<?php

namespace App\Service;

class CaseConvertService
{
    public static function camelToSnake(string $input): string
    {
        if (preg_match('/[A-Z]/', $input) == 0) {
            return $input;
        }

        $pattern = '/([a-z])([A-Z])/';

        return strtolower(preg_replace_callback($pattern, function ($a) {
            return $a[1] . '_' . strtolower ( $a[2] );
        }, $input));
    }

    public static function snakeToCamel(string $input): string
    {
        return str_replace('_', '', lcfirst(ucwords($input, '_')));
    }
}