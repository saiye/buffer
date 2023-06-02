<?php

declare(strict_types=1);

namespace Engine\Config;

class Config
{

    public static function getConfigPath(): string
    {
        return BASE_PATH.DIRECTORY_SEPARATOR."config";
    }

    public static function get(string $key, mixed $default = null)
    {
        $keyArr = explode(".", $key);
        $count  = count($keyArr);
        $file   = self::getConfigPath().DIRECTORY_SEPARATOR.$keyArr[0].".php";
        if (is_file($file)) {
            $res = require $file;
            if ($count == 2) {
                return ($res[$keyArr[1]] ?? $default);
            }
            if ($count == 3) {
                return ($res[$keyArr[1]][$keyArr[2]] ?? $default);
            }
            if ($count == 4) {
                return ($res[$keyArr[1]][$keyArr[2]][$keyArr[3]] ?? $default);
            }
            if ($count == 5) {
                return ($res[$keyArr[1]][$keyArr[2]][$keyArr[3]][$keyArr[4]] ?? $default);
            }
            if ($count == 6) {
                return ($res[$keyArr[1]][$keyArr[2]][$keyArr[3]][$keyArr[4]][$keyArr[5]] ?? $default);
            }
        }
        return $default;
    }
}