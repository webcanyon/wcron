<?php

namespace WebCanyon\WCronBundle\Util;

/**
 * Class Collection
 *
 * @package WebCanyon\WCronBundle\Util
 */
class Collection
{
    /**
     * Method sortDescByKeyLength
     *
     * @param array $arr
     *
     * @return array
     */
    public static function sortDescByKeyLength(array $arr)
    {
        uksort($arr, 'self::sortByKeyLength');
        return $arr;
    }

    /**
     * Method sortByKeyLength
     *
     * @param $key1
     * @param $key2
     *
     * @return bool
     */
    private static function sortByKeyLength($key1, $key2)
    {
        return strlen($key1) < strlen($key2);
    }
}