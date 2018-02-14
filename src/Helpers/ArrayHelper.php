<?php

namespace Application\Helpers;

class ArrayHelper
{
    public static function toArray($object, $properties = [])
    {
        if (!empty($properties)) {
            $result = [];
            foreach ($properties as $key => $value) {
                if (is_int($key)) {
                    $result[$value] = $object[$value];
                } else {
                    $result[$key] = self::toArray($object,$properties[$key]);
                }
            }
            return $result;
        }
    }
}