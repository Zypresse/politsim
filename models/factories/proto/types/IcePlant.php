<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\NFMetal,
    app\models\resources\proto\types\FMetal,
    app\models\resources\proto\types\ICEngine;

/**
 * Завод по производству льда
 * (на самом деле нет)
 * @author ilya
 */
class IcePlant extends FactoryProto
{

	public function getId()
	{
		return 21;
	}

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 22
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new NFMetal,
            new FMetal
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new ICEngine
        ];
    }

}
