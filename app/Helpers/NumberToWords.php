<?php

namespace App\Helpers;

class NumberToWords
{
    private static $units = [
        '', 'bir', 'iki', 'üç', 'dört', 'beş', 'altı', 'yedi', 'sekiz', 'dokuz'
    ];

    private static $tens = [
        '', 'on', 'yirmi', 'otuz', 'kırk', 'elli', 'altmış', 'yetmiş', 'seksen', 'doksan'
    ];

    private static $thousands = [
        '', 'bin', 'milyon', 'milyar', 'trilyon'
    ];

    public static function convert($number)
    {
        if ($number == 0) {
            return 'sıfır';
        }

        $result = '';
        $groups = array_reverse(str_split(str_pad($number, 12, '0', STR_PAD_LEFT), 3));

        foreach ($groups as $i => $group) {
            if ($group != '000') {
                $result = self::convertThreeDigit($group) . ' ' . self::$thousands[$i] . ' ' . $result;
            }
        }

        return trim($result);
    }

    private static function convertThreeDigit($number)
    {
        $result = '';
        $hundreds = floor($number / 100);
        $tens = floor(($number % 100) / 10);
        $units = $number % 10;

        if ($hundreds > 0) {
            $result .= ($hundreds == 1 ? '' : self::$units[$hundreds]) . 'yüz';
        }

        if ($tens > 0) {
            $result .= self::$tens[$tens];
        }

        if ($units > 0) {
            $result .= self::$units[$units];
        }

        return $result;
    }
} 