<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Wood;

/**
 * Лесопилка
 *
 * @author ilya
 */
class Sawmill extends FactoryProto
{
    
    public function getId()
    {
        return 21;
    }

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 7
            ])
        ];
    }

    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff($region)
    {
        return $region->getDiggingEff(8)->k;
    }

    public function getResourcesForBuy()
    {
        return [];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Wood
        ];
    }
    
}
