<?php

namespace app\models\bills\proto;


/**
 * Отправить в отставку главу государства
 *
 * @author ilya
 */
class DropStateleader extends BillProto {

    public $id = 14;
    public $name = "Отправить в отставку главу государства";

    public function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }

        if ($bill->state->executiveOrg->leader->user) {
            $bill->state->executiveOrg->leader->user->post_id = 0;
            $bill->state->executiveOrg->leader->user->save();
            $bill->state->executiveOrg->next_elect = time() + 48 * 60 * 60;
            if (!$bill->state->executiveOrg->save()) {
                var_dump($bill->state->executiveOrg->getErrors());
            }
        }

        return parent::accept($bill);
    }

    public function isVisible($state)
    {
        return (!(is_null($state->executiveOrg->leader->user))) && $state->legislatureOrg && $state->legislatureOrg->can_drop_stateleader;
    }

}