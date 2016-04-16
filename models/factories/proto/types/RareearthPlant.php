<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\REOre,
    app\models\resources\proto\types\REMetal;

/**
 * Плавильный завод редкоземельных металлов
 *
 * @author ilya
 */
class RareearthPlant extends FactoryProto
{
    
    public function getId()
    {
        return 29;
    }

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 17
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new REOre
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new REMetal
        ];
    }

}
