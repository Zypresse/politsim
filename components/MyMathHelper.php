<?php

namespace app\components;

/**
 * Description of MyMathHelper
 *
 * @author ilya
 */
class MyMathHelper {
    
    /**
     * Число е
     */
    const E = 2.71828182846;
    
    /**
     * Константы для myHalfExpo
     */
    const HE_C = -1.28734;
    const HE_K = 0.841467;

    /**
     * "Половина экспоненты" при <0.5 возвр. 0, при 0.5 возвр. 0.1, при 1 возвр. 1, при >1 возвр. 1
     * @param float $x
     * @return float
     */
    public static function myHalfExpo($x)
    {
        if ($x < 0.5) {
            return 0;
        }
        if ($x > 1) {
            return 1;
        }
        return static::HE_K*pow(static::E, $x) + static::HE_C;
    }
    
    /**
     * 
     * @param \yii\base\Model[] $objects
     * @param string $attributeJSON
     * @param string $attributeCount
     * @param integer $sumCount
     * @return string JSON
     */
    public static function sumPercents($objects, $attributeJSON, $attributeCount, $sumCount)
    {
        $values = [];
        foreach ($objects as $object) {
            if ($object->$attributeJSON) {
                $objectValues = json_decode($object->$attributeJSON, true);
                foreach ($objectValues as $id => $percents) {
                    $count = (int)round($object->$attributeCount*$percents/100);
                    if (isset($values[$id])) {
                        $values[$id] += $count;
                    } else {
                        $values[$id] = $count;
                    }
                }
            }
        }
        foreach ($values as $id => $count) {
            $percents = round(100 * $count / $sumCount,2);
            $values[$id] = $percents;
        }
        return json_encode($values);
    }
    
    /**
     * 
     * @param array $potentials variant => вероятность его выпадения
     */
    public static function randomP($potentials)
    {
        $r = mt_rand(0, 100)/100;
        foreach ($potentials as $i => $value) {
            if ($r > $value) {
                $r -= $value;
            } else {
                $num = $i;
                break;
            }
        }
        return $num;
    }
    
    /**
     * 
     * @param array $ar
     * @return integer
     */
    public static function implodeArrayToBitmask($ar)
    {
        if (!is_array($ar)) {
            return (int)$ar;
        }
        
        $sum = 0;
        foreach ($ar as $val) {
            $sum |= (int)$val;
        }
        return $sum;
    }
    
}
