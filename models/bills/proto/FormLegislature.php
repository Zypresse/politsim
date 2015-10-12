<?php

namespace app\models\bills\proto;

use app\models\Org;

/**
 * Сформировать законодательную власть
 *
 * @author ilya
 */
class FormLegislature extends BillProto {

    public static $id = 9;
    public static $name = "Сформировать законодательную власть";

    public static function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }

        $data = json_decode($bill->data);

        if (is_null($bill->state->legislatureOrg)) {
            $org = Org::generate($bill->state, Org::LEGISLATURE_PARLIAMENT10);
            $bill->state->legislature = $org->id;
            $bill->state->save();
        }

        return parent::accept($bill);
    }

    public static function isVisible($state)
    {
        return is_null($state->legislatureOrg);
    }

}
