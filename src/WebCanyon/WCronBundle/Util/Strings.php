<?php

namespace WebCanyon\WCronBundle\Util;

/**
 * Class Strings
 *
 * @package WebCanyon\WCronBundle\Util
 */
class Strings
{
    /**
     * Method toCamel
     *
     * @param string $string
     * @param bool $capitalizeFirstCharacter
     * @param string $separator
     *
     * @return string
     */
    public static function toCamel(string $string, bool $capitalizeFirstCharacter = false, string $separator = '_'): string
    {
        $str = str_replace(' ', '', ucwords(str_replace($separator, ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }

    /**
     * Method camelTo
     *
     * @param string $string
     * @param string $separator
     *
     * @return mixed|string
     */
    public static function camelTo(string $string, string $separator = '_')
    {
        $string = preg_replace('/([a-z])([A-Z])/', "\\1$separator\\2", $string);
        $string = strtolower($string);
        return $string;
    }
}