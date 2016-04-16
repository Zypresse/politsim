<?php

namespace app\models\factories\proto\types;

use app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\Gasoline;

/**
 * Description of OilPowerplant
 *
 * @author ilya
 */
class OilPowerplant extends TermoPowerplant
{
    
    public function getId()
    {
        return 6;
    }

    public function getResourcesForBuy()
    {
        return [
            new Gasoline
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Electricity
        ];
    }
    
}
