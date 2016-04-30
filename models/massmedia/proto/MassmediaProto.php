<?php

namespace app\models\massmedia\proto;

/**
 * Description of MassmediaProto
 *
 * @author ilya
 */
class MassmediaProto
{
    public static function instantiateById($id, $row = [])
    {
    	$classes = [
    		1 => 'Newspaper'
    	];
        $className = "app\\models\\massmedia\\proto\\types\\{$classes[$id]}";
        return $className::instantiate($row);
    }
}
