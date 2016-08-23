<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\FMetal,
    app\models\resources\proto\types\NFMetal,
    app\models\resources\proto\types\ElectricEngine;

/**
 * завод электродвигателей
 *
 * @author ilya
 */
class ElectricEnginesPlant extends FactoryProto
{

	public function getId()
	{
		return 17;
	}

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 21
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new FMetal,
            new NFMetal
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new ElectricEngine
        ];
    }

}
