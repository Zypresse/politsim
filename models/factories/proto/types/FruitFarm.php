<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto;

/**
 * Фруктовый сад
 *
 * @author ilya
 */
class FruitFarm extends FactoryProto {
    
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 8
            ])
        ];
    }

    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff($region)
    {
        return $region->getDiggingEff(10)->k;
    }
    
}
