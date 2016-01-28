<?php

namespace app\models\bills\proto;

use app\components\MyHtmlHelper;

/**
 * Сменить флаг государства
 *
 * @author ilya
 */
class ChangeFlag extends BillProto {
    
    public $id = 6;
    public $name = "Сменить флаг государства";
    
    public function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }
        
        $data = json_decode($bill->data);
        
        if (MyHtmlHelper::isImageLink($data->new_flag)) {
            $bill->state->flag = $data->new_flag;
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