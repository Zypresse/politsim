<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\Lumber,
    app\models\resources\proto\types\Furniture;

/**
 * завод мебели
 *
 * @author ilya
 */
class FurniturePlant extends FactoryProto
{

	public function getId()
	{
		return 20;
	}

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 29
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new Lumber
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Furniture
        ];
    }

}
