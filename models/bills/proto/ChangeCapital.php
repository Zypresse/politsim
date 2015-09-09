<?php

namespace app\models\bills\proto;

use app\models\Region;

/**
 * Description of ChangeCapital
 *
 * @author ilya
 */
class ChangeCapital extends BillProto {
    
    public $id = 2;
    
    public function accept($bill)
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
    
    public function isVisible($state)
    {
        return true;
    }
    
}
