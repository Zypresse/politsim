<?php

namespace app\models\poprequests\proto;

/**
 * Description of PopRequestProto
 *
 * @author ilya
 */
class PopRequestProto
{
    public static function instantiateById($id, $row = [])
    {
    	$classes = [
    		1 => 'MakeBill'
    	];
        $className = "app\\models\\poprequests\\proto\\types\\{$classes[$id]}";
        return $className::instantiate($row);
    }
}
