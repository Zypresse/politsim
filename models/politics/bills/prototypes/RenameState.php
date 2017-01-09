<?php

namespace app\models\politics\bills\prototypes;

use app\models\politics\bills\BillProtoInterface,
    app\models\politics\bills\Bill,
    app\models\politics\State;

/**
 * Переименование государства
 */
class RenameState implements BillProtoInterface
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill) : bool
    {
        $data = json_decode($bill->data);
        $bill->state->name = $data->name;
        $bill->state->nameShort = $data->nameShort;
        $bill->state->save();
        return true;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill) : bool
    {
        $data = json_decode($bill->data);
        return ($data->name && $data->nameShort);
    }

}
