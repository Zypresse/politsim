<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\Wood,
    app\models\resources\proto\types\Lumber;

/**
 * Лесопилка
 *
 * @author ilya
 */
class WoodPlant extends FactoryProto
{

	public function getId()
	{
		return 36;
	}

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 19
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new Wood
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Lumber
        ];
    }

}
