<?php

namespace app\models\bills\proto;

use app\models\Region,
    app\models\State,
    app\models\Org,
    app\models\CoreCountry,
    app\models\constitution\ConstitutionFactory,
    app\components\MyHtmlHelper;

/**
 * Выделить государство-сателлит
 *
 * @author ilya
 */
class CreateSattellite extends BillProto {

    public $id = 13;
    public $name = "Выделить государство-сателлит";

    public function accept($bill)
    {
        if (is_null($bill->state)) {
            return $bill->delete();
        }

        $data = json_decode($bill->data);

        $core = ($data->core_id) ? CoreCountry::findByPk($data->core_id) : null;
        
        $region = Region::findByPk($data->new_capital);
        if ($region && $region->state_id === $bill->state_id) {
            $state = new State();
            $state->name = $data->new_name;
            $state->short_name = $data->new_short_name;
            $state->flag = "http://placehold.it/300x200/eeeeee/000000&text=" . urlencode(MyHtmlHelper::transliterate($data->new_short_name));
            $state->capital = $data->new_capital;
            $state->color = $bill->state->color;
            $state->core_id = $data->core_id;
            $state->allow_register_parties = 1;
            $state->leader_can_drop_legislature = 1;
            $state->allow_register_holdings = 1;
            $state->register_parties_cost = 0;

            if (!$state->save()) {
                var_dump($state->getErrors());
                return false;
            }
            
            $region->state_id = $state->id;
            if (!$region->save()) {
                var_dump($region->getErrors());
                return false;
            }

            $executive = Org::generate($state, Org::EXECUTIVE_PRESIDENT);
            $state->executive = $executive->id;

            $legislature = Org::generate($state, Org::LEGISLATURE_PARLIAMENT10);
            $state->legislature = $legislature->id;

            if (!$state->save()) {
                var_dump($state->getErrors());
                return false;
            }

            ConstitutionFactory::generate('PresidentRepublic', $state->id);

            if ($data->core_id && $core) {
                foreach ($core->regions as $region) {
                    if ($region->state_id === $bill->state_id && !($region->isCapital())) {
                        $region->state_id = $state->id;
                        if (!$region->save()) {
                            var_dump($region->getErrors());
                        }
                    }
                }
            }
        }

        return parent::accept($bill);
    }

    public function isVisible($state)
    {
        return (intval($state->getRegions()->count()) > 1);
    }

}