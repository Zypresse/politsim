<?php

namespace app\models\bills\proto;

use app\models\Region;

/**
 * Переименовать город
 *
 * @author ilya
 */
class RenameCity extends BillProto {
    
    public static $id = 4;
    public static $name = "Переименовать город";
    
    public static function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }
        
        $data = json_decode($bill->data);
        
        $region = Region::findByPk($data->region_id);
        if ($region && $region->state_id === $bill->state_id) {
            $region->city = $data->new_city_name;
            $region->save();
        }
        
        return parent::accept($bill);
    }
    
    public static function isVisible($state)
    {
        return true;
    }
    
}
