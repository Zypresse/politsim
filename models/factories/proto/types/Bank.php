<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto;

/**
 * Description of Bank
 *
 * @author ilya
 */
class Bank extends FactoryProto {
    
	public function getId()
	{
		return 3;
	}

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 13
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [];
    }    
    
    public function getResourcesForSell()
    {
        return [];
    }
    
}
