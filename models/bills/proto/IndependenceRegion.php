<?php

namespace app\models\bills\proto;

use app\models\Region;

/**
 * Дать региону независимость
 *
 * @author ilya
 */
class IndependenceRegion extends BillProto {
    
    public $id = 5;
    public $name = "Дать региону независимость";
    
    public function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }
        
        $data = json_decode($bill->data);
        
        $region = Region::findByPk($data->region_id);
        if ($region && $region->state_id === $bill->state_id) {
            $region->state_id = 0;
            $region->save();
        }
        
        return parent::accept($bill);
    }
    
    /**
     * 
     * @param \app\models\State $state
     */
    public function isVisible($state)
    {
        return (intval($state->getRegions()->count()) > 1);
    }
    
}
