<?php

namespace app\models\bills\proto;

/**
 * Сменить флаг государства
 *
 * @author ilya
 */
class ChangeFlag extends BillProto {
    
    public static $id = 6;
    public static $name = "Сменить флаг государства";
    
    public static function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }
        
        $data = json_decode($bill->data);
        
        $bill->state->flag = $data->new_flag;
        $bill->state->save();
        
        return parent::accept($bill);
    }
    
    public static function isVisible($state)
    {
        return true;
    }
    
}
