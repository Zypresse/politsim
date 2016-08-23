<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\NFMetal,
    app\models\resources\proto\types\FMetal,
    app\models\resources\proto\types\Electronics,
    app\models\resources\proto\types\JetEngine;

/**
 * завод реактивных двигателей
 *
 * @author ilya
 */
class JetEnginesPlant extends FactoryProto
{

	public function getId()
	{
		return 24;
	}

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 23
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new NFMetal,
            new FMetal,
            new Electronics
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new JetEngine
        ];
    }

}

