<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\REMetal,
    app\models\resources\proto\types\Electronics,
    app\models\resources\proto\types\SolarPanel;

/**
 * завод электродвигателей
 *
 * @author ilya
 */
class SolarPanelPlant extends FactoryProto
{

	public function getId()
	{
		return 31;
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
            new REMetal,
            new Electronics
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new SolarPanel
        ];
    }

}
