<?php

namespace App\Helpers;

use Hashids\Hashids;

class HashidsHelper
{
    const PROJCET = 'Resume Builder';
    const LENGTH = 10;

    public function encode($string, $length = self::LENGTH)
    {
        $hashids = new Hashids(self::PROJCET, $length);
        return $hashids->encode($string);
    }

    public function decode($encrypted, $length = self::LENGTH)
    {
        $hashids = new Hashids(self::PROJCET, $length);
        return $hashids->decode($encrypted);
    }

    public function encodeHex($string, $length = self::LENGTH)
    {
        $hashids = new Hashids(self::PROJCET, $length);
        return $hashids->encodeHex($string);
    }

    public function decodeHex($encrypted, $length = self::LENGTH)
    {
        $hashids = new Hashids(self::PROJCET, $length);
        return $hashids->decodeHex($encrypted);
    }
    
}
?>