<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\Wool,
    app\models\resources\proto\types\Dress;

/**
 * завод одежды
 *
 * @author ilya
 */
class DressPlant extends FactoryProto
{

	public function getId()
	{
		return 16;
	}

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 27
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new Wool
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Dress
        ];
    }

}
