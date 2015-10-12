<?php

namespace app\models\bills\proto;


/**
 * Отправить в отставку главу государства
 *
 * @author ilya
 */
class DropStateleader extends BillProto {

    public static $id = 14;
    public static $name = "Отправить в отставку главу государства";

    public static function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }

        $data = json_decode($bill->data);

        if ($bill->state->executiveOrg->leader->user) {
            $bill->state->executiveOrg->leader->user->post_id = 0;
            $bill->state->executiveOrg->leader->user->save();
            $bill->state->executiveOrg->next_elect = time() + 48 * 60 * 60;
            $bill->state->executiveOrg->save();
        }

        return parent::accept($bill);
    }

    public static function isVisible($state)
    {
        return (!(is_null($state->executiveOrg->leader->user))) && $state->legislatureOrg->can_drop_stateleader;
    }

}
