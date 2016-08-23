<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Fruits,    
    app\models\resources\proto\types\Alcohol;

/**
 * винзавод
 *
 * @author ilya
 */
class AlcoholPlant extends FactoryProto
{

	public function getId()
	{
		return 12;
	}

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 28
            ])
        ];
    }


    public function getResourcesForBuy()
    {
        return [
            new Fruits
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Alcohol
        ];
    }

}
