<?php

namespace app\models\bills\proto;

/**
 * Переименовать страну
 *
 * @author ilya
 */
class RenameState extends BillProto {

    public static $id = 1;
    public static $name = "Переименовать страну";

    public static function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }

        $data = json_decode($bill->data);

        $bill->state->name = $data->new_name;
        $bill->state->short_name = $data->new_short_name;
        $bill->state->save();

        return parent::accept($bill);
    }

    public static function isVisible($state)
    {
        return true;
    }

}
