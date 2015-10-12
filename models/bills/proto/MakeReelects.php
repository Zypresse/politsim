<?php

namespace app\models\bills\proto;

use app\models\Org;

/**
 * Провести перевыборы
 *
 * @author ilya
 */
class MakeReelects extends BillProto {

    public static $id = 10;
    public static $name = "Провести перевыборы";

    public static function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }

        $data = json_decode($bill->data);

        $org_id = explode('_', $data->elected_variant)[0];
        $org = Org::findByPk($org_id);
        if ($org && $org->state_id === $bill->state_id) {
            $org->next_elect = time() + 48 * 60 * 60;
            $org->save();
        }

        return parent::accept($bill);
    }

    public static function isVisible($state)
    {
        return true;
    }

}
