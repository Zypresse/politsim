<?php

namespace app\models\bills\proto;

use app\models\Org;

/**
 * Переименовать организацию
 *
 * @author ilya
 */
class RenameOrg extends BillProto {

    public static $id = 12;
    public static $name = "Переименовать организацию";

    public static function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }

        $data = json_decode($bill->data);

        $org = Org::findByPk($data->org_id);
        if ($org && $org->state_id === $bill->state_id) {
            $org->name = $data->new_name;
            $org->save();
        }

        return parent::accept($bill);
    }

    public static function isVisible($state)
    {
        return true;
    }

}
