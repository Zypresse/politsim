<?php

namespace app\models\bills\proto;

/**
 * Переименовать страну
 *
 * @author ilya
 */
class RenameState extends BillProto {

    public $id = 1;
    public $name = "Переименовать страну";

    public function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }

        $data = json_decode($bill->data);

        $bill->state->name = $data->new_name;
        $bill->state->short_name = $data->new_short_name;
        if (!$bill->state->save()) {
            var_dump($bill->state->getErrors());
            return false;
        }

        return parent::accept($bill);
    }

    public function isVisible($state)
    {
        return true;
    }

}