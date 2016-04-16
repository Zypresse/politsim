<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\UOre,
    app\models\resources\proto\types\EnrichedUranium,
    app\models\resources\proto\types\DepletedUranium;

/**
 * Обогатительный завод урана
 *
 * @author ilya
 */
class UranPlant extends FactoryProto
{

	public function getId()
	{
		return 34;
	}

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 18
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new UOre
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new EnrichedUranium,
            new DepletedUranium
        ];
    }

}
