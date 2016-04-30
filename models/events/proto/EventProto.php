<?php

namespace app\models\events\proto;

class EventProto {
	

    public static function instantiateById($id, $row)
    {
    	$classes = [
    		1 => 'Elections'
    	];
        $className = "app\\models\\events\\proto\\types\\{$classes[$id]}";
        return $className::instantiate($row);
    }

}