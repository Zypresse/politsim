<?php

namespace app\models\bills\proto;

use app\models\licenses\LicenseRule;

/**
 * Сменить порядок выдачи лицензий
 *
 * @author ilya
 */
class ChangeLicenseRule extends BillProto {

    public $id = 11;
    public $name = "Сменить порядок выдачи лицензий";

    public function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }

        $data = json_decode($bill->data);

        $sl = LicenseRule::findOrCreate([
            'state_id' => $bill->state_id,
            'proto_id' => $data->license_proto_id
        ], false);
        
        $data->cost = floatval($data->cost);
        $data->cost = $data->cost > 0 ? $data->cost : 0;
        $data->cost_noncitizens = floatval($data->cost_noncitizens);
        $data->cost_noncitizens = $data->cost_noncitizens > 0 ? $data->cost_noncitizens : 0;

        $sl->cost = $data->cost;
        $sl->cost_noncitizens = $data->cost_noncitizens;
        $sl->is_need_confirm = ($data->is_need_confirm ? 1 : 0);
        $sl->is_need_confirm_noncitizens = ($data->is_need_confirm_noncitizens ? 1 : 0);
        $sl->is_only_goverment = ($data->is_only_goverment ? 1 : 0);
        if (!$sl->save()) {
            var_dump($sl->getErrors());
            return false;
        }

        return parent::accept($bill);
    }

    public function isVisible($state)
    {
        return true;
    }

}