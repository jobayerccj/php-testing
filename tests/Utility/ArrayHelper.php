<?php

namespace App\Tests\Utility;

class ArrayHelper
{
    public static function flattenArray(array $data): array
    {
        return iterator_to_array(
            new \RecursiveIteratorIterator(
                new \RecursiveArrayIterator($data)
            )
        );
    }
}
