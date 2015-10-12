<?php

namespace app\models\bills\proto;

use app\models\Region;

/**
 * Перенести столицу государства
 *
 * @author ilya
 */
class ChangeCapital extends BillProto {
    
    public static $id = 2;
    public static $name = "Перенести столицу государства";

    public static function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }
        
        $data = json_decode($bill->data);
        
        $region = Region::findByPk($data->new_capital);
        if ($region && $region->state_id === $bill->state_id) {
            $bill->state->capital = $data->new_capital;
            $bill->state->save();
        }
        
        return parent::accept($bill);
    }
   
    /**
     * 
     * @param \app\models\State $state
     */
    public static function isVisible($state)
    {
        return (intval($state->getRegions()->count()) > 1);
    }
    
}
