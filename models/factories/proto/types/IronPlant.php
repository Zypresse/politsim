<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\FOre,
    app\models\resources\proto\types\FMetal;

/**
 * Сталеплавильный завод
 *
 * @author ilya
 */
class IronPlant extends FactoryProto
{

    public function getId()
    {
        return 23;
    }

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 16
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new FOre
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new FMetal
        ];
    }

}
