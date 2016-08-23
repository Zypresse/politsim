<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\NFMetal,
    app\models\resources\proto\types\Electronics;

/**
 * завод электродвигателей
 *
 * @author ilya
 */
class ElectronicsPlant extends FactoryProto
{

	public function getId()
	{
		return 18;
	}

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 24
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new NFMetal
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Electronics
        ];
    }
}
