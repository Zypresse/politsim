<?php

namespace app\models\bills\proto;

use app\components\MyHtmlHelper;

/**
 * Сменить гимн государства
 *
 * @author ilya
 */
class ChangeAnthem extends BillProto {
    
    public $id = 16;
    public $name = "Сменить гимн государства";
    
    public static function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }
        
        $data = json_decode($bill->data);
        
        if (MyHtmlHelper::isSoundCloudLink($data->new_anthem)) {
        
            $bill->state->anthem = $data->new_anthem;
            $bill->state->save();
        
            return parent::accept($bill);
        } else {
            return $bill->delete();
        }
    }
    
    public function isVisible($state)
    {
        return true;
    }
    
}
