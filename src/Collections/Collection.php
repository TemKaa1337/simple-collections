<?php declare(strict_types = 1);

namespace SimpleCollections\Collections;

use SimpleCollections\Collections\{ArrayCollection, ObjectCollection};
use SimpleCollections\Exceptions\InvalidInputFormatException;

class Collection
{
    public static function init(array $array, bool $staticProps = true): ArrayCollection|ObjectCollection
    {
        if (empty($array))
            return new ArrayCollection($array);

        if (isset($array[0])) {
            if (is_array($array[0])) {
                return new ArrayCollection($array);
            } else if (is_object($array[0])) {
                return new ObjectCollection($array, $staticProps);
            }
        }

        throw new InvalidInputFormatException('The provided collection input is incorrect.');
    }
}