<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\Sand,
    app\models\resources\proto\types\Bricks;

/**
 * Кирпичный завод
 *
 * @author ilya
 */
class BrickPlant extends FactoryProto
{

	public function getId()
	{
		return 14;
	}

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 20
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Sand,
            new Electricity
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Bricks
        ];
    }

}
