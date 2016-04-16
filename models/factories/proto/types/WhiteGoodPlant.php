<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\NFMetal,
    app\models\resources\proto\types\Electronics,
    app\models\resources\proto\types\Accumulator,
    app\models\resources\proto\types\WhiteGood;

/**
 * завод бытовой техники
 *
 * @author ilya
 */
class WhiteGoodPlant extends FactoryProto
{

	public function getId()
	{
		return 35;
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
            new Electronics,
            new Accumulator,
            new NFMetal
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new WhiteGood
        ];
    }

}
