<?php

namespace app\models\bills\proto;

use app\models\Org;

/**
 * Сформировать законодательную власть
 *
 * @author ilya
 */
class FormLegislature extends BillProto {

    public $id = 9;
    public $name = "Сформировать законодательную власть";

    public function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }

        if (is_null($bill->state->legislatureOrg)) {
            $org = Org::generate($bill->state, Org::LEGISLATURE_PARLIAMENT10);
            $bill->state->legislature = $org->id;
            if (!$bill->state->save()) {
                var_dump($bill->state->getErrors());
                return false;
            }
        }

        return parent::accept($bill);
    }

    public function isVisible($state)
    {
        return is_null($state->legislatureOrg);
    }

}