<?php

namespace app\models\bills\proto;

/**
 * Смена цвета государства на карте
 *
 * @author ilya
 */
class ChangeColor extends BillProto {
    
    public $id = 8;
    public $name = "Сменить цвет государства на карте";
    
    public static function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }
        
        $data = json_decode($bill->data);
        
        $bill->state->color = $data->new_color;
        $bill->state->save();
        
        return parent::accept($bill);
    }
    
    public function isVisible($state)
    {
        return true;
    }
    
}
