<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Tube,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\FMetal;

/**
 * Трубопрокатный завод 
 *
 * @author ilya
 */
class TubePlant extends FactoryProto
{

	public function getId()
	{
		return 32;
	}

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 25
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new FMetal
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Tube
        ];
    }

}
