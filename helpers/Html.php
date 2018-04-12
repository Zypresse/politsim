<?php

use app\helpers\Html as YiiHtml;

namespace app\helpers;

/**
 * Description of Html
 *
 * @author ilya
 */
class Html extends YiiHtml
{
    
    /**
     * Русское форматирование слов стоящих за числами
     * @param integer $n
     * @param string $s0 строка для 0/10/etc элементов, либо 'h' (человек), 'd' (день), 's' (акций)
     * @param string $s1 строка для 1/21/etc элемента
     * @param string $s2 строка для 2/3/etc элементов
     * @return string "N элементов"
     */
    public static function numberWord($n, $s0 = false, $s1 = false, $s2 = false)
    {
        $pref = ($n < 0) ? '-' : '';
        $n = abs($n);
        $number = $pref . number_format($n, 0, '', ' ');
        
        if ($s0 === false) {
            return $number;
        }
        
        if ($s0 === 'h') {
            $s0 = 'человек';
            $s1 = 'человек';
            $s2 = 'человека';
        } elseif ($s0 === 'd') {
            $s0 = 'дней';
            $s1 = 'день';
            $s2 = 'дня';
        } elseif ($s0 === 's') {
            $s0 = 'акций';
            $s1 = 'акция';
            $s2 = 'акции';
        }
        if ($s1 === false) {
            $s1 = $s0;
        }
        if ($s2 === false) {
            $s2 = $s0;
        }

        if ($n === 0) {
            return '0 ' . $s0;
        } elseif ($n === 1 || ($n % 10 === 1 && $n % 100 != 11 && $n != 11)) {
            return $number . ' ' . $s1;
        } elseif ($n > 100 && $n % 100 >= 12 && $n % 100 <= 14) {
            return $number . ' ' . $s0;
        } elseif (($n % 10 >= 2 && $n % 10 <= 4 && $n > 20) || ($n >= 2 && $n <= 4)) {
            return $number . ' ' . $s2;
        } else {
            return $number . ' ' . $s0;
        }
    }
    
}
