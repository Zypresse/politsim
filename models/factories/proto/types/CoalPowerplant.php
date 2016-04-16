<?php

namespace app\models\factories\proto\types;

use app\models\resources\proto\types\Coal,
    app\models\resources\proto\types\Electricity;
/**
 * Description of CoalPowerplant
 *
 * @author ilya
 */
class CoalPowerplant extends TermoPowerplant {

	public function getId()
	{
		return 8;
	}

    public function getResourcesForBuy()
    {
        return [
            new Coal
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Electricity
        ];
    }

}
