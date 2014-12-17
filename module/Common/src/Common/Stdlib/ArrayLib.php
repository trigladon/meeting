<?php

namespace Common\Stdlib;

abstract class ArrayLib
{

    public static function merge(array $aArray, array $bArray, $preserveNumericKeys = false )
    {
        foreach ($bArray as $key => $value) {
            if (isset($aArray[$key])) {
                if (is_int($key) && !$preserveNumericKeys) {
                    $aArray[] = $value;
                } elseif (is_array($value) && is_array($aArray[$key])) {
                    $aArray[$key] = static::merge($aArray[$key], $value, $preserveNumericKeys);
                } else {
                    $aArray[$key] = $value;
                }
            } else {
                $aArray[$key] = $value;
            }
        }

        return $aArray;
    }

}