<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Sand;

/**
 * Карьер по добыче песка (добываемых стройматериалов)
 *
 * @author ilya
 */
class Career extends FactoryProto {
    
    public function getId()
    {
        return 15;
    }

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 9
            ])
        ];
    }

    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff($region)
    {
        return $region->getDiggingEff(14)->k;
    }

    public function getResourcesForBuy()
    {
        return [];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Sand
        ];
    }
    
}
