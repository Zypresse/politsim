<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\NFOre,
    app\models\resources\proto\types\NFMetal;

/**
 * Плавильный завод цветных металлов
 *
 * @author ilya
 */
class NonferrousPlant extends FactoryProto
{

    public function getId()
    {
        return 27;
    }

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 15
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new NFOre
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new NFMetal
        ];
    }

}
