<?php

namespace app\models\bills\proto;

use app\models\Region;

/**
 * Переименовать регион
 *
 * @author ilya
 */
class RenameRegion extends BillProto {
    
    public $id = 3;
    public $name = "Переименовать регион";
    
    public function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }
        
        $data = json_decode($bill->data);
        
        $region = Region::findByPk($data->region_id);
        if ($region && $region->state_id === $bill->state_id) {
            $region->name = $data->new_name;
            $region->save();
        }
        
        return parent::accept($bill);
    }
    
    public function isVisible($state)
    {
        return true;
    }
    
}
