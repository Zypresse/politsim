<?php

namespace app\models\bills\proto;

use app\models\CoreCountry;

/**
 * Установить историческое наследование
 *
 * @author ilya
 */
class SetCore extends BillProto {
    
    public $id = 15;
    public $name = "Выдвинуть претензии на наследование";
    
    public static function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }
        
        $data = json_decode($bill->data);
        
        if (!$bill->state->core_id) {            
            $coreCountry = CoreCountry::findByPk($data->core_id);
            if ($coreCountry) {
                $c2s = $bill->state->getCoreCountryState($coreCountry);
                if ($c2s && $c2s->percents > 0.5) {                
                    $bill->state->core_id = $data->core_id;
                    $bill->state->save();
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
        
        return parent::accept($bill);
    }
    
    /**
     * 
     * @param \app\models\State $state
     * @return boolean
     */
    public function isVisible($state)
    {
        if ($state->core) {
            return false;
        }
        foreach ($state->coreCountryStates as $c2s) {
            if ($c2s->percents > 0.5) {
                return true;
            }
        }
        return false;
    }
    
}
